<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\Client;

use GamesealPlugin\Core\Content\CurrencyExchange\CurrencyExchangeSource;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\DtoInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


abstract class AbstractClient
{
    public ?string $apiKey;
    public Context $context;

    public function __construct(
        protected SystemConfigService $configService,
        protected SerializerInterface $serializer,
        protected Client $client,
        protected EntityRepository $entityRepository,
    )
    {
        $this->apiKey = $this->configService->get('GamesealPlugin.config.apiKey');
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    abstract public function run(): bool;
    abstract public function createContext(DtoInterface $dto): Context;

    /**
     * @throws GuzzleException
     */
    protected function makeRequest(string $method, string $url): ResponseInterface
    {
        return $this->client->send(new Request($method, $url, ['apiKey' => $this->apiKey]));
    }

    protected function writeData(array $data, Context $context): void
    {
        $currencyId = $this->findCurrencyIdByCode($context);

        if (!$currencyId) {
            $this->entityRepository->create([$data], $context);
        } else {
            $data['id'] = $currencyId;
            $this->entityRepository->update([$data], $context);
        }
    }

    private function findCurrencyIdByCode(Context $context): ?string
    {
        /** @var CurrencyExchangeSource $source */
        $source = $context->getSource();
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('code', $source->getCode()));

        return $this->entityRepository->searchIds($criteria, $context)->firstId();
    }
}
