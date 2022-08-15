<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerStrategyException;

use RuntimeException;

final class NoDecisionMakersConfiguredException extends RuntimeException
{
    /**
     * @return NoDecisionMakersConfiguredException
     */
    public static function create(): NoDecisionMakersConfiguredException
    {
        return new self(
            'No decision makers are configured. The decision maker strategy will not work without any decision makers.'
        );
    }
}
