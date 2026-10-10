<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Placement;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\Export;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\InClassNode;

/**
 * Collects every analysed class in Shared, Pending or a domain, with its
 * export, so the placement rules can see where each class lives.
 *
 * @implements Collector<InClassNode, array{class: string, area: 'shared'|'pending'|'domain', domain: string|null, exported: bool, exportedTo: list<string>|null, line: int}>
 */
final readonly class PlacedClassCollector implements Collector
{
    public function __construct(
        private BoundaryClassifier $classifier,
    ) {
    }

    #[\Override]
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    #[\Override]
    public function processNode(Node $node, Scope $scope): ?array
    {
        $classReflection = $node->getClassReflection();
        if ($classReflection->isAnonymous()) {
            return null;
        }

        $className = $classReflection->getName();
        $area = $this->classifier->classifyClass($className);
        $areaName = match ($area->type) {
            AreaType::Shared => 'shared',
            AreaType::Pending => 'pending',
            AreaType::Domain => 'domain',
            default => null,
        };
        if (null === $areaName) {
            return null;
        }

        $export = Export::of($classReflection);

        return [
            'class' => $className,
            'area' => $areaName,
            'domain' => $area->domain,
            'exported' => null !== $export,
            'exportedTo' => $export?->to,
            'line' => $node->getStartLine(),
        ];
    }
}
