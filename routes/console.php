<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Commands
|--------------------------------------------------------------------------
*/
Schedule::command('ads:deactivate-expired')->daily();
Schedule::command('trash:cleanup')->daily();
Schedule::command('cache:warm')->hourly();
Schedule::command('queue:prune-batches --hours=48')->daily();
Schedule::command('queue:flush')->daily(); // clear failed jobs >1 day old (optional)
Schedule::command('blogs:publish-scheduled')->everyMinute();
