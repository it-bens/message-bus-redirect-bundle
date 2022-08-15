<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerProviderException;

use RuntimeException;

final class DecisionMakerDoesNotImplementInterface extends RuntimeException
{
    /**
     * @param string $decisionMakerClass
     * @return DecisionMakerDoesNotImplementInterface
     */
    public static function create(string $decisionMakerClass): DecisionMakerDoesNotImplementInterface
    {
        return new self(
            sprintf(
                'The provided decision maker of class "%s" does not implement the DecisionMakerInterface.',
                $decisionMakerClass
            )
        );
    }
}
