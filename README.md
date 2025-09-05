# Laravel Santander Payment Library
- composer require laravel-santander/payment-library

Biblioteca PHP para geração de QR codes, boletos e pagamentos PIX integrada com a API do Santander.

## Compatibilidade

- PHP >= 8.1
- Laravel 9.x, 10.x, 11.x, 12.x

### Dependências

- Guzzle HTTP Client
- DomPDF
- Illuminate Support
- Illuminate Console

## Instalação

### 1. Instalar via Composer

```bash
composer require laravel-santander/payment-library
```

### 2. Publicar Configurações (Automático)

O service provider será registrado automaticamente. Execute o comando de instalação:

```bash
php artisan santander:install
```

Este comando irá:
- ✅ Publicar o arquivo de configuração `config/santander.php`
- ✅ Criar o diretório `storage/certificates/santander/`
- ✅ Adicionar variáveis de ambiente ao `.env`
- ✅ Configurar o arquivo `config/services.php`

### 3. Configurar Variáveis de Ambiente

Após a instalação, configure as seguintes variáveis no seu arquivo `.env`:

```env
SANTANDER_URL=https://trust-open.api.santander.com.br/auth/oauth/v2/token
SANTANDER_CLIENT_ID=seu_client_id_aqui
SANTANDER_CLIENT_SECRET=seu_client_secret_aqui
SANTANDER_CERTIFICATE_CRT=certificates/santander/certificate.crt
SANTANDER_CERTIFICATE_KEY=certificates/santander/private.key
SANTANDER_SSL_VERIFY_PEER=false
SANTANDER_SSL_VERIFY_HOST=false
```

### 4. Adicionar Certificados

Coloque seus certificados do Santander no diretório:

