<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\MessageBusProviderException;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

final class MessageBusRetrievalFailedException extends RuntimeException
{
    /**
     * @param string $busName
     * @param ContainerExceptionInterface $exception
     * @return MessageBusRetrievalFailedException
     */
    public static function create(
        string $busName,
        ContainerExceptionInterface $exception
    ): MessageBusRetrievalFailedException {
        return new self(
            sprintf('The retrieval of the message bus %s failed.', $busName),
            previous: $exception
        );
    }
}
