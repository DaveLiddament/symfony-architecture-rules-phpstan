<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\DomainExportCollector;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Every domain named in an #[Exported] `to` list must exist, i.e. contain
 * at least one analysed class. This catches typos, and a renamed domain
 * that a `to` list still refers to.
 *
 * @implements Rule<CollectedDataNode>
 */
final class ExportedToUnknownDomainRule implements Rule
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
        $collected = $node->get(DomainExportCollector::class);

        $domains = [];
        foreach ($collected as $classes) {
            foreach ($classes as $class) {
                if (null !== $class['domain']) {
                    $domains[$class['domain']] = true;
                }
            }
        }

        $errors = [];
        foreach ($collected as $file => $classes) {
            foreach ($classes as $class) {
                foreach ($class['exportedTo'] ?? [] as $domain) {
                    if (isset($domains[$domain])) {
                        continue;
                    }

                    $errors[] = RuleErrorBuilder::message(sprintf(
                        '%s is exported to domain "%s", which does not exist.',
                        $class['class'],
                        $domain,
                    ))
                        ->file($file)
                        ->line($class['line'])
                        ->identifier('architecture.exportedToUnknownDomain')
                        ->build();
                }
            }
        }

        return $errors;
    }
}
