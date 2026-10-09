<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('admin', fn () => (bool) auth()->user()?->isAdmin());

        View::composer('layouts.app', function ($view) {
            $view->with('namaSekolah', Setting::get('nama_sekolah', 'Sistem Absensi'));
        });
    }
}
