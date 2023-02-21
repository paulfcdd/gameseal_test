<?php

declare(strict_types=1);

namespace GamesealPlugin\Command;

use GamesealPlugin\Exception\CurrencyExchangeServiceException\ApiProviderNotDefinedException;
use GamesealPlugin\Service\CurrencyExchangeApiService\Client\AbstractClient;
use Shopware\Core\System\SystemConfig\Exception\ConfigurationNotFoundException;
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

        if (!$apiProvider) {
            $exception = new ApiProviderNotDefinedException('apiProvider value not found in plugin config');
            $output->writeln($exception->getMessage());

            return 1;
        }

        try {
            /** @var AbstractClient $service */
            $service = $this->container->get($apiProvider);
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return 1;
        }

        $service->run();
        $output->writeln('Data imported successfully');

        return 0;
    }
}
