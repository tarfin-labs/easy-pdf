<?php

namespace TarfinLabs\EasyPdf;

use Illuminate\Support\ServiceProvider;

class EasyPdfServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('easy-pdf.php'),
            ], 'easy-pdf');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Register the main class to use with the facade
        $this->app->singleton(EasyPdf::class, function () {
            return new EasyPdf();
        });
    }
}
