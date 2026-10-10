<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Boundaries;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\InClassNode;

/**
 * Collects, for every analysed class, the domain it is in and the domains
 * its #[Exported] limits it to, so the domains named in `to` can be checked
 * against the domains that exist.
 *
 * @implements Collector<InClassNode, array{class: string, domain: string|null, exportedTo: list<string>|null, line: int}>
 */
final readonly class DomainExportCollector implements Collector
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
        $className = $classReflection->getName();
        $domain = $this->classifier->classifyClass($className)->domain;
        $exportedTo = Export::of($classReflection)?->to;
        if (null === $domain && null === $exportedTo) {
            return null;
        }

        return [
            'class' => $className,
            'domain' => $domain,
            'exportedTo' => $exportedTo,
            'line' => $node->getStartLine(),
        ];
    }
}
