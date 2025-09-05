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
        // Usar configurações do Laravel se os parâmetros não forem fornecidos
        $this->certificate_crt = config('services.santander.certificates.crt');
        $this->certificate_key = config('services.santander.certificates.key');
        $this->client_id = config('services.santander.client_id');
        $this->client_secret = config('services.santander.client_secret');
        $this->url = config('services.santander.url');
    }

    public function getToken()
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Cookie' => '_abck=63244B4BEFF3986E2D4049513324A8C2~-1~YAAQhEIVAtSOWP2YAQAAqZ30DA6J49EebIG/5HAOnxKTPPaAHPXGyXoOhbdyrH4HkUXwo+C+y/Zp9P5TEVnSirH3GPAr5wzB4Za3Psucsm01BZ3/7Om0tkKqEsXMS2p2E2v7ZEwCbsjstutcKq3golP7RjAUyE1SapS4ioObsPr+NXAn4NcDutCNwDDwO/U+U1DTWAUn7/+bzRA+7/QDGqBgVubdFr6dK3iXPbcUFMi3XeWb7ZExrnjCrVLtTIDN6DSdyvN1O+ZZPdkkLbI4T0eS2ipgdmsSZwJkIab+lq7cNiltNaV3PP7+2vqkH2BLdkXCv6A5wBeiQ01zuhAZq4W2gZ3rMPu7MwlfxqLYP62oaK/y5sUlZvQiWC1RlWPfObUwWd9jvK6id20dyF1mFxQbrQIOWJ5op63iaGaUGs8GpQIlxQuHSm62PUTFZRpq59xwVlKbnN2csbQ=~-1~-1~-1~~; bm_sz=F83CACB573F18566261F5ADD1B746FD0~YAAQhEIVAtaOWP2YAQAAqZ30DByBmjzYuf27ujW1F35wA3w4mXK+G3YNXRk9jbOyY689r8JM0vbhgR5nKh1oBYNFfgQ+kwMLqRHQW1FPEm1Flo3P3+xSHTbHKAPJ2ZM4mgXkWy0h77cLuYd19YA58ohbPZG908v57lHvmhrIuniudRPlK6QTesjx9TrtSULDii5PpF7pAmRLWjFUyZLAyQTDExIK2wmJWcz+10lU07kPBcKga30ybenjKdMrrmxxPJdUKm715TU3zyy91zEs9TwY2RN0RHoiEqwtYfMADI+9ROFhy1CiWTDnLBG87pP+y/ikbDhU938y1bLrpSr4PIBGAqiT1fdhzvCQ0vvvfI/WAOxRYQU=~3687223~4470323; ak_bmsc=243669CB7B8DD69C19D9A599F1A57E27~000000000000000000000000000000~YAAQhEIVAtWOWP2YAQAAqZ30DBxg1dJKuFbLksFg7QTV6OUJZUlYD8BbQNVa6FqG0qBBNZe7E/tbCCvflpWwE0dZgEQ/cByu96YDlaHWAf6x6OVTf3zU0kCdwpS9WarLvggNH2O8h77fViQF0givZzAaQskvcqTewZUgKey0uliBvs7Bme7QtQ5Y++drN35ekc8yPhHVuwpvC2I/HOko5MWchiUqgiInRVk9ufBkVxnyOV0DpgVVKzAnKJWTg0IIwbROZ+oBYkC+xJgMLafqsiDG5ovz48lZXbMLilobRjYiUMm1itReIZdWM1y7FE0tVty6ZpzTKHoBEuc7jDG9DjNLAygJZn5N8KLl7sQp8ZPO3DaVcar4uq4=; bm_sv=9C6980AAC9ECF065AD7C4D312B98F004~YAAQhEIVAofUWP2YAQAA0Pz0DBwKp/pwcASn06+3t0u6Gs+LA8iKl/ksVRl5fe91+0d8iwhf/P3ucvTiDXD/M0j/HeGwuSy3BwAmrwJy9uQeLUznv3LWA7aedkhhgyfuoEvdkapk5+w26vcvkD9ZxXXNftzEZIqFbo1EfQpa0KeaxhZgSzjFyLGROOn804JK7DX8jR8Su4zAms0v3iWUzPHExHPkIP9ZlnfDguEef4msw/HhOLhwCqrVbHYlT1YmhEXp2me9wnGaCA==~1'
        ];
        $options = [
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'client_credentials'
            ]
        ];
        // Usar a URL da configuração ao invés de hardcoded
        $request = new Request('POST', $this->url, $headers);
        $res = $client->sendAsync($request, $options)->wait();
        echo $res->getBody();
    }
}
