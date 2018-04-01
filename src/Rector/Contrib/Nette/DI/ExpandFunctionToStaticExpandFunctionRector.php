<?php declare(strict_types=1);

namespace Rector\Rector\Contrib\Nette\DI;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Node\Attribute;
use Rector\NodeAnalyzer\MethodArgumentAnalyzer;
use Rector\NodeAnalyzer\MethodCallAnalyzer;
use Rector\Rector\AbstractRector;

/**
 * Covers https://github.com/Kdyby/Doctrine/pull/298/files
 * From:
 * - $builder->expand(object|array);
 *
 * To:
 * - \Nette\DI\Helpers::expand(object|array, $builder->parameters);
 */
final class ExpandFunctionToStaticExpandFunctionRector extends AbstractRector
{
    /**
     * @var MethodCallAnalyzer
     */
    private $methodCallAnalyzer;

    /**
     * @var MethodArgumentAnalyzer
     */
    private $methodArgumentAnalyzer;

    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    public function __construct(
        MethodCallAnalyzer $methodCallAnalyzer,
        MethodArgumentAnalyzer $methodArgumentAnalyzer,
        BuilderFactory $builderFactory
    ) {
        $this->methodCallAnalyzer = $methodCallAnalyzer;
        $this->methodArgumentAnalyzer = $methodArgumentAnalyzer;
        $this->builderFactory = $builderFactory;
    }

    public function isCandidate(Node $node): bool
    {
        $parentClassName = $node->getAttribute(Attribute::PARENT_CLASS_NAME);
        if ($parentClassName !== 'Nette\DI\CompilerExtension') {
            return false;
        }

        /** @var MethodCall $methodCallNode */
        $methodCallNode = $node;

        if (! $this->methodCallAnalyzer->isMethod($methodCallNode, 'expand')
            || $this->methodArgumentAnalyzer->isMethodFirstArgumentString($methodCallNode)) {
            return false;
        }

        return true;
    }

    /**
     * @param MethodCall $methodCallNode
     *
     * Returns \Nette\DI\Helpers::expand('argument', $builder->parameters)
     */
    public function refactor(Node $methodCallNode): ?Node
    {
        $arguments = [
            $methodCallNode->args[0],
            new PropertyFetch($methodCallNode->var, 'parameters'),
        ];

        return $this->builderFactory->StaticCall('Nette\DI\Helpers', 'expand', $arguments);
    }
}
