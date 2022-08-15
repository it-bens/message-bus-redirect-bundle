<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Unit;

use ITB\MessageBusRedirectBundle\MessageBusProvider;
use ITB\MessageBusRedirectBundle\MessageBusProviderException\MessageBusDoesNotImplementInterface;
use ITB\MessageBusRedirectBundle\MessageBusProviderException\MessageBusNotFoundException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerProvider;
use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerProviderException\DecisionMakerDoesNotImplementInterface;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategy;
use ITB\MessageBusRedirectBundle\RedirectStrategyProvider;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyClassNotAClassNameException;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyClassNotAStringException;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyNotFoundException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class DecisionMakerProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testWithInvalidDecisionMaker(): void
    {
        $decisionMakers = [new \stdClass()];
        $this->expectException(DecisionMakerDoesNotImplementInterface::class);
        new DecisionMakerProvider($decisionMakers);
    }
}
