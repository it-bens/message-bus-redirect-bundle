<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle;

use ITB\MessageBusRedirectBundle\MessageBusProviderException\MessageBusDoesNotImplementInterface;
use ITB\MessageBusRedirectBundle\MessageBusProviderException\MessageBusNotFoundException;
use ITB\MessageBusRedirectBundle\MessageBusProviderException\MessageBusRetrievalFailedException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageBusProvider
{
    /**
     * @param ServiceLocator $busLocator
     */
    public function __construct(private ServiceLocator $busLocator)
    {
    }

    /**
     * @param string $id
     * @return MessageBusInterface
     */
    public function get(string $id): MessageBusInterface
    {
        try {
            $messageBus = $this->busLocator->get($id);
        } catch (NotFoundExceptionInterface $exception) {
            throw MessageBusNotFoundException::create($id, $exception);
        } catch (ContainerExceptionInterface $exception) {
            throw MessageBusRetrievalFailedException::create($id, $exception);
        }

        if (!$messageBus instanceof MessageBusInterface) {
            throw MessageBusDoesNotImplementInterface::create($id);
        }

        return $messageBus;
    }
}
