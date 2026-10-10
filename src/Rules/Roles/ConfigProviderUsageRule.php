<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

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
 * Only a #[Service] may hold a #[ConfigProvider], directly or in an
 * array or iterable. Checked on every class property in the codebase.
 *
 * @implements Rule<ClassPropertyNode>
 */
final class ConfigProviderUsageRule implements Rule
{
    public function __construct(
        private RoleResolver $roleResolver,
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
        if ($this->roleResolver->plays($reflection, Role::Service) || !$reflection->hasNativeProperty($node->getName())) {
            return [];
        }

        $type = $reflection->getNativeProperty($node->getName())->getReadableType();
        if (!$this->mentionsConfigProvider($type)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Config providers may only be injected into #[Service] classes, but %s::$%s holds one.',
                $reflection->getName(),
                $node->getName(),
            ))
                ->identifier('configProvider.onlyInService')
                ->build(),
        ];
    }

    private function mentionsConfigProvider(Type $type): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $inner) {
                if ($this->mentionsConfigProvider($inner)) {
                    return true;
                }
            }

            return false;
        }

        if ($type->isArray()->yes()) {
            return $this->mentionsConfigProvider($type->getIterableValueType());
        }

        // An object is judged by its class, never by what it iterates over:
        // descending into a class that iterates over itself (e.g. Symfony's
        // FormInterface) would recurse forever.
        $classReflections = $type->getObjectClassReflections();
        if ([] !== $classReflections) {
            foreach ($classReflections as $classReflection) {
                if ($this->roleResolver->plays($classReflection, Role::ConfigProvider)) {
                    return true;
                }
            }

            return false;
        }

        // The bare "iterable" native type.
        if ($type->isIterable()->yes()) {
            return $this->mentionsConfigProvider($type->getIterableValueType());
        }

        return false;
    }
}
