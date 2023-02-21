<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\DTO;

class CurrencyApiClientDto implements DtoInterface
{
    public string $code;
    public string $value;
    public string $lastUpdatedAt;
}
