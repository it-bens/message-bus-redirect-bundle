<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies;

use Symfony\Component\Messenger\Envelope;

interface DecisionMakerInterface
{
    /**
     * Returns either the bus name to which the envelope should be redirected
     * or null if the maker doesnt support this envelope.
     *
     * @param Envelope $envelope
     * @return string|null
     */
    public function getBusName(Envelope $envelope): ?string;
}
