<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Functional;

use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerStrategy;
use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerStrategyException\NoDecisionMakersConfiguredException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Yaml\Yaml;
use Tests\ITB\MessageBusRedirectBundle\ITBMessageBusRedirectBundleKernel;
use Tests\ITB\MessageBusRedirectBundle\ITBMessageBusRedirectBundleKernelWithDecisionMaker;
use Tests\ITB\MessageBusRedirectBundle\Mock\TestMessage;

final class DecisionMakerStrategyTest extends TestCase
{
    /**
     * @return void
     */
    public function test(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Fixtures/Configuration/config_with_decision_maker_strategy.yaml');
        $kernel = new ITBMessageBusRedirectBundleKernelWithDecisionMaker('test', true, $config);
        $kernel->boot();

        /** @var DecisionMakerStrategy $decisionMakerStrategy */
        $decisionMakerStrategy = $kernel->getContainer()->get('itb_message_bus_redirect.redirect_strategy.decision_maker_strategy');

        $this->assertInstanceOf(DecisionMakerStrategy::class, $decisionMakerStrategy);
        $this->assertNotNull($decisionMakerStrategy->getBusName(new Envelope(new TestMessage())));
    }

    /**
     * @return void
     */
    public function testNoDecisionMakersConfiguredConfigured(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Fixtures/Configuration/config_with_decision_maker_strategy.yaml');
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $kernel->boot();

        $this->expectException(NoDecisionMakersConfiguredException::class);
        $kernel->getContainer()->get('itb_message_bus_redirect.redirect_strategy.decision_maker_strategy');
    }
}
