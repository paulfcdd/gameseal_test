<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyApi\Client;

use Psr\Http\Message\ResponseInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

abstract class AbstractClient
{
    public ?string $apiKey;

    public function __construct(protected SystemConfigService $configService, protected Serializer $serializer, protected Client $client)
    {
        $this->apiKey = $this->configService->get('GamesealPlugin.config.apiKey');
    }

    abstract function run(): void;

    protected function makeRequest(string $method, string $url): ResponseInterface
    {
        return $this->client->send(new Request($method, $url, ['apiKey' => $this->apiKey]));
    }
}
