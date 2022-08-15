<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Mock;

use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerInterface;
use Symfony\Component\Messenger\Envelope;

final class TestDecisionMaker implements DecisionMakerInterface
{
    /**
     * @param Envelope $envelope
     * @return string|null
     */
    public function getBusName(Envelope $envelope): ?string
    {
        return 'alternativeBus';
    }
}
