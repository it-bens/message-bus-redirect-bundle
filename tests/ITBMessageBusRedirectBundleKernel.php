<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle;

use Exception;
use ITB\MessageBusRedirectBundle\ITBMessageBusRedirectBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Messenger\MessageBus;

class ITBMessageBusRedirectBundleKernel extends Kernel
{
    /**
     * @phpstan-ignore-next-line
     * @var array|null
     */
    private ?array $messageRedirectConfig;

    /**
     * @phpstan-ignore-next-line
     * @param string $environment
     * @param bool $debug
     * @param array|null $messageRedirectConfig
     */
    public function __construct(string $environment, bool $debug, ?array $messageRedirectConfig = null)
    {
        parent::__construct($environment, $debug);
        $this->messageRedirectConfig = $messageRedirectConfig;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return __DIR__ . '/../var/cache/' . spl_object_hash($this);
    }

    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [new ITBMessageBusRedirectBundle()];
    }

    /**
     * @param LoaderInterface $loader
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            if (null !== $this->messageRedirectConfig) {
                $container->loadFromExtension('itb_message_bus_redirect', $this->messageRedirectConfig);
            }

            // All services are made public to use them via container.
            $container->addCompilerPass(new PublicForTestsCompilerPass());

            $messageBus = new Definition(MessageBus::class);
            $messageBus->addTag('messenger.bus');
            $alternativeBus = new Definition(MessageBus::class);
            $alternativeBus->addTag('messenger.bus');

            $container->addDefinitions(['defaultBus' => $messageBus, 'alternativeBus' => $alternativeBus]);
        });
    }
}
