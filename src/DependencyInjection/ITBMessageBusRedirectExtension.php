<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\DependencyInjection;

use Exception;
use ITB\MessageBusRedirectBundle\RedirectStrategyInterface;
use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerInterface;
use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerProvider;
use ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerStrategy;
use ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategy;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class ITBMessageBusRedirectExtension extends Extension
{
    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'itb_message_bus_redirect';
    }

    /**
     * @param array<string|int, mixed> $configs
     * @param ContainerBuilder $container
     * @return void
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->registerForAutoconfiguration(RedirectStrategyInterface::class)->addTag(
            'itb_message_bus_redirect.redirect_strategy'
        );
        $container->registerForAutoconfiguration(DecisionMakerInterface::class)->addTag(
            'itb_message_bus_redirect.decision_maker'
        );

        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $redirectStrategyProvider = $container->getDefinition('itb_message_bus_redirect.redirect_strategy_provider');
        // The 'redirect_Strategies' value should be validated in the configuration.
        $redirectStrategyProvider->replaceArgument(1, $config['redirect_strategies']);

        if (in_array(DecisionMakerStrategy::class, $config['redirect_strategies'])) {
            $decisionMakers = new TaggedIteratorArgument('itb_message_bus_redirect.decision_maker');
            $decisionMakerProvider = new Definition(DecisionMakerProvider::class, [$decisionMakers]);
            $container->addDefinitions(
                ['itb_message_bus_redirect.strategy.decision_maker_provider' => $decisionMakerProvider]
            );

            $decisionMakerStrategy = new Definition(
                DecisionMakerStrategy::class,
                [new Reference('itb_message_bus_redirect.strategy.decision_maker_provider')]
            );
            $decisionMakerStrategy->addTag('itb_message_bus_redirect.redirect_strategy');

            $container->addDefinitions(
                ['itb_message_bus_redirect.redirect_strategy.decision_maker_strategy' => $decisionMakerStrategy]
            );
        }

        if (in_array(MessageClassStrategy::class, $config['redirect_strategies'])) {
            $messageClasses = array_reduce(
                $config['message_classes'],
                static function ($messageClasses, $messageClass) {
                    $messageClasses[$messageClass['message_class']] = $messageClass['bus_name'];
                    return $messageClasses;
                },
                []
            );

            $messageClassStrategy = new Definition(MessageClassStrategy::class, [$messageClasses]);
            $messageClassStrategy->addTag('itb_message_bus_redirect.redirect_strategy');

            $container->addDefinitions(
                ['itb_message_bus_redirect.redirect_strategy.message_class_strategy' => $messageClassStrategy]
            );
        }
    }
}
