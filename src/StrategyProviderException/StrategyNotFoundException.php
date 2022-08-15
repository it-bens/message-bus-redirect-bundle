<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\StrategyProviderException;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

final class StrategyNotFoundException extends RuntimeException
{
    /**
     * @param string $strategyClass
     * @param NotFoundExceptionInterface $exception
     * @return StrategyNotFoundException
     */
    public static function create(
        string $strategyClass,
        NotFoundExceptionInterface $exception
    ): StrategyNotFoundException {
        return new self(
            sprintf('The provided strategy class %s is unknown. The class has to implement the RedirectStrategyInterface and must be registered as a service.', $strategyClass),
            previous: $exception
        );
    }
}
