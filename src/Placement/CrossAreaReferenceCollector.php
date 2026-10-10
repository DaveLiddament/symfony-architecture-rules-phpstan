<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Placement;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;

/**
 * Collects every class name used across a boundary between Shared, Pending
 * and the domains: from one domain (or Pending) to another, or between
 * Shared and anything. Domain is null for Shared.
 *
 * @implements Collector<Name, array{sourceClass: string|null, sourceDomain: string|null, targetClass: string, targetDomain: string|null}>
 */
final readonly class CrossAreaReferenceCollector implements Collector
{
    public function __construct(
        private BoundaryClassifier $classifier,
    ) {
    }

    #[\Override]
    public function getNodeType(): string
    {
        return Name::class;
    }

    #[\Override]
    public function processNode(Node $node, Scope $scope): ?array
    {
        if ($node->isSpecialClassName()) {
            return null;
        }

        $source = $this->classifier->classifyNamespace($scope->getNamespace());
        $targetClass = $node->toString();
        $target = $this->classifier->classifyClass($targetClass);
        if (!self::isPlaced($source) || !self::isPlaced($target)) {
            return null;
        }

        $sourceClass = $scope->getClassReflection()?->getName();
        if ($sourceClass === $targetClass || (null !== $source->domain && $source->domain === $target->domain)) {
            return null;
        }

        return [
            'sourceClass' => $sourceClass,
            'sourceDomain' => $source->domain,
            'targetClass' => $targetClass,
            'targetDomain' => $target->domain,
        ];
    }

    private static function isPlaced(Area $area): bool
    {
        return $area->actsAsDomain() || $area->is(AreaType::Shared);
    }
}
