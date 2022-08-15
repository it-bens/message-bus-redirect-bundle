<?php

declare(strict_types=1);

namespace ITB\MessageBusRedirectBundle;

use ITB\MessageBusRedirectBundle\DependencyInjection\Compiler\MessageBusCompilerPass;
use ITB\MessageBusRedirectBundle\DependencyInjection\Compiler\RedirectStrategyCompilerPass;
use ITB\MessageBusRedirectBundle\DependencyInjection\ITBMessageBusRedirectExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ITBMessageBusRedirectBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessageBusCompilerPass());
        $container->addCompilerPass(new RedirectStrategyCompilerPass());
    }

    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension(): ITBMessageBusRedirectExtension
    {
        if (null === $this->extension) {
            $this->extension = new ITBMessageBusRedirectExtension();
        }

        return $this->extension;
    }
}
