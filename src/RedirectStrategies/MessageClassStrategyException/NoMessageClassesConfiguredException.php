<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException;

use RuntimeException;

final class NoMessageClassesConfiguredException extends RuntimeException
{
    /**
     * @return NoMessageClassesConfiguredException
     */
    public static function create(): NoMessageClassesConfiguredException
    {
        return new self(
            'No message classes are configured. The message class strategy will not work without any message classes.'
        );
    }
}
