<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Unit;

use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategy;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\BusNameNotAStringException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\MessageClassNotAClassNameException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\MessageClassNotAStringException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\NoMessageClassesConfiguredException;
use PHPUnit\Framework\TestCase;
use Tests\ITB\MessageBusRedirectBundle\Mock\TestMessage;

final class MessageClassStrategyTest extends TestCase
{
    /**
     * @return void
     */
    public function testWithBusNameNotAString(): void
    {
        $messageClassBusMap = [TestMessage::class => 1337];

        $this->expectException(BusNameNotAStringException::class);
        new MessageClassStrategy($messageClassBusMap);
    }

    /**
     * @return void
     */
    public function testWithEmptyBusMap(): void
    {
        $this->expectException(NoMessageClassesConfiguredException::class);
        new MessageClassStrategy([]);
    }

    /**
     * @return void
     */
    public function testWithInvalidMessageClass(): void
    {
        $messageClassBusMap = ['not a class name' => 'alternativeBus'];

        $this->expectException(MessageClassNotAClassNameException::class);
        new MessageClassStrategy($messageClassBusMap);
    }

    /**
     * @return void
     */
    public function testWithMessageClassNotAString(): void
    {
        $messageClassBusMap = [5 => 'alternativeBus'];

        $this->expectException(MessageClassNotAStringException::class);
        new MessageClassStrategy($messageClassBusMap);
    }
}
