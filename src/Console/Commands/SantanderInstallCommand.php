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

        // 5. Adicionar configuração ao services.php
        $this->addToServicesConfig();

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

    private function addToServicesConfig()
    {
        $servicesPath = config_path('services.php');

        if (!File::exists($servicesPath)) {
            $this->error('❌ Arquivo services.php não encontrado!');
            return;
        }

        $content = File::get($servicesPath);

        // Verificar se a configuração do Santander já existe
        if (str_contains($content, "'santander'")) {
            $this->warn('⚠️  Configuração do Santander já existe no services.php');
            return;
        }

        // Configuração do Santander para adicionar
        $santanderConfig = "
    'santander' => [
        'url' => env('SANTANDER_URL', 'https://trust-open.api.santander.com.br/auth/oauth/v2/token'),
        'client_id' => env('SANTANDER_CLIENT_ID'),
        'client_secret' => env('SANTANDER_CLIENT_SECRET'),
        'certificates' => [
            'crt' => env('SANTANDER_CERTIFICATE_CRT', 'certificates/santander/certificate.crt'),
            'key' => env('SANTANDER_CERTIFICATE_KEY', 'certificates/santander/private.key'),
        ],
        'ssl' => [
            'verify_peer' => env('SANTANDER_SSL_VERIFY_PEER', false),
            'verify_host' => env('SANTANDER_SSL_VERIFY_HOST', false),
        ],
    ],";

        // Encontrar a posição antes do fechamento do array
        $pattern = '/\];\s*$/';
        if (preg_match($pattern, $content)) {
            $newContent = preg_replace($pattern, $santanderConfig . "\n];", $content);

            File::put($servicesPath, $newContent);
            $this->info('✅ Configuração do Santander adicionada ao services.php');
        } else {
            $this->error('❌ Não foi possível adicionar a configuração ao services.php');
            $this->line('Por favor, adicione manualmente a seguinte configuração:');
            $this->line($santanderConfig);
        }
    }
}
