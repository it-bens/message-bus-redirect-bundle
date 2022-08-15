<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RedirectStrategyCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('itb_message_bus_redirect.redirect_strategy_provider');

        $redirectStrategies = [];
        foreach ($container->findTaggedServiceIds('itb_message_bus_redirect.redirect_strategy') as $id => $tags) {
            // The redirect strategies are indexed by their class name.
            $redirectStrategies[$container->getDefinition($id)->getClass()] = new Reference($id);
        }

        $definition->setArgument(0, ServiceLocatorTagPass::register($container, $redirectStrategies));
    }
}
