<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle;

use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyClassNotAClassNameException;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyClassNotAStringException;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyNotFoundException;
use ITB\MessageBusRedirectBundle\StrategyProviderException\StrategyRetrievalFailedException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class RedirectStrategyProvider
{
    /** @var RedirectStrategyInterface[] $redirectStrategies */
    private array $redirectStrategies = [];

    /**
     * @param ServiceLocator $redirectStrategies
     * @param string[] $redirectStrategyClasses
     */
    public function __construct(ServiceLocator $redirectStrategies, array $redirectStrategyClasses)
    {
        foreach ($redirectStrategyClasses as $redirectStrategyClass) {
            if (!is_string($redirectStrategyClass)) {
                throw StrategyClassNotAStringException::create();
            }
            if (!class_exists($redirectStrategyClass)) {
                throw StrategyClassNotAClassNameException::create($redirectStrategyClass);
            }

            try {
                $redirectStrategy = $redirectStrategies->get($redirectStrategyClass);
            } catch (NotFoundExceptionInterface $exception) {
                throw StrategyNotFoundException::create($redirectStrategyClass, $exception);
            } catch (ContainerExceptionInterface $exception) {
                throw StrategyRetrievalFailedException::create($redirectStrategyClass, $exception);
            }

            $this->redirectStrategies[] = $redirectStrategy;
        }
    }

    /**
     * @return RedirectStrategyInterface[]
     */
    public function getStrategies(): array
    {
        return $this->redirectStrategies;
    }
}
