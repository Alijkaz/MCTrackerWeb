<?php

namespace App\Helpers;

use App\Models\Server;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use xPaw\MinecraftPing;

/**
 * It's used to fetch data for a Minecraft Server
 */
class ServerStats
{
    public function __construct(
        private readonly string $host,
        private readonly int    $port,
        private readonly int    $players,
        private readonly int    $maxPlayers,
        private readonly int    $latency,
        private readonly string $version,
        private readonly string $favIcon,
        private readonly Server $server
    )
    {
    }

    public static function fetch(Server $server, int $retries = 3): ?ServerStats
    {
        $ping = null;
        $context = ['server' => $server->name];
        $result = null;

        try {
            retry($retries, function () use ($server, &$ping, &$result) {
                $ping = new MinecraftPing($server->address, 25565);
                $query = $ping->Query();

                $context['query'] = $query;

                $latency = 0; // TODO fix me

                Log::info('Tracking successful', $context);

                $result = new self($server->address, 25565, $query['players']['online'], $query['players']['max'], $latency, $query['version']['name'], $query['favicon'], $server);
            }, 250);
        } catch (\Exception $exception) {
            Log::warning('Tracking server failed: ' . $exception->getMessage(), $context);
        }

        $ping?->Close();

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
            $favPath = "servers/{$this->getServer()->id}/favicon.png";
            Storage::put($favPath, base64_decode($file_data));

            return $favPath;
        }

        return null;
    }
}
