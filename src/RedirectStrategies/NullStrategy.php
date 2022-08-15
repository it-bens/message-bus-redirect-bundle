<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies;

use ITB\MessageBusRedirectBundle\RedirectStrategyInterface;
use Symfony\Component\Messenger\Envelope;

final class NullStrategy implements RedirectStrategyInterface
{
    /**
     * @param Envelope $envelope
     * @return string|null
     */
    public function getBusName(Envelope $envelope): ?string
    {
        return null;
    }
}
