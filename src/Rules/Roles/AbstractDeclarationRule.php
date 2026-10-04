<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * A class carrying a role attribute must be declared final, and for some
 * roles also readonly.
 *
 * @implements Rule<InClassNode>
 */
abstract class AbstractDeclarationRule implements Rule
{
    /**
     * @return class-string
     */
    abstract protected function getAttributeClass(): string;

    /**
     * The role's name as it appears in error messages, e.g. "Value object".
     */
    abstract protected function getRoleName(): string;

    abstract protected function mustBeReadonly(): bool;

    abstract protected function getIdentifier(): string;

    #[\Override]
    final public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    final public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $node->getClassReflection();
        if ($reflection->isAnonymous()) {
            return [];
        }

        $native = $reflection->getNativeReflection();
        if ([] === $native->getAttributes($this->getAttributeClass())) {
            return [];
        }

        if ($native->isFinal() && (!$this->mustBeReadonly() || $native->isReadOnly())) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                '%s %s must be %s.',
                $this->getRoleName(),
                $reflection->getName(),
                $this->mustBeReadonly() ? 'final and readonly' : 'final',
            ))
                ->identifier($this->getIdentifier())
                ->build(),
        ];
    }
}
