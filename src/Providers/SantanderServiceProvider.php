<?php
namespace Santander\Providers;
use Illuminate\Support\ServiceProvider;
use Santander\Console\Commands\SantanderInstallCommand;

class SantanderServiceProvider extends ServiceProvider
{
    public function register()
    {
        
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SantanderInstallCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../stubs/certificates/' => base_path('storage/certificates/santander/'),
        ], 'santander-certificates');
    }
}