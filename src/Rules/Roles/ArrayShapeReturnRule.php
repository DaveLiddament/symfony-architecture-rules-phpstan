<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\Serializer;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * Public methods must not return array shapes (array{...}), directly or
 * nested inside lists: a shape crossing a class boundary is a struct
 * wanting to be a real object. A #[Serializer] is exempt, since producing
 * wire-format arrays is its job. Private helpers may use shapes freely.
 *
 * @implements Rule<InClassMethodNode>
 */
final class ArrayShapeReturnRule implements Rule
{
    /** @var list<string> */
    private array $ignoredNamespaces;

    /**
     * @param list<string> $ignoredNamespaces
     */
    public function __construct(array $ignoredNamespaces)
    {
        $this->ignoredNamespaces = array_map(static fn (string $namespace): string => trim($namespace, '\\'), $ignoredNamespaces);
    }

    #[\Override]
    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if (
            null === $classReflection
            || $this->isIgnored($classReflection)
            || RoleAttribute::isOn($classReflection, Serializer::class)
        ) {
            return [];
        }

        $method = $node->getOriginalNode();
        $name = $method->name->toString();
        if (!$method->isPublic() || str_starts_with($name, '__')) {
            return [];
        }

        $returnType = $node->getMethodReflection()->getOnlyVariant()->getReturnType();
        if (!$this->containsArrayShape($returnType)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Public method %s::%s() returns an array shape — make it a value object, DTO or view model, or move it into a #[Serializer].',
                $classReflection->getName(),
                $name,
            ))
                ->identifier('arrayShape.onlyInSerializer')
                ->build(),
        ];
    }

    private function containsArrayShape(Type $type): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $inner) {
                if ($this->containsArrayShape($inner)) {
                    return true;
                }
            }

            return false;
        }

        if ($type->isConstantArray()->yes()) {
            // The empty array literal (e.g. an inferred `array{}`) is not a
            // declared shape.
            return [] !== $type->getConstantArrays()
                && [] !== $type->getConstantArrays()[0]->getKeyTypes();
        }

        if ($type->isArray()->yes() || ([] === $type->getObjectClassReflections() && $type->isIterable()->yes())) {
            return $this->containsArrayShape($type->getIterableValueType());
        }

        return false;
    }

    private function isIgnored(ClassReflection $classReflection): bool
    {
        foreach ($this->ignoredNamespaces as $ignoredNamespace) {
            if (str_starts_with($classReflection->getName(), $ignoredNamespace.'\\')) {
                return true;
            }
        }

        return false;
    }
}
