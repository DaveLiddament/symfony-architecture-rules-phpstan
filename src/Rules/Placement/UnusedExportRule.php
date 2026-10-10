<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Placement;

use DaveLiddament\PhpstanArchitectureRules\Placement\PlacementData;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;

/**
 * An #[Exported] class that no other domain uses needn't be exported, and a
 * domain listed in `to` that doesn't use the class needn't be listed.
 * Domains in `to` that don't exist are left to ExportedToUnknownDomainRule.
 *
 * @implements Rule<CollectedDataNode>
 */
final class UnusedExportRule implements Rule
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
        $data = PlacementData::fromNode($node);

        $errors = [];
        foreach ($data->classes as $class) {
            if ('shared' === $class->area || !$class->exported) {
                continue;
            }

            $usedBy = $data->domainsUsing($class);
            if ([] === $usedBy) {
                $errors[] = $class->error(sprintf(
                    'No other domain uses %s, so it needn\'t be #[Exported].',
                    $class->name,
                ), 'placement.exportUnused');

                continue;
            }

            foreach ($class->exportedTo ?? [] as $domain) {
                if ($data->domainExists($domain) && !in_array($domain, $usedBy, true)) {
                    $errors[] = $class->error(sprintf(
                        '%s is exported to domain "%s", which doesn\'t use it.',
                        $class->name,
                        $domain,
                    ), 'placement.exportedToUnusedDomain');
                }
            }
        }

        return $errors;
    }
}
