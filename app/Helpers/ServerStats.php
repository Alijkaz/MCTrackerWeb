<?php

namespace App\Helpers;

use App\Models\GameMode;
use App\Models\Server;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JJG\Ping;
use xPaw\MinecraftPing;

/**
 * It's used to fetch data for a Minecraft Server
 */
class ServerStats
{
    public function __construct(
        private string          $host,
        private int             $port,
        private readonly int    $players,
        private readonly int    $maxPlayers,
        private readonly int    $latency,
        private readonly string $version,
        private readonly string $favIcon,
        private readonly array  $gameModes,
        private readonly Server $server
    )
    {
    }

    // TODO extract these two methods to helper
    public static function resolveSRV(string $address): string
    {
        if(ip2long($address) !== false) {
            return $address;
        }

        $record = @dns_get_record('_minecraft._tcp.' . $address, DNS_SRV);

        if(empty($record)) {
            return $address;
        }

        if(isset($record[0]['target'])) {
            return $record[0]['target'];
        }

        return $address;
    }

    public static function extractGameModesFromQuery(?array $query, Collection $gameModes): array
    {
        $result = [];

        foreach ($query['players']['sample'] as $line) {
            $line = preg_replace('/§[A-Za-z1-9]/', '', $line['name']);

            foreach ($gameModes as $gameMode) {
                if (str($line)->contains($gameMode, true)) {
                    $matches = [];
                    preg_match_all('/\d+/', $line, $matches);
                    $players = array_map('intval', $matches[0]);

                    $result[strtolower($gameMode)] = array_sum($players);

                    break;
                }
            }
        }

        return $result;
    }

    public static function fetch(Server $server, int $retries = 3): ?ServerStats
    {
        $minecraftPing = null;
        $context = ['server' => $server->name];
        $result = null;

        $cacheTTL = app()->isProduction() ? now()->addHours() : 0;
        $gameModes = Cache::remember('gamemodes', $cacheTTL, fn () => GameMode::query()->get('name')->pluck('name'));

        try {
            retry($retries, function () use ($server, $gameModes, &$minecraftPing, &$result) {
                $resolvedSrv = self::resolveSRV($server->address);

                $latency = (new Ping($resolvedSrv, timeout: 3))->ping('fsockopen') ?? 0;

                $minecraftPing = new MinecraftPing($server->address, 25565, 10);
                $query = $minecraftPing->Query();

                $context['query'] = $query;

                $online = $query['players']['online'];

                // Will return count of online players if max players is negative or 0
                $max = $query['players']['max'];
                $max = $max <= 0 ? $online : $max;

                $gameModes = self::extractGameModesFromQuery($query, $gameModes);

                $result = new self($server->address, 25565, $online, $max, $latency, $query['version']['name'], $query['favicon'], $gameModes, $server);

                Log::info('Tracking successful', $context);
            }, 250);
        } catch (\Exception $exception) {
            Log::warning('Tracking server failed: ' . $exception->getMessage(), $context);
        }

        $minecraftPing?->Close();

        return $result;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getPlayers(): int
    {
        return $this->players;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public function getLatency(): int
    {
        return $this->latency;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getFavIcon(): string
    {
        return $this->favIcon;
    }

    public function getGameModes(): array
    {
        return $this->gameModes;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    /* Methods */
    public function storeFavicon(): ?string
    {
        $base64_image = $this->getFavIcon();

        if ($base64_image) {
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $favPath = "servers/{$this->getServer()->slug}/favicon.png";
            Storage::put($favPath, base64_decode($file_data));

            return $favPath;
        }

        return null;
    }

    public function storeMOTD(): ?string
    {
        $server = $this->getServer();

        $motdUrl = "http://status.mclive.eu/$server->name/$this->address/$this->port/banner.png";

        try {
            $motd = file_get_contents($motdUrl);
            $motdPath = "servers/{$server->slug}/motd.png";
            Storage::put($motdPath, $motd);
        } catch (\Exception $exception) {
            Log::warning('Storing motd for server failed: ' . $exception->getMessage(), ['url' => $motdUrl, 'server' => $server->id]);
        }



        return null;
    }

}
