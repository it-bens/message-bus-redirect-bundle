<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Bundle;

use ITB\MessageBusRedirectBundle\MessageBusProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use Tests\ITB\MessageBusRedirectBundle\ITBMessageBusRedirectBundleKernel;

final class MessengerBusProviderInitializationTest extends TestCase
{
    /**
     * @return string[][]
     */
    public function providePathToValidConfiguration(): array
    {
        return [[__DIR__ . '/../Fixtures/Configuration/config_with_message_class_strategy_valid.yaml']];
    }

    /**
     * @dataProvider providePathToValidConfiguration
     *
     * @param string $configurationFilePath
     * @return void
     */
    public function testServiceAlias(string $configurationFilePath): void
    {
        $config = Yaml::parseFile($configurationFilePath);
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $kernel->boot();

        $messageBusProvider = $kernel->getContainer()->get(MessageBusProvider::class);
        self::assertInstanceOf(MessageBusProvider::class, $messageBusProvider);
    }

    /**
     * @dataProvider providePathToValidConfiguration
     *
     * @param string $configurationFilePath
     * @return void
     */
    public function testServiceWiring(string $configurationFilePath): void
    {
        $config = Yaml::parseFile($configurationFilePath);
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $kernel->boot();

        $messageBusProvider = $kernel->getContainer()->get('itb_message_bus_redirect.message_bus_provider');
        self::assertInstanceOf(MessageBusProvider::class, $messageBusProvider);
    }
}
