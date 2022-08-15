<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\StrategyProviderException;

use RuntimeException;

final class StrategyClassNotAStringException extends RuntimeException
{
    /**
     * @return StrategyClassNotAStringException
     */
    public static function create(): StrategyClassNotAStringException
    {
        return new self(
            'The provided strategy class is not a string. A fully qualified name of a class, implementing the RedirectStrategyInterface is required.'
        );
    }
}
