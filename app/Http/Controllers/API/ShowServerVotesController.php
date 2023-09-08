<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Models\Server;
use Illuminate\Http\Request;

class ShowServerVotesController extends Controller
{
    public function __invoke(Server $server)
    {
        return $server->votes()->limit(10)->get()->map(fn ($vote) => [
            'id' => $vote->id,
            'username' => $vote->username,
            'voted_at' => $vote->created_at->timestamp
        ]);
    }
}
