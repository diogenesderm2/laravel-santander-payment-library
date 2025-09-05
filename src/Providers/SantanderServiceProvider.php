<?php
namespace Santander\Providers;
use Illuminate\Support\ServiceProvider;
use Santander\Console\Commands\SantanderInstallCommand;

class SantanderServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registrar serviços se necessário
    }

    public function boot()
    {
        // Registrar o comando
        if ($this->app->runningInConsole()) {
            $this->commands([
                SantanderInstallCommand::class,
            ]);
        }

        // Publicar arquivos de configuração
        $this->publishes([
            __DIR__.'/../config/santander.php' => config_path('santander.php'),
        ], 'santander-config');

        // Publicar stubs de certificados se necessário
        $this->publishes([
            __DIR__.'/../stubs/certificates/' => base_path('storage/certificates/santander/'),
        ], 'santander-certificates');
    }
}