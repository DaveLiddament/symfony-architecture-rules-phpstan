<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

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
 * Every property of a class carrying the role attribute must be a value:
 * a primitive (int, float, string, bool), an allowed class, or a list of
 * any of these. Nullable variants are allowed. By default the allowed
 * classes are \DateTimeImmutable (and subclasses), enums and other classes
 * carrying the same role attribute.
 *
 * @implements Rule<ClassPropertyNode>
 */
abstract class AbstractValuePropertyTypeRule implements Rule
{
    /**
     * @return class-string
     */
    abstract protected function getAttributeClass(): string;

    /**
     * The error message, with %s standing for the property, e.g. "Foo::$bar".
     */
    abstract protected function getMessage(): string;

    abstract protected function getIdentifier(): string;

    #[\Override]
    final public function getNodeType(): string
    {
        return ClassPropertyNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    final public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $node->getClassReflection();
        if (!RoleAttribute::isOn($reflection, $this->getAttributeClass()) || !$reflection->hasNativeProperty($node->getName())) {
            return [];
        }

        $type = $reflection->getNativeProperty($node->getName())->getReadableType();
        if ($this->isAllowed($type)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf($this->getMessage(), $reflection->getName().'::$'.$node->getName()))
                ->identifier($this->getIdentifier())
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

        if (
            $type->isNull()->yes()
            || $type->isBoolean()->yes()
            || $type->isInteger()->yes()
            || $type->isFloat()->yes()
            || $type->isString()->yes()
        ) {
            return true;
        }

        if ($type->isArray()->yes()) {
            return $type->isList()->yes() && $this->isAllowed($type->getIterableValueType());
        }

        $classReflections = $type->getObjectClassReflections();
        if ([] === $classReflections) {
            return false;
        }

        foreach ($classReflections as $classReflection) {
            if (!$this->isAllowedClass($classReflection)) {
                return false;
            }
        }

        return true;
    }

    protected function isAllowedClass(ClassReflection $classReflection): bool
    {
        return $classReflection->isEnum()
            || \DateTimeImmutable::class === $classReflection->getName()
            || $classReflection->isSubclassOf(\DateTimeImmutable::class)
            || RoleAttribute::isOn($classReflection, $this->getAttributeClass());
    }
}
