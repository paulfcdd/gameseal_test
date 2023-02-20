<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyApi\Client;

use GuzzleHttp\Client;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Serializer\Serializer;

class CurrencyApiClient extends AbstractClient
{
    private const API_URL = 'https://api.currencyapi.com/v3';

    public function __construct(protected SystemConfigService $configService, protected Serializer $serializer, Client $client)
    {
        parent::__construct($configService, $serializer, $client);
    }

    public function run(): void
    {}

    public function getLatestRates()
    {
        $url = sprintf('%s/%s?base_currency=%s&currencies=%s', self::API_URL, 'latest', 'PLN', 'EUR,USD');
        $response = $this->makeRequest('GET', $url);
        $data = $response->getBody()->getContents();
        $body = json_decode($data, true);

        dd($body);
    }
}
