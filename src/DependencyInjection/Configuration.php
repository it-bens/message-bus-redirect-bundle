<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\DependencyInjection;

use ITB\MessageBusRedirectBundle\RedirectStrategyInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('itb_message_bus_redirect');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        /** @phpstan-ignore-next-line */
        $rootNode
            ->children()
                ->arrayNode('redirect_strategies')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->scalarPrototype()
                        ->cannotBeEmpty()
                        ->validate()
                            ->ifTrue(static function ($redirectStrategy) {
                                return false === is_a($redirectStrategy, RedirectStrategyInterface::class, true);
                            })
                            ->thenInvalid('%s does not implement the RedirectStrategyInterface and is therefore no valid redirect strategy.')
                        ->end()
                    ->end()
                    ->info('The redirect strategy classes in the order they should be used.')
                ->end()
                ->arrayNode('message_classes')
                    ->requiresAtLeastOneElement()
                    ->arrayPrototype()
                        ->ignoreExtraKeys()
                        ->children()
                            ->scalarNode('message_class')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifTrue(static function ($messageClass) {
                                        return false === class_exists($messageClass);
                                    })
                                    ->thenInvalid('%s is not a valid class name.')
                                ->end()
                            ->end()
                            ->scalarNode('bus_name')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                    ->info('The message class to bus name assignments used by the MessageClassStrategy.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
