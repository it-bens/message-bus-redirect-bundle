<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException;

use RuntimeException;

final class MessageClassNotAStringException extends RuntimeException
{
    /**
     * @return MessageClassNotAStringException
     */
    public static function create(): MessageClassNotAStringException
    {
        return new self(
            'The provided message class is not a string. A fully qualified name of a class is required.'
        );
    }
}
