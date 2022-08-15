<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException;

use RuntimeException;

final class BusNameNotAStringException extends RuntimeException
{
    /**
     * @return BusNameNotAStringException
     */
    public static function create(): BusNameNotAStringException
    {
        return new self('The provided bus name is not a string.');
    }
}
