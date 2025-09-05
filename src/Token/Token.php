<?php

namespace Santander\Token;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Token
{
    private string $certificate_crt;
    private string $certificate_key;
    private string $client_id;
    private string $client_secret;
    private string $url;

    public function __construct()
    {
        $this->certificate_crt = config('services.santander.certificates.crt');
        $this->certificate_key = config('services.santander.private.key');
        $this->client_id = config('services.santander.client_id');
        $this->client_secret = config('services.santander.client_secret');
        $this->url = config('services.santander.url');
    }

    public function getToken()
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $options = [
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'client_credentials'
            ],
            'cert' => [
               $this->certificate_crt,
                $this->certificate_key
            ],
            'verify' => config('services.santander.ssl.verify_peer', false),
            'ssl_key' => $this->certificate_key,
        ];
        // Usar a URL da configuração ao invés de hardcoded
        $request = new Request('POST', $this->url, $headers);
        $res = $client->sendAsync($request, $options)->wait();
        echo $res->getBody();
    }
}
