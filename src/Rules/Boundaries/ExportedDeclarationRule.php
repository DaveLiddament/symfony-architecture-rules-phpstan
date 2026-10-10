<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Export;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * An #[Exported] class must be one other domains can sensibly use: not a
 * trait, and not a role that is never exportable (entry points, view
 * models, serializers, config providers). Its `to` list, when given, must
 * name at least one domain.
 *
 * It runs on ClassLike nodes rather than InClassNode, because PHPStan
 * analyses a trait only where a class uses it.
 *
 * @implements Rule<ClassLike>
 */
final class ExportedDeclarationRule implements Rule
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private RoleResolver $roleResolver,
    ) {
    }

    #[\Override]
    public function getNodeType(): string
    {
        return ClassLike::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $className = $node->namespacedName?->toString();
        if (null === $className || !$this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        $export = Export::of($classReflection);
        if (null === $export) {
            return [];
        }

        $className = $classReflection->getName();
        $errors = [];

        if ($classReflection->isTrait()) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                '%s is a trait, so it cannot be exported.',
                $className,
            ))->identifier('architecture.notExportable')->build();
        }

        foreach ($this->roleResolver->rolesOf($classReflection) as $role) {
            if (!$role->isExportable()) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    '%s has the %s role, so it cannot be exported.',
                    $className,
                    $role->value,
                ))->identifier('architecture.notExportable')->build();
            }
        }

        if ([] === $export->to) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                '%s is exported to no domains: list the domains in `to`, or remove `to` to export it to every domain.',
                $className,
            ))->identifier('architecture.exportedToNone')->build();
        }

        return $errors;
    }
}
