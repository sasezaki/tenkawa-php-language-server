<?php declare(strict_types=1);

namespace Tsufeki\Tenkawa;

use Recoil\Kernel;
use Tsufeki\BlancheJsonRpc\MappedJsonRpc;
use Tsufeki\BlancheJsonRpc\Transport\Transport;
use Tsufeki\HmContainer\Container;
use Tsufeki\Tenkawa\Event\EventDispatcher;
use Tsufeki\Tenkawa\Event\OnStart;
use Tsufeki\Tenkawa\Transport\RunnableTransport;
use Tsufeki\Tenkawa\Utils\SyncAsync;

class Tenkawa
{
    /**
     * @param Plugin[] $plugins
     */
    public function run(RunnableTransport $transport, Kernel $kernel, SyncAsync $syncAsync, array $plugins): \Generator
    {
        $container = new Container();
        $container->setValue(Transport::class, $transport);
        $container->setValue(Kernel::class, $kernel);
        $container->setValue(SyncAsync::class, $syncAsync);

        foreach ($plugins as $plugin) {
            $plugin->configureContainer($container);
        }

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container->get(EventDispatcher::class);
        yield $eventDispatcher->dispatchAndWait(OnStart::class);

        // Materialize the RPC server
        $rpc = $container->get(MappedJsonRpc::class);

        yield $transport->run();
    }
}
