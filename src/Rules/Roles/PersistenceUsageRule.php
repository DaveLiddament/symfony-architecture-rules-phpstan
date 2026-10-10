<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\PersistenceClasses;
use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * Only a #[Repository] may hold a persistence class (an entity manager, a
 * database connection, ...). Everything else talks to storage through a
 * repository. Checked on every class property in the codebase.
 *
 * @implements Rule<ClassPropertyNode>
 */
final class PersistenceUsageRule implements Rule
{
    public function __construct(
        private RoleResolver $roleResolver,
        private PersistenceClasses $persistenceClasses,
    ) {
    }

    #[\Override]
    public function getNodeType(): string
    {
        return ClassPropertyNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $node->getClassReflection();
        if ($this->roleResolver->plays($reflection, Role::Repository) || !$reflection->hasNativeProperty($node->getName())) {
            return [];
        }

        $type = $reflection->getNativeProperty($node->getName())->getReadableType();
        $persistenceClass = $this->persistenceClassIn($type);
        if (null === $persistenceClass) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Only a #[Repository] may hold a persistence class, but %s::$%s holds %s.',
                $reflection->getName(),
                $node->getName(),
                $persistenceClass,
            ))
                ->identifier('persistence.onlyInRepository')
                ->build(),
        ];
    }

    private function persistenceClassIn(Type $type): ?string
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $inner) {
                $persistenceClass = $this->persistenceClassIn($inner);
                if (null !== $persistenceClass) {
                    return $persistenceClass;
                }
            }

            return null;
        }

        if ($type->isArray()->yes()) {
            return $this->persistenceClassIn($type->getIterableValueType());
        }

        // An object is judged by its class, never by what it iterates over
        // (see ConfigProviderUsageRule).
        $classReflections = $type->getObjectClassReflections();
        if ([] !== $classReflections) {
            foreach ($classReflections as $classReflection) {
                $persistenceClass = $this->persistenceClasses->matching($classReflection);
                if (null !== $persistenceClass) {
                    return $persistenceClass;
                }
            }

            return null;
        }

        if ($type->isIterable()->yes()) {
            return $this->persistenceClassIn($type->getIterableValueType());
        }

        return null;
    }
}
