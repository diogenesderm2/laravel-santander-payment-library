<?php

namespace Santander\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SantanderInstallCommand extends Command
{
    protected $signature = 'santander:install {--force : Sobrescrever arquivos existentes}';
    protected $description = 'Instala e configura a biblioteca Santander Payment';

    public function handle()
    {
        $this->info('🚀 Instalando Santander Payment Library...');

        // 1. Publicar arquivo de configuração
        $this->call('vendor:publish', [
            '--tag' => 'santander-config',
            '--force' => $this->option('force')
        ]);

        // 2. Criar diretório para certificados
        $certificatesPath = storage_path('certificates/santander');
        if (!File::exists($certificatesPath)) {
            File::makeDirectory($certificatesPath, 0755, true);
            $this->info('✅ Diretório de certificados criado: ' . $certificatesPath);
        }

        // 3. Publicar stubs de certificados
        $this->call('vendor:publish', [
            '--tag' => 'santander-certificates',
            '--force' => $this->option('force')
        ]);

        // 4. Adicionar variáveis ao .env
        $this->addEnvironmentVariables();

        $this->info('✅ Santander Payment Library instalada com sucesso!');
        $this->newLine();
        $this->warn('📝 Próximos passos:');
        $this->line('1. Configure as variáveis no arquivo .env');
        $this->line('2. Adicione seus certificados em: ' . $certificatesPath);
        $this->line('3. Execute: php artisan config:cache');
    }

    private function addEnvironmentVariables()
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');
        
        $envVars = [
            'SANTANDER_URL=https://trust-open.api.santander.com.br/auth/oauth/v2/token',
            'SANTANDER_CLIENT_ID=your_client_id_here',
            'SANTANDER_CLIENT_SECRET=your_client_secret_here',
            'SANTANDER_CERTIFICATE_CRT=certificates/santander/certificate.crt',
            'SANTANDER_CERTIFICATE_KEY=certificates/santander/private.key',
        ];

        foreach ([$envPath, $envExamplePath] as $file) {
            if (File::exists($file)) {
                $content = File::get($file);
                
                foreach ($envVars as $var) {
                    $key = explode('=', $var)[0];
                    if (!str_contains($content, $key)) {
                        File::append($file, PHP_EOL . $var);
                    }
                }
            }
        }
        
        $this->info('✅ Variáveis de ambiente adicionadas ao .env');
    }
}