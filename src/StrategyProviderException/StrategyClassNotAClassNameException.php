<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\StrategyProviderException;

use RuntimeException;

final class StrategyClassNotAClassNameException extends RuntimeException
{
    /**
     * @param string $className
     * @return StrategyClassNotAClassNameException
     */
    public static function create(string $className): StrategyClassNotAClassNameException
    {
        return new self(
            sprintf(
                'The provided strategy class %s is not a valid class name. A fully qualified name of a class, implementing the RedirectStrategyInterface is required.',
                $className
            )
        );
    }
}
