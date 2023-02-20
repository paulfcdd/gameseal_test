<?php

declare(strict_types=1);

namespace GamesealPlugin\Command;

use GamesealPlugin\Service\CurrencyApi\Client\AbstractClient;
use GamesealPlugin\Service\CurrencyApi\Client\CurrencyApiClient;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GetCurrenciesExchangeDataCommand extends Command
{
    public function __construct(private readonly SystemConfigService $configService, private readonly ContainerInterface $container)
    {
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('gameseal:currency:update');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiProvider = $this->configService->get('GamesealPlugin.config.apiProvider');
        /** @var CurrencyApiClient $service */
        $service = $this->container->get($apiProvider);
        dump($service->getLatestRates());
        $output->writeln('It\'s alive!');
        return 0;
    }
}
