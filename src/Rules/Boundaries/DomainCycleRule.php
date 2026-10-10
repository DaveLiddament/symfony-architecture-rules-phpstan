<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\DomainDependencyCollector;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Dependencies between domains must not go round in a circle: if
 * Registration uses Walks, Walks may not use Registration, directly or
 * through other domains. Domains in a cycle can't be understood, changed or
 * extracted on their own. Pending counts as a domain.
 *
 * Each group of domains that depend on each other is reported once, with
 * one cycle through it and an example class reference for each step. The
 * error is placed at the first step's reference.
 *
 * @implements Rule<CollectedDataNode>
 */
final class DomainCycleRule implements Rule
{
    #[\Override]
    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $edges = $this->edges($node);

        $errors = [];
        foreach ($this->stronglyConnectedComponents($edges) as $component) {
            if (count($component) < 2) {
                continue;
            }

            $cycle = $this->shortestCycle($component[0], $edges, $component);
            $steps = [];
            $examples = [];
            for ($i = 0, $count = count($cycle) - 1; $i < $count; ++$i) {
                $edge = $edges[$cycle[$i]][$cycle[$i + 1]];
                $steps[] = $edge;
                $examples[] = sprintf('%s uses %s', $edge['sourceClass'] ?? $edge['sourceDomain'], $edge['targetClass']);
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                'Domains %s depend on each other in a cycle: %s (%s).',
                implode(', ', array_map(static fn (string $domain): string => '"'.$domain.'"', $component)),
                implode(' → ', $cycle),
                implode('; ', $examples),
            ))
                ->file($steps[0]['file'])
                ->line($steps[0]['line'])
                ->identifier('architecture.domainCycle')
                ->build();
        }

        return $errors;
    }

    /**
     * Each domain dependency, with the first reference (by file and line)
     * as its example.
     *
     * @return array<string, array<string, array{sourceDomain: string, sourceClass: string|null, targetClass: string, file: string, line: int}>> source => target => example
     */
    private function edges(CollectedDataNode $node): array
    {
        $edges = [];
        foreach ($node->get(DomainDependencyCollector::class) as $file => $references) {
            foreach ($references as $reference) {
                $line = $reference['line'];
                $existing = $edges[$reference['sourceDomain']][$reference['targetDomain']] ?? null;
                if (null !== $existing && [$existing['file'], $existing['line']] <= [$file, $line]) {
                    continue;
                }

                $edges[$reference['sourceDomain']][$reference['targetDomain']] = [
                    'sourceDomain' => $reference['sourceDomain'],
                    'sourceClass' => $reference['sourceClass'],
                    'targetClass' => $reference['targetClass'],
                    'file' => $file,
                    'line' => $line,
                ];
            }
        }

        ksort($edges);
        foreach ($edges as &$targets) {
            ksort($targets);
        }

        return $edges;
    }

    /**
     * Tarjan's algorithm. Each component is sorted by domain name.
     *
     * @param array<string, array<string, mixed>> $edges
     *
     * @return list<non-empty-list<string>>
     */
    private function stronglyConnectedComponents(array $edges): array
    {
        $index = 0;
        $indexes = [];
        $lowLinks = [];
        $stack = [];
        $onStack = [];
        $components = [];

        $visit = static function (string $domain) use (&$visit, &$index, &$indexes, &$lowLinks, &$stack, &$onStack, &$components, $edges): void {
            $indexes[$domain] = $index;
            $lowLinks[$domain] = $index;
            ++$index;
            $stack[] = $domain;
            $onStack[$domain] = true;

            foreach (array_keys($edges[$domain] ?? []) as $target) {
                if (!isset($indexes[$target])) {
                    $visit($target);
                    $lowLinks[$domain] = min($lowLinks[$domain], $lowLinks[$target]);
                } elseif (isset($onStack[$target])) {
                    $lowLinks[$domain] = min($lowLinks[$domain], $indexes[$target]);
                }
            }

            if ($lowLinks[$domain] !== $indexes[$domain]) {
                return;
            }

            $component = [];
            do {
                $member = array_pop($stack) ?? $domain;
                unset($onStack[$member]);
                $component[] = $member;
            } while ($member !== $domain);
            sort($component);
            $components[] = $component;
        };

        foreach (array_keys($edges) as $domain) {
            if (!isset($indexes[$domain])) {
                $visit($domain);
            }
        }

        usort($components, static fn (array $a, array $b): int => $a[0] <=> $b[0]);

        return $components;
    }

    /**
     * The shortest cycle from the domain back to itself within the
     * component, e.g. [A, B, A].
     *
     * @param array<string, array<string, mixed>> $edges
     * @param list<string> $component
     *
     * @return non-empty-list<string>
     */
    private function shortestCycle(string $start, array $edges, array $component): array
    {
        $previous = [];
        $queue = [$start];
        while ([] !== $queue) {
            $domain = array_shift($queue);
            foreach (array_keys($edges[$domain] ?? []) as $target) {
                if (!in_array($target, $component, true)) {
                    continue;
                }

                if ($target === $start) {
                    $path = [$start];
                    for ($step = $domain; $step !== $start; $step = $previous[$step]) {
                        array_unshift($path, $step);
                    }
                    array_unshift($path, $start);

                    return $path;
                }

                if (!isset($previous[$target])) {
                    $previous[$target] = $domain;
                    $queue[] = $target;
                }
            }
        }

        return [$start];
    }
}
