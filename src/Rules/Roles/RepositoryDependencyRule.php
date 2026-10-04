<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * A #[Repository] wraps the ORM and nothing else: every property must come
 * from the Doctrine namespace (entity manager, connection, ...). Anything
 * further, such as services, config or clocks, belongs in the caller.
 *
 * @implements Rule<ClassPropertyNode>
 */
final class RepositoryDependencyRule implements Rule
{
    private string $doctrineNamespace;

    public function __construct(string $doctrineNamespace)
    {
        $this->doctrineNamespace = trim($doctrineNamespace, '\\');
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
        if (!RoleAttribute::isOn($reflection, Repository::class) || !$reflection->hasNativeProperty($node->getName())) {
            return [];
        }

        $type = $reflection->getNativeProperty($node->getName())->getReadableType();
        if ($this->isAllowed($type)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Repository dependency %s::$%s must come from %s.',
                $reflection->getName(),
                $node->getName(),
                $this->doctrineNamespace,
            ))
                ->identifier('repository.dependencyType')
                ->build(),
        ];
    }

    private function isAllowed(Type $type): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $inner) {
                if (!$this->isAllowed($inner)) {
                    return false;
                }
            }

            return true;
        }

        if ($type->isNull()->yes()) {
            return true;
        }

        $classReflections = $type->getObjectClassReflections();
        if ([] === $classReflections) {
            return false;
        }

        foreach ($classReflections as $classReflection) {
            if (!str_starts_with($classReflection->getName(), $this->doctrineNamespace.'\\')) {
                return false;
            }
        }

        return true;
    }
}
