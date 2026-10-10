<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\SymfonyArchitecture\Attribute\Command;
use DaveLiddament\SymfonyArchitecture\Attribute\Controller;
use DaveLiddament\SymfonyArchitecture\Attribute\Repository;
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
 * - #[Controller] and #[Command] (framework-invoked entry points) and
 *   entities (Doctrine mappings scan a known directory) must always live
 *   in their role directory, e.g. App\Registration\Controller.
 * - A #[Repository] lives in its role directory or at the area root.
 *
 * Every other role carries no location demand.
 *
 * @implements Rule<InClassNode>
 */
final class RoleLocationRule implements Rule
{
    private const array CHECKED_AREAS = [AreaType::Domain, AreaType::Shared, AreaType::Nursery];

    /** @var array<string, array{directory: string, allowedAtAreaRoot: bool}> attribute => location */
    private array $roleLocations;

    public function __construct(
        private BoundaryClassifier $classifier,
        string $entityAttribute,
    ) {
        $this->roleLocations = [
            Controller::class => ['directory' => 'Controller', 'allowedAtAreaRoot' => false],
            Command::class => ['directory' => 'Command', 'allowedAtAreaRoot' => false],
            $entityAttribute => ['directory' => 'Entity', 'allowedAtAreaRoot' => false],
            Repository::class => ['directory' => 'Repository', 'allowedAtAreaRoot' => true],
        ];
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
        foreach ($classReflection->getNativeReflection()->getAttributes() as $attribute) {
            $location = $this->roleLocations[$attribute->getName()] ?? null;
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
                '%s has #[%s] so it must live in %s.',
                $className,
                substr($attribute->getName(), (int) strrpos('\\'.$attribute->getName(), '\\')),
                $expected,
            ))
                ->identifier('architecture.roleLocation')
                ->build();
        }

        return $errors;
    }
}
