<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Unit;

use ITB\MessageBusRedirectBundle\MessageBusProvider;
use ITB\MessageBusRedirectBundle\MessageBusProviderException\MessageBusDoesNotImplementInterface;
use ITB\MessageBusRedirectBundle\MessageBusProviderException\MessageBusNotFoundException;
use ITB\MessageBusRedirectBundle\RedirectStrategyProvider;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyClassNotAStringException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class MessageBusProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testWithInvalidBus(): void
    {
        $buses = new ServiceLocator([
            'testBus' => static function () {
                return new stdClass();
            }
        ]);
        $messageBusProvider = new MessageBusProvider($buses);

        $this->expectException(MessageBusDoesNotImplementInterface::class);
        $messageBusProvider->get('testBus');
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

    /**
     * @return void
     */
    public function testWithoutBus(): void
    {
        $buses = new ServiceLocator([]);
        $messageBusProvider = new MessageBusProvider($buses);

        $this->expectException(MessageBusNotFoundException::class);
        $messageBusProvider->get('testBus');
    }
}
