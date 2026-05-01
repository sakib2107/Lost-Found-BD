<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule cleanup of orphaned temp search images
app(Schedule::class)->command('cleanup:orphaned-temp-images --hours=2')
    ->hourly()
    ->description('Clean up orphaned temporary search images');
