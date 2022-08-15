<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException;

use RuntimeException;

final class MessageClassNotAClassNameException extends RuntimeException
{
    /**
     * @param string $messageClass
     * @return MessageClassNotAClassNameException
     */
    public static function create(string $messageClass): MessageClassNotAClassNameException
    {
        return new self(
            sprintf(
                'The provided message class %s is not a valid class name. A fully qualified name of a class is required.',
                $messageClass
            )
        );
    }
}
