<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\MessageBusProviderException;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

final class MessageBusNotFoundException extends RuntimeException
{
    /**
     * @param string $busName
     * @param NotFoundExceptionInterface $exception
     * @return MessageBusNotFoundException
     */
    public static function create(string $busName, NotFoundExceptionInterface $exception): MessageBusNotFoundException
    {
        return new self(
            sprintf('The provided bus name "%s" is unknown.', $busName),
            previous: $exception
        );
    }
}
