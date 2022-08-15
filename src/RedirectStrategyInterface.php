<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle;

use Symfony\Component\Messenger\Envelope;

interface RedirectStrategyInterface
{
    /**
     * Returns either the bus name to which the envelope should be redirected
     * or null if the strategy does not support the envelope.
     *
     * @param Envelope $envelope
     * @return string|null
     */
    public function getBusName(Envelope $envelope): ?string;
}
