<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * A class playing the role must be declared final, and for some roles also
 * readonly.
 *
 * @implements Rule<InClassNode>
 */
abstract class AbstractDeclarationRule implements Rule
{
    final public function __construct(
        private RoleResolver $roleResolver,
    ) {
    }

    abstract protected function getRole(): Role;

    /**
     * The role's name as it appears in error messages, e.g. "Value object".
     */
    abstract protected function getRoleName(): string;

    abstract protected function mustBeReadonly(): bool;

    abstract protected function getIdentifier(): string;

    /**
     * Whether a @final PHPDoc tag is enough, for roles that libraries such as
     * ORMs need to extend at runtime (e.g. lazy-loading proxies).
     */
    protected function acceptsPhpDocFinal(): bool
    {
        return false;
    }

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
        if ($reflection->isAnonymous() || !$this->roleResolver->plays($reflection, $this->getRole())) {
            return [];
        }

        $isFinal = $this->acceptsPhpDocFinal() ? $reflection->isFinal() : $reflection->isFinalByKeyword();
        if ($isFinal && (!$this->mustBeReadonly() || $reflection->getNativeReflection()->isReadOnly())) {
            return [];
        }

        $requirement = match (true) {
            $this->mustBeReadonly() => 'final and readonly',
            $this->acceptsPhpDocFinal() => 'final (or @final)',
            default => 'final',
        };

        return [
            RuleErrorBuilder::message(sprintf(
                '%s %s must be %s.',
                $this->getRoleName(),
                $reflection->getName(),
                $requirement,
            ))
                ->identifier($this->getIdentifier())
                ->build(),
        ];
    }
}
