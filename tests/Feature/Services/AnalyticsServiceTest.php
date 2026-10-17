<?php

use App\Models\Setting;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

it('does not return fake analytics data when api credentials are missing', function () {
    Setting::create([
        'group' => 'seo',
        'key' => 'ga_measurement_id',
        'value' => 'G-ABC1234567',
    ]);

    Cache::flush();

    $service = app(AnalyticsService::class);

    expect($service->isConfigured())->toBeTrue()
        ->and($service->getSummary())->toBeNull()
        ->and($service->getDailyTrend())->toBeNull()
        ->and($service->getTopPages())->toBeNull();
});
