<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Every application class (in a domain, Shared or the Nursery) plays a role,
 * through a role attribute or a role alias, so the role contracts can see
 * it: a class without a role is invisible to every other role rule. Enums,
 * interfaces and traits are exempt, as are framework-glue classes directly
 * in the app namespace (e.g. App\Kernel), ignored namespaces and the
 * configured exempt classes.
 *
 * @implements Rule<InClassNode>
 */
final class RoleRequiredRule implements Rule
{
    private const array CHECKED_AREAS = [AreaType::Domain, AreaType::Shared, AreaType::Nursery];

    /**
     * @param list<string> $exemptClasses
     */
    public function __construct(
        private BoundaryClassifier $classifier,
        private RoleResolver $roleResolver,
        private array $exemptClasses,
    ) {
    }

    #[\Override]
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $node->getClassReflection();
        if ($reflection->isAnonymous() || $reflection->isEnum() || $reflection->isInterface() || $reflection->isTrait()) {
            return [];
        }

        $name = $reflection->getName();
        if (
            !in_array($this->classifier->classifyClass($name)->type, self::CHECKED_AREAS, true)
            || in_array($name, $this->exemptClasses, true)
            || $this->roleResolver->hasAnyRole($reflection)
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Class %s declares no role: give it a role attribute from %s (#[Service], #[Dto], #[ValueObject], ...).',
                $name,
                $this->roleResolver->getAttributeNamespace(),
            ))
                ->identifier('architecture.roleRequired')
                ->build(),
        ];
    }
}
