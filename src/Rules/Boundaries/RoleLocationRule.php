<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Location is enforced only for roles where placement carries weight,
 * relative to the root of the class's area (a domain, Shared or the
 * Nursery):
 *
 * - Controllers and CLI commands (framework-invoked entry points) and
 *   entities (ORM mappings scan a known directory) must always live in
 *   their role directory, e.g. App\Registration\Controller.
 * - A repository lives in its role directory or at the area root.
 *
 * Every other role carries no location demand.
 *
 * @implements Rule<InClassNode>
 */
final class RoleLocationRule implements Rule
{
    private const array CHECKED_AREAS = [AreaType::Domain, AreaType::Shared, AreaType::Nursery];

    /** @var array<string, array{directory: string, allowedAtAreaRoot: bool}> role => location */
    private const array ROLE_LOCATIONS = [
        Role::Controller->value => ['directory' => 'Controller', 'allowedAtAreaRoot' => false],
        Role::CliCommand->value => ['directory' => 'CliCommand', 'allowedAtAreaRoot' => false],
        Role::Entity->value => ['directory' => 'Entity', 'allowedAtAreaRoot' => false],
        Role::Repository->value => ['directory' => 'Repository', 'allowedAtAreaRoot' => true],
    ];

    public function __construct(
        private BoundaryClassifier $classifier,
        private RoleResolver $roleResolver,
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
        $classReflection = $node->getClassReflection();
        $className = $classReflection->getName();
        $area = $this->classifier->classifyClass($className);
        $areaNamespace = $this->classifier->getAreaNamespace($area);
        if (!in_array($area->type, self::CHECKED_AREAS, true) || null === $areaNamespace) {
            return [];
        }

        $classNamespace = substr($className, 0, (int) strrpos($className, '\\'));
        $pathInArea = substr($classNamespace, strlen($areaNamespace) + 1);
        $isAtAreaRoot = $classNamespace === $areaNamespace;
        $directory = $isAtAreaRoot ? null : explode('\\', $pathInArea)[0];

        $errors = [];
        foreach ($this->roleResolver->rolesOf($classReflection) as $role) {
            $location = self::ROLE_LOCATIONS[$role->value] ?? null;
            if (
                null === $location
                || $directory === $location['directory']
                || ($isAtAreaRoot && $location['allowedAtAreaRoot'])
            ) {
                continue;
            }

            $expected = $areaNamespace.'\\'.$location['directory'];
            if ($location['allowedAtAreaRoot']) {
                $expected .= ' or '.$areaNamespace;
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                '%s has the %s role, so it must live in %s.',
                $className,
                $role->value,
                $expected,
            ))
                ->identifier('architecture.roleLocation')
                ->build();
        }

        return $errors;
    }
}
