<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('weather:notifications', function () {
    $this->info('Starting weather notifications process...');
    
    try {
        Artisan::call('weather:send-forecasts');
        $output = Artisan::output();
        $this->info($output);
        $this->info('Weather notifications process completed successfully.');
    } catch (\Exception $e) {
        $this->error('Weather notifications process failed: ' . $e->getMessage());
    }
})->purpose('Process weather notifications including forecasts')->everyMinute();