<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Functional;

use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Yaml\Yaml;
use Tests\ITB\MessageBusRedirectBundle\ITBMessageBusRedirectBundleKernel;
use Tests\ITB\MessageBusRedirectBundle\Mock\TestMessage;
use Tests\ITB\MessageBusRedirectBundle\Mock\TestMessage2;

final class MessageClassStrategyTest extends TestCase
{
    /**
     * @return void
     */
    public function testNoMessageClassesConfigured(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Fixtures/Configuration/config_with_message_class_strategy_without_entries.yaml');
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $this->expectException(InvalidConfigurationException::class);
        $kernel->boot();
    }

    /**
     * @return void
     */
    public function testNoRedirectForMessageConfigured(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Fixtures/Configuration/config_with_message_class_strategy_valid.yaml');
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $kernel->boot();

        /** @var MessageClassStrategy $messageClassStrategy */
        $messageClassStrategy = $kernel->getContainer()->get('itb_message_bus_redirect.redirect_strategy.message_class_strategy');

        $envelope = new Envelope(new TestMessage2());
        $busName = $messageClassStrategy->getBusName($envelope);
        $this->assertNull($busName);
    }

    /**
     * @return void
     */
    public function testRedirect(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Fixtures/Configuration/config_with_message_class_strategy_valid.yaml');
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $kernel->boot();

        /** @var MessageClassStrategy $messageClassStrategy */
        $messageClassStrategy = $kernel->getContainer()->get('itb_message_bus_redirect.redirect_strategy.message_class_strategy');

        $envelope = new Envelope(new TestMessage());
        $busName = $messageClassStrategy->getBusName($envelope);
        $this->assertEquals('alternativeBus', $busName);
    }
}
