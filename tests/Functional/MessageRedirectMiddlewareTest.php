<?php

declare(strict_types=1);

namespace Tests\ITB\MessageBusRedirectBundle\Functional;

use ITB\MessageBusRedirectBundle\MessageRedirectedStamp;
use ITB\MessageBusRedirectBundle\MessageRedirectMiddleware;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Test\Middleware\MiddlewareTestCase;
use Symfony\Component\Yaml\Yaml;
use Tests\ITB\MessageBusRedirectBundle\ITBMessageBusRedirectBundleKernel;
use Tests\ITB\MessageBusRedirectBundle\Mock\TestMessage;

final class MessageRedirectMiddlewareTest extends MiddlewareTestCase
{
    /**
     * @return string[][]
     */
    public function providePathToValidConfiguration(): array
    {
        return [[__DIR__ . '/../Fixtures/Configuration/config_with_message_class_strategy_valid.yaml']];
    }

    /**
     * @dataProvider providePathToValidConfiguration
     *
     * @param string $configurationFilePath
     * @return void
     */
    public function testNoRedirectWithMessageRedirectedStamp(string $configurationFilePath): void
    {
        $config = Yaml::parseFile($configurationFilePath);
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $kernel->boot();

        /** @var MessageRedirectMiddleware $messageBusRedirectMiddleware */
        $messageBusRedirectMiddleware = $kernel->getContainer()->get(MessageRedirectMiddleware::class);

        $envelope = new Envelope(new TestMessage());
        $handledEnvelope = $messageBusRedirectMiddleware->handle(
            $envelope->with(new MessageRedirectedStamp()),
            $this->getStackMock(false)
        );

        self::assertCount(1, $handledEnvelope->all(MessageRedirectedStamp::class));
    }

    /**
     * @dataProvider providePathToValidConfiguration
     *
     * @param string $configurationFilePath
     * @return void
     */
    public function testRedirectedStampAdded(string $configurationFilePath): void
    {
        $config = Yaml::parseFile($configurationFilePath);
        $kernel = new ITBMessageBusRedirectBundleKernel('test', true, $config);
        $kernel->boot();

        /** @var MessageRedirectMiddleware $messageBusRedirectMiddleware */
        $messageBusRedirectMiddleware = $kernel->getContainer()->get(MessageRedirectMiddleware::class);

        $envelope = new Envelope(new TestMessage());
        $handledEnvelope = $messageBusRedirectMiddleware->handle($envelope, $this->getStackMock(false));

        self::assertCount(1, $handledEnvelope->all(MessageRedirectedStamp::class));
    }
}
