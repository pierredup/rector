<?php declare(strict_types=1);

namespace Rector\Rector\Contrib\Nette\DI;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\NodeAnalyzer\MethodCallAnalyzer;
use Rector\NodeChanger\IdentifierRenamer;
use Rector\Rector\AbstractRector;

/**
 * From:
 * - $compiler->generateCode($className);
 *
 * To:
 * - $compiler->setClassName($className);
 * - $compiler->generateCode();
 */
final class CompilerGenerateCodeArgumentsRector extends AbstractRector
{
    /**
     * @var MethodCallAnalyzer
     */
    private $methodCallAnalyzer;

    /**
     * @var IdentifierRenamer
     */
    private $identifierRenamer;

    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    public function __construct(
        MethodCallAnalyzer $methodCallAnalyzer,
        IdentifierRenamer $identifierRenamer,
        BuilderFactory $builderFactory
    ) {
        $this->methodCallAnalyzer = $methodCallAnalyzer;
        $this->identifierRenamer = $identifierRenamer;
        $this->builderFactory = $builderFactory;
    }

    public function isCandidate(Node $node): bool
    {
        if (! $this->methodCallAnalyzer->isTypeAndMethod($node, 'Nette\DI\Compiler', 'generateCode')) {
            return false;
        }

        /** @var MethodCall $methodCallNode */
        $methodCallNode = $node;

        return count($methodCallNode->args) >= 1;
    }

    /**
     * @param MethodCall $methodCallNode
     */
    public function refactor(Node $methodCallNode): ?Node
    {
        $this->identifierRenamer->renameNode($methodCallNode, 'setClassName');

        $generateCode = $this->builderFactory->methodCall($methodCallNode->var, 'generateCode');
        $this->addNodeAfterNode($generateCode, $methodCallNode);

        return $methodCallNode;
    }
}
