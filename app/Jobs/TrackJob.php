<?php

namespace App\Jobs;

use App\Helpers\ServerStats;
use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TrackJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $servers = Server::active()->get();

        Log::info('Going to track servers', ['servers' => $servers->pluck('name')]);

        /** @var Server $server */
        foreach ($servers as $server) {
            $serverStats = $server->fetchStats();

            // Store server record (players, version, etc.)
            $this->updateServer($server, $serverStats);

            // Store favicon on system
            $serverStats?->storeFavIcon();
        }
    }

    private function updateServer(Server $server, ?ServerStats $serverStats): void
    {
        $date = now()->startOfMinute()->timestamp;

        // TODO update/insert all in a single query

        $serverUpdateContext = ['gamemodes' => json_encode($serverStats?->getGameModes() ?? [])];
        if ($server->up_from < 0) {
            $serverUpdateContext['up_from'] = time();
        }
        $server->update($serverUpdateContext);

        $server->records()->create([
            'players' => $serverStats?->getPlayers(),
            'max_players' => $serverStats?->getMaxPlayers(),
            'version' => $serverStats?->getVersion(),
            'latency' => $serverStats?->getLatency(),
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }
}
