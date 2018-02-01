<?php declare(strict_types=1);

namespace Tsufeki\Tenkawa\Php\Language;

use PhpParser\Comment;
use PhpParser\Node;
use Tsufeki\Tenkawa\Php\Reflection\Element\Element;
use Tsufeki\Tenkawa\Server\Document\Document;
use Tsufeki\Tenkawa\Server\Language\GoToDefinitionProvider;
use Tsufeki\Tenkawa\Server\Protocol\Common\Position;

class GoToGlobalsProvider implements GoToDefinitionProvider
{
    /**
     * @var GlobalsHelper
     */
    private $globalsHelper;

    /**
     * @var NodeFinder
     */
    private $nodeFinder;

    public function __construct(GlobalsHelper $globalsHelper, NodeFinder $nodeFinder)
    {
        $this->globalsHelper = $globalsHelper;
        $this->nodeFinder = $nodeFinder;
    }

    public function getLocations(Document $document, Position $position): \Generator
    {
        if ($document->getLanguage() !== 'php') {
            return [];
        }

        /** @var (Node|Comment)[] $nodes */
        $nodes = yield $this->nodeFinder->getNodePath($document, $position);
        /** @var Element[] $elements */
        $elements = yield $this->globalsHelper->getReflectionFromNodePath($nodes, $document);

        return array_values(array_filter(array_map(function (Element $element) {
            return $element->location;
        }, $elements)));
    }
}
