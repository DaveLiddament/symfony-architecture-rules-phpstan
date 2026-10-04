<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\TypeCombinator;

/**
 * Public repository methods speak a fixed vocabulary (find*, get*,
 * persist*, update*, delete*, has*, is*), and each verb carries a return
 * contract: a get never returns null, a find returns exactly one nullable
 * non-iterable value (collections belong to get), writes return void, and
 * an iterable result is never nullable (return an empty list instead).
 *
 * @implements Rule<InClassMethodNode>
 */
final class RepositoryMethodRule implements Rule
{
    private const array PREFIXES = ['find', 'get', 'persist', 'update', 'delete', 'has', 'is'];

    private const array WRITE_PREFIXES = ['persist', 'update', 'delete'];

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
        if (null === $classReflection || !RoleAttribute::isOn($classReflection, Repository::class)) {
            return [];
        }

        $method = $node->getOriginalNode();
        $name = $method->name->toString();
        if (!$method->isPublic() || str_starts_with($name, '__')) {
            return [];
        }

        $prefix = $this->matchedPrefix($name);
        if (null === $prefix) {
            return [$this->error(sprintf(
                'Repository method %s::%s() must start with find, get, persist, update, delete, has or is.',
                $classReflection->getName(),
                $name,
            ), 'repository.methodName')];
        }

        $returnType = $node->getMethodReflection()->getOnlyVariant()->getReturnType();

        if (in_array($prefix, self::WRITE_PREFIXES, true) && !$returnType->isVoid()->yes()) {
            return [$this->error(sprintf(
                'Repository method %s::%s() is a write, so it must return void.',
                $classReflection->getName(),
                $name,
            ), 'repository.methodReturn')];
        }

        if ('get' === $prefix && TypeCombinator::containsNull($returnType)) {
            return [$this->error(sprintf(
                'Repository method %s::%s() is a get, so its return type must not be nullable.',
                $classReflection->getName(),
                $name,
            ), 'repository.methodReturn')];
        }

        if ('find' === $prefix
            && (!TypeCombinator::containsNull($returnType) || TypeCombinator::removeNull($returnType)->isIterable()->yes())
        ) {
            return [$this->error(sprintf(
                'Repository method %s::%s() is a find, so it must return a nullable, non-iterable value.',
                $classReflection->getName(),
                $name,
            ), 'repository.methodReturn')];
        }

        if (TypeCombinator::containsNull($returnType) && TypeCombinator::removeNull($returnType)->isIterable()->yes()) {
            return [$this->error(sprintf(
                'Repository method %s::%s() must not return a nullable iterable.',
                $classReflection->getName(),
                $name,
            ), 'repository.methodReturn')];
        }

        return [];
    }

    private function error(string $message, string $identifier): IdentifierRuleError
    {
        return RuleErrorBuilder::message($message)->identifier($identifier)->build();
    }

    /**
     * The prefix must end the name or be followed by an uppercase letter or
     * digit, so "getaway" is not a get.
     */
    private function matchedPrefix(string $name): ?string
    {
        foreach (self::PREFIXES as $prefix) {
            if (!str_starts_with($name, $prefix)) {
                continue;
            }

            $rest = substr($name, strlen($prefix));
            if ('' === $rest || 1 === preg_match('/^[A-Z0-9]/', $rest)) {
                return $prefix;
            }
        }

        return null;
    }
}
