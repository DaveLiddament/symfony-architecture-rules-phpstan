<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Roles\RepositoryMethodKind;
use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Code outside a repository's domain (another domain or the Nursery) may
 * only read through it: find*, get*, has* and is*. Writes go through a
 * service the owning domain exports. A method outside the repository
 * vocabulary counts as a write.
 *
 * @implements Rule<MethodCall>
 */
final class CrossDomainRepositoryWriteRule implements Rule
{
    public function __construct(
        private BoundaryClassifier $classifier,
        private RoleResolver $roleResolver,
    ) {
    }

    #[\Override]
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Identifier) {
            return [];
        }

        $methodName = $node->name->toString();
        if (RepositoryMethodKind::isReadMethodName($methodName)) {
            return [];
        }

        $source = $this->classifier->classifyNamespace($scope->getNamespace());
        if (!$source->is(AreaType::Domain) && !$source->is(AreaType::Nursery)) {
            return [];
        }

        $errors = [];
        foreach ($scope->getType($node->var)->getObjectClassReflections() as $classReflection) {
            $target = $this->classifier->classifyClass($classReflection->getName());
            if (
                !$target->is(AreaType::Domain)
                || $target->domain === $source->domain
                || !$this->roleResolver->plays($classReflection, Role::Repository)
            ) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                'Repository method %s::%s() is not a read, so it cannot be called from outside domain "%s": '
                .'other domains may only call find*, get*, has* and is* methods.',
                $classReflection->getName(),
                $methodName,
                $target->domain,
            ))->identifier('architecture.repositoryWrite')->build();
        }

        return $errors;
    }
}
