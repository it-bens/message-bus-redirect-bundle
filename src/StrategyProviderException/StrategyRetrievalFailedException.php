<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\StrategyProviderException;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

final class StrategyRetrievalFailedException extends RuntimeException
{
    /**
     * @param string $strategyClass
     * @param ContainerExceptionInterface $exception
     * @return StrategyRetrievalFailedException
     */
    public static function create(
        string $strategyClass,
        ContainerExceptionInterface $exception
    ): StrategyRetrievalFailedException {
        return new self(
            sprintf('The retrieval of the strategy class %s failed.', $strategyClass),
            previous: $exception
        );
    }
}
