<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\PersistenceClasses;
use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * A #[Repository] wraps storage and nothing else. Every property is a
 * persistence class (entity manager, connection, ...), another repository,
 * or a list of entities or value objects, which covers in-memory and
 * generated-data repositories. Anything further, such as services, config
 * or clocks, belongs in the caller.
 *
 * @implements Rule<ClassPropertyNode>
 */
final class RepositoryDependencyRule implements Rule
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
        if (!$this->roleResolver->plays($reflection, Role::Repository) || !$reflection->hasNativeProperty($node->getName())) {
            return [];
        }

        $type = $reflection->getNativeProperty($node->getName())->getReadableType();
        if ($this->isAllowed($type)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Repository dependency %s::$%s must be a persistence class, another repository, or a list of entities or value objects.',
                $reflection->getName(),
                $node->getName(),
            ))
                ->identifier('repository.dependencyType')
                ->build(),
        ];
    }

    private function isAllowed(Type $type): bool
    {
        if ($this->isNullOrUnionOf($type, $this->isAllowed(...))) {
            return true;
        }

        if ($type->isList()->yes()) {
            return $this->isEntityOrValueObject($type->getIterableValueType());
        }

        return $this->allClassesMatch(
            $type,
            fn (ClassReflection $classReflection): bool => null !== $this->persistenceClasses->matching($classReflection)
                || $this->roleResolver->plays($classReflection, Role::Repository),
        );
    }

    private function isEntityOrValueObject(Type $type): bool
    {
        if ($this->isNullOrUnionOf($type, $this->isEntityOrValueObject(...))) {
            return true;
        }

        return $this->allClassesMatch(
            $type,
            fn (ClassReflection $classReflection): bool => $this->roleResolver->plays($classReflection, Role::Entity)
                || $this->roleResolver->plays($classReflection, Role::ValueObject),
        );
    }

    /**
     * @param \Closure(Type): bool $isAllowed
     */
    private function isNullOrUnionOf(Type $type, \Closure $isAllowed): bool
    {
        if ($type->isNull()->yes()) {
            return true;
        }

        if (!$type instanceof UnionType) {
            return false;
        }

        foreach ($type->getTypes() as $inner) {
            if (!$isAllowed($inner)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Closure(ClassReflection): bool $matches
     */
    private function allClassesMatch(Type $type, \Closure $matches): bool
    {
        $classReflections = $type->getObjectClassReflections();
        if ([] === $classReflections) {
            return false;
        }

        foreach ($classReflections as $classReflection) {
            if (!$matches($classReflection)) {
                return false;
            }
        }

        return true;
    }
}
