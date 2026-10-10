<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\Architecture\Attribute\Repository;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * Only a #[Repository] may hold the entity manager (or anything
 * implementing it). Everything else talks to the database through a
 * repository. Checked on every class property in the codebase.
 *
 * @implements Rule<ClassPropertyNode>
 */
final class EntityManagerUsageRule implements Rule
{
    public function __construct(
        private string $entityManagerInterface,
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
        if (RoleAttribute::isOn($reflection, Repository::class) || !$reflection->hasNativeProperty($node->getName())) {
            return [];
        }

        $type = $reflection->getNativeProperty($node->getName())->getReadableType();
        if (!$this->mentionsEntityManager($type)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'The entity manager may only be held by a #[Repository], but %s::$%s holds it.',
                $reflection->getName(),
                $node->getName(),
            ))
                ->identifier('entityManager.onlyInRepository')
                ->build(),
        ];
    }

    private function mentionsEntityManager(Type $type): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $inner) {
                if ($this->mentionsEntityManager($inner)) {
                    return true;
                }
            }

            return false;
        }

        if ($type->isArray()->yes()) {
            return $this->mentionsEntityManager($type->getIterableValueType());
        }

        // An object is judged by its class, never by what it iterates over
        // (see ConfigProviderUsageRule).
        $classReflections = $type->getObjectClassReflections();
        if ([] !== $classReflections) {
            foreach ($classReflections as $classReflection) {
                if (
                    $this->entityManagerInterface === $classReflection->getName()
                    || $classReflection->implementsInterface($this->entityManagerInterface)
                ) {
                    return true;
                }
            }

            return false;
        }

        if ($type->isIterable()->yes()) {
            return $this->mentionsEntityManager($type->getIterableValueType());
        }

        return false;
    }
}
