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
        // TODO return server votes
        return [];
    }
}
