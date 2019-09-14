<?php declare(strict_types=1);

namespace Tsufeki\Tenkawa\Server;

use Tsufeki\Tenkawa\BeberleiAssert\BeberleiAssertPlugin;
use Tsufeki\Tenkawa\Doctrine\DoctrinePlugin;
use Tsufeki\Tenkawa\Mockery\MockeryPlugin;
use Tsufeki\Tenkawa\Phony\PhonyPlugin;
use Tsufeki\Tenkawa\Php\PhpPlugin;
use Tsufeki\Tenkawa\PhpUnit\PhpUnitPlugin;
use Tsufeki\Tenkawa\Prophecy\ProphecyPlugin;
use Tsufeki\Tenkawa\Symfony\SymfonyPlugin;
use Tsufeki\Tenkawa\WebMozartAssert\WebMozartAssertPlugin;

class PluginFinder
{
    /**
     * @return Plugin[]
     */
    public function findPlugins(): array
    {
        return [
            new ServerPlugin(),
            new PhpPlugin(),
 //           new SymfonyPlugin(),
    //        new DoctrinePlugin(),
            new PhpUnitPlugin(),
            new PhonyPlugin(),
            new ProphecyPlugin(),
   //         new MockeryPlugin(),
            new WebMozartAssertPlugin(),
            new BeberleiAssertPlugin(),
        ];
    }
}
