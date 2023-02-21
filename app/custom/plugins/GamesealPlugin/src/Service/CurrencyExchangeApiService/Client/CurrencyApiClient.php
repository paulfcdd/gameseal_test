<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\Client;

use GamesealPlugin\Core\Content\CurrencyExchange\CurrencyExchangeSource;
use GamesealPlugin\Repository\CurrencyExchange\CurrencyExchangeRepository;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\DtoInterface;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\CurrencyApiClientDto;
use GuzzleHttp\Client;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyApiClient extends AbstractClient
{
    private const API_URL = 'https://api.currencyapi.com/v3';

    public function __construct(
        SystemConfigService $configService,
        SerializerInterface $serializer,
        Client $client, protected
        CurrencyExchangeRepository $currencyExchangeRepository,
    )
    {
        parent::__construct($configService, $serializer, $client, $currencyExchangeRepository);
    }

    public function run(): bool
    {
        try {
            $url = sprintf('%s/%s?base_currency=%s&currencies=%s', self::API_URL, 'latest', 'PLN', 'EUR,USD');
            $response = $this->makeRequest('GET', $url);
            $data = $response->getBody()->getContents();
            $body = json_decode($data, true);

            foreach ($body['data'] as $currency) {
                $currencyApiDto = new CurrencyApiClientDto();
                $currencyApiDto->lastUpdatedAt = $body['meta']['last_updated_at'];
                $currencyApiDto->code = $currency['code'];
                $currencyApiDto->value = (string)$currency['value'];
                $dtoToArray = json_decode($this->serializer->serialize($currencyApiDto, 'json'), true);
                $this->writeData($dtoToArray, $this->createContext($currencyApiDto));
            }

            return true;
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function createContext(DtoInterface $dto): Context
    {
        $this->context = new Context(new CurrencyExchangeSource($dto->code, $dto->value, $dto->lastUpdatedAt));

        return $this->context;
    }
}
