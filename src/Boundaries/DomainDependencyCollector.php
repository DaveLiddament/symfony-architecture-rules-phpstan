<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Boundaries;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;

/**
 * Collects every class name one domain (or Pending) uses from another, so
 * the dependencies between domains can be checked for cycles.
 *
 * @implements Collector<Name, array{sourceDomain: string, targetDomain: string, sourceClass: string|null, targetClass: string, line: int}>
 */
final readonly class DomainDependencyCollector implements Collector
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
        if (
            !$source->actsAsDomain()
            || !$target->actsAsDomain()
            || null === $source->domain
            || null === $target->domain
            || $source->domain === $target->domain
        ) {
            return null;
        }

        return [
            'sourceDomain' => $source->domain,
            'targetDomain' => $target->domain,
            'sourceClass' => $scope->getClassReflection()?->getName(),
            'targetClass' => $targetClass,
            'line' => $node->getStartLine(),
        ];
    }
}
