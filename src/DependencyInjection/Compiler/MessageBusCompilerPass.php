<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class MessageBusCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('itb_message_bus_redirect.message_bus_provider');

        $buses = [];
        foreach ($container->findTaggedServiceIds('messenger.bus') as $busName => $tags) {
            $buses[$busName] = new Reference($busName);
        }

        $definition->setArgument(0, ServiceLocatorTagPass::register($container, $buses));
    }
}
