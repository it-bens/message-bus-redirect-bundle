<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Unit;

use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategy;
use ITB\MessageBusRedirectBundle\RedirectStrategyProvider;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyClassNotAClassNameException;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyClassNotAStringException;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyNotFoundException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class RedirectStrategyProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testMessageClassStrategyWithoutService(): void
    {
        $redirectStrategies = new ServiceLocator([]);
        $redirectStrategyClasses = [MessageClassStrategy::class];

        $this->expectException(StrategyNotFoundException::class);
        new RedirectStrategyProvider($redirectStrategies, $redirectStrategyClasses);
    }

    /**
     * @return void
     */
    public function testWithRedirectStrategyClassNotAClass(): void
    {
        $redirectStrategies = new ServiceLocator([]);
        $redirectStrategyClasses = ['SomeRandomClass'];

        $this->expectException(StrategyClassNotAClassNameException::class);
        new RedirectStrategyProvider($redirectStrategies, $redirectStrategyClasses);
    }

    /**
     * @return void
     */
    public function testWithRedirectStrategyClassNotAString(): void
    {
        $redirectStrategies = new ServiceLocator([]);
        $redirectStrategyClasses = [5];

        $this->expectException(StrategyClassNotAStringException::class);
        /** @phpstan-ignore-next-line */
        new RedirectStrategyProvider($redirectStrategies, $redirectStrategyClasses);
    }
}
