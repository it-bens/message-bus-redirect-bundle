<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies;

use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerProviderException\DecisionMakerDoesNotImplementInterface;

final class DecisionMakerProvider
{
    /** @var DecisionMakerInterface[] $decisionMakers */
    private array $decisionMakers = [];

    /**
     * @param iterable<int, mixed> $decisionMakers
     */
    public function __construct(iterable $decisionMakers)
    {
        foreach ($decisionMakers as $decisionMaker) {
            if (!$decisionMaker instanceof DecisionMakerInterface) {
                throw DecisionMakerDoesNotImplementInterface::create(get_debug_type($decisionMaker));
            }

            $this->decisionMakers[] = $decisionMaker;
        }
    }

    /**
     * @return DecisionMakerInterface[]
     */
    public function getDecisionMakers(): array
    {
        return $this->decisionMakers;
    }
}
