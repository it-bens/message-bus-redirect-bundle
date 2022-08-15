<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies;

use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerStrategyException\NoDecisionMakersConfiguredException;
use ITB\MessageBusRedirectBundle\RedirectStrategyInterface;
use Symfony\Component\Messenger\Envelope;

final class DecisionMakerStrategy implements RedirectStrategyInterface
{
    /**
     * @param DecisionMakerProvider $decisionMakerProvider
     */
    public function __construct(private DecisionMakerProvider $decisionMakerProvider)
    {
        if (0 === count($this->decisionMakerProvider->getDecisionMakers())) {
            throw NoDecisionMakersConfiguredException::create();
        }
    }

    /**
     * @param Envelope $envelope
     * @return string|null
     */
    public function getBusName(Envelope $envelope): ?string
    {
        foreach ($this->decisionMakerProvider->getDecisionMakers() as $decisionMaker) {
            $busName = $decisionMaker->getBusName($envelope);
            if (null !== $busName) {
                return $busName;
            }
        }

        return null;
    }
}
