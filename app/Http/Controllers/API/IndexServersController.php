<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Models\Server;
use Illuminate\Http\Request;

class IndexServersController extends Controller
{
    public function __invoke()
    {
        return ServerResource::collection(Server::active()->get());
    }
}
