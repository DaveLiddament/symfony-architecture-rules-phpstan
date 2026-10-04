<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\Area;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\BoundaryClassifier;
use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Checks every class name referenced in code against the architectural
 * boundaries: the area of the code doing the referencing (source) and the
 * area of the referenced class (target).
 *
 * @implements Rule<Name>
 */
abstract class AbstractBoundaryRule implements Rule
{
    public function __construct(
        private readonly BoundaryClassifier $classifier,
    ) {
    }

    #[\Override]
    final public function getNodeType(): string
    {
        return Name::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    final public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isSpecialClassName()) {
            return [];
        }

        $targetName = $node->toString();
        $error = $this->check(
            $this->classifier->classifyNamespace($scope->getNamespace()),
            $this->classifier->classifyClass($targetName),
            $targetName,
        );

        return null === $error ? [] : [$error];
    }

    abstract protected function check(Area $source, Area $target, string $targetName): ?IdentifierRuleError;

    final protected function error(string $message, string $identifier): IdentifierRuleError
    {
        return RuleErrorBuilder::message($message)->identifier($identifier)->build();
    }
}
