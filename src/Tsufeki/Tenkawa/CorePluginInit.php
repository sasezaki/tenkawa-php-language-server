<?php declare(strict_types=1);

namespace Tsufeki\Tenkawa;

use Psr\Log\LoggerInterface;
use Tsufeki\BlancheJsonRpc\Dispatcher\MethodProvider;
use Tsufeki\BlancheJsonRpc\Dispatcher\MethodRegistry;
use Tsufeki\BlancheJsonRpc\Dispatcher\SimpleMethodRegistry;
use Tsufeki\Tenkawa\Event\OnStart;
use Tsufeki\Tenkawa\Logger\ClientLogger;
use Tsufeki\Tenkawa\Logger\CompositeLogger;

class CorePluginInit implements OnStart
{
    /**
     * @var MethodRegistry
     */
    private $methodRegistry;

    /**
     * @var MethodProvider[]
     */
    private $methodProviders;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ClientLogger
     */
    private $clientLogger;

    /**
     * @param MethodProvider[] $methodProviders
     */
    public function __construct(
        MethodRegistry $methodRegistry,
        array $methodProviders,
        LoggerInterface $logger,
        ClientLogger $clientLogger
    ) {
        $this->methodRegistry = $methodRegistry;
        $this->methodProviders = $methodProviders;
        $this->logger = $logger;
        $this->clientLogger = $clientLogger;
    }

    public function onStart(): \Generator
    {
        if ($this->methodRegistry instanceof SimpleMethodRegistry) {
            foreach ($this->methodProviders as $provider) {
                $this->methodRegistry->addProvider($provider);
            }
        }

        if ($this->logger instanceof CompositeLogger) {
            $this->logger->add($this->clientLogger);
        }

        return;
        yield;
    }
}
