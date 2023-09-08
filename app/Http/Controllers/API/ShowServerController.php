<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShowServerController extends Controller
{
    public function stats(Server $server)
    {
        return ServerResource::make($server);
    }

    public function favicon(Server $server)
    {
        $path = "servers/$server->id/favicon.png";

        abort_if(! Storage::exists($path), 404);

        return Storage::response($path);
    }
}
