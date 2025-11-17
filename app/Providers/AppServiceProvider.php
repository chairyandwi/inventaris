<?php

namespace App\Providers;

use App\Models\AppConfiguration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appConfig = Cache::remember('app_config', 3600, function () {
            return AppConfiguration::first();
        });

        View::share('globalAppConfig', $appConfig);

        if ($appConfig && $appConfig->apply_email) {
            if (!empty($appConfig->email)) {
                Config::set('mail.from.address', $appConfig->email);
            }
            if (!empty($appConfig->nama_kampus)) {
                Config::set('mail.from.name', $appConfig->nama_kampus);
            }
        }
    }
}
