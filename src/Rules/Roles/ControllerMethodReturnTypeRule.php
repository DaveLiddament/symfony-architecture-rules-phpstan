<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Public methods on #[Controller] classes must declare a return type that is
 * one of the allowed return types (or a subclass), or a union made only of
 * these. Never nullable.
 *
 * @implements Rule<InClassMethodNode>
 */
final class ControllerMethodReturnTypeRule implements Rule
{
    /** @var list<string> */
    private array $allowedReturnTypes;

    /**
     * @param list<string> $allowedReturnTypes
     */
    public function __construct(
        private RoleResolver $roleResolver,
        private ReflectionProvider $reflectionProvider,
        array $allowedReturnTypes,
    ) {
        $this->allowedReturnTypes = array_map(static fn (string $type): string => trim($type, '\\'), $allowedReturnTypes);
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
        $classReflection = $node->getClassReflection();
        if (!$this->roleResolver->plays($classReflection, Role::Controller)) {
            return [];
        }

        $methodReflection = $node->getMethodReflection();
        $methodName = $methodReflection->getName();
        if (!$methodReflection->isPublic() || '__construct' === $methodName) {
            return [];
        }

        if ($this->isAllowedReturnType($node->getOriginalNode()->returnType)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Public method %s::%s() on a #[Controller] must declare a return type of %s.',
                $classReflection->getDisplayName(),
                $methodName,
                $this->describeAllowedReturnTypes(),
            ))
                ->identifier('architecture.controllerReturnType')
                ->build(),
        ];
    }

    private function isAllowedReturnType(ComplexType|Identifier|Name|null $returnType): bool
    {
        if ($returnType instanceof Name) {
            return $this->isAllowedClassName($returnType);
        }

        if ($returnType instanceof UnionType) {
            foreach ($returnType->types as $type) {
                if (!$type instanceof Name || !$this->isAllowedClassName($type)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    private function isAllowedClassName(Name $name): bool
    {
        if (!$this->reflectionProvider->hasClass($name->toString())) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($name->toString());
        foreach ($this->allowedReturnTypes as $allowedReturnType) {
            if ($allowedReturnType === $classReflection->getName() || $classReflection->isSubclassOf($allowedReturnType)) {
                return true;
            }
        }

        return false;
    }

    private function describeAllowedReturnTypes(): string
    {
        $shortNames = array_map(
            static fn (string $type): string => substr($type, (int) strrpos('\\'.$type, '\\')),
            $this->allowedReturnTypes,
        );

        if (1 === count($shortNames)) {
            return $shortNames[0];
        }

        return implode(', ', $shortNames).', or a union of these';
    }
}
