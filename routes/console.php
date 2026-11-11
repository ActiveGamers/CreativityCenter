<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Horizon keeps its queue metrics in Redis and the dashboard reads them from
 * the snapshots. Without this schedule the graphs stay empty, so the snapshot
 * is taken every five minutes (Horizon's documented cadence).
 */
Schedule::command('horizon:snapshot')->everyFiveMinutes();
