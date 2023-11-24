<?php

use App\Models\Record;
use App\Models\Server;

it('creates record correctly', function () {
    $server = Server::factory()->createOne();

    $server->records()->create(Record::factory()->definition());

    $this->assertDatabaseCount('records', 1);
});

it('returns records correctly', function () {
    $server = Server::factory()->hasRecords(10)->createOne();

    $records = $server->records;

    $this->assertCount(10, $records);
});
