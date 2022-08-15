<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\RedirectStrategies;

use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\BusNameNotAStringException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\MessageClassNotAClassNameException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\MessageClassNotAStringException;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategyException\NoMessageClassesConfiguredException;
use ITB\MessageBusRedirectBundle\RedirectStrategyInterface;
use Symfony\Component\Messenger\Envelope;

final class MessageClassStrategy implements RedirectStrategyInterface
{
    /** @var  array<class-string, string> $messageClassBusMap */
    private array $messageClassBusMap;

    /**
     * @param array<string|int, mixed> $messageClassBusMap
     */
    public function __construct(array $messageClassBusMap)
    {
        if (0 === count($messageClassBusMap)) {
            throw NoMessageClassesConfiguredException::create();
        }

        $busNames = array_values($messageClassBusMap);
        array_walk($busNames, static function (mixed $busName): void {
            if (!is_string($busName)) {
                throw BusNameNotAStringException::create();
            }
        });

        $messageClasses = array_keys($messageClassBusMap);
        array_walk($messageClasses, static function (mixed $messageClass): void {
            if (!is_string($messageClass)) {
                throw MessageClassNotAStringException::create();
            }
            if (!class_exists($messageClass)) {
                throw MessageClassNotAClassNameException::create($messageClass);
            }
        });

        /** @var array<class-string, string> $messageClassBusMap */
        $this->messageClassBusMap = $messageClassBusMap;
    }

    /**
     * @param Envelope $envelope
     * @return string|null
     */
    public function getBusName(Envelope $envelope): ?string
    {
        $messageClass = get_class($envelope->getMessage());

        return array_key_exists(
            $messageClass,
            $this->messageClassBusMap
        ) ? $this->messageClassBusMap[$messageClass] : null;
    }
}
