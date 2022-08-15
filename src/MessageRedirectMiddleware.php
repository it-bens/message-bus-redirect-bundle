<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class MessageRedirectMiddleware implements MiddlewareInterface
{
    /**
     * @param RedirectStrategyProvider $redirectStrategyProvider
     * @param MessageBusProvider $messageBusProvider
     */
    public function __construct(
        private RedirectStrategyProvider $redirectStrategyProvider,
        private MessageBusProvider $messageBusProvider
    ) {
    }

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     * @return Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        // If this middleware already handled this envelope, it should be skipped.
        if (0 !== count($envelope->all(MessageRedirectedStamp::class))) {
            return $envelope;
        }

        foreach ($this->redirectStrategyProvider->getStrategies() as $redirectStrategy) {
            $busName = $redirectStrategy->getBusName($envelope);
            if (null !== $busName) {
                // The MessageRedirectedStamp is added to prevent infinite redirect.
                $stampedEnvelope = $envelope->with(new MessageRedirectedStamp());
                return $this->messageBusProvider->get($busName)->dispatch($stampedEnvelope);
            }
        }

        return $envelope;
    }
}
