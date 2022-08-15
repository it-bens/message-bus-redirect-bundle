<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\MessageBusProviderException;

use RuntimeException;

final class MessageBusDoesNotImplementInterface extends RuntimeException
{
    /**
     * @param string $busName
     * @return MessageBusDoesNotImplementInterface
     */
    public static function create(string $busName): MessageBusDoesNotImplementInterface
    {
        return new self(sprintf('The provided bus "%s" does not implement the MessageBusInterface.', $busName));
    }
}
