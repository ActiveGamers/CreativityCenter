<?php

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;

/*
 * Production runs two blue/green colours, each with its own scheduler
 * container. Without the one-server lock both would take the Horizon snapshot
 * every five minutes.
 */
test('the horizon snapshot is locked to one server', function () {
    $snapshot = collect(app(Schedule::class)->events())
        ->first(fn (Event $event): bool => str_contains((string) $event->command, 'horizon:snapshot'));

    expect($snapshot)->not->toBeNull()
        ->and($snapshot->onOneServer)->toBeTrue();
});
