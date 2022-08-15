<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle;

use Exception;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tests\ITB\MessageBusRedirectBundle\Mock\TestDecisionMaker;

final class ITBMessageBusRedirectBundleKernelWithDecisionMaker extends ITBMessageBusRedirectBundleKernel
{
    /**
     * @param LoaderInterface $loader
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        parent::registerContainerConfiguration($loader);

        $loader->load(function (ContainerBuilder $container) {
            $decisionMaker = new Definition(TestDecisionMaker::class);
            $decisionMaker->addTag('itb_message_bus_redirect.decision_maker');
            $container->addDefinitions([TestDecisionMaker::class => $decisionMaker]);
        });
    }
}
