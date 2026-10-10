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
 * Pending is for code whose home isn't known yet. Once only one domain is
 * involved with a Pending class, its home is known:
 *
 * - exactly one domain uses it, and it uses no other domain; or
 * - no domain uses it, and it uses exactly one domain.
 *
 * When several domains are involved, it stays in Pending until someone
 * chooses between Shared and one domain exporting it.
 *
 * @implements Rule<CollectedDataNode>
 */
final class PendingWayOutRule implements Rule
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
            if ('pending' !== $class->area) {
                continue;
            }

            $usedBy = $data->domainsUsing($class);
            $uses = $data->domainsUsedBy($class);

            if (1 === count($usedBy) && [] === array_diff($uses, $usedBy)) {
                $errors[] = $class->error(sprintf(
                    'Only domain "%s" uses %s, so it can move out of Pending into that domain.',
                    $usedBy[0],
                    $class->name,
                ), 'placement.pendingUsedByOneDomain');
            } elseif ([] === $usedBy && 1 === count($uses)) {
                $errors[] = $class->error(sprintf(
                    '%s uses only domain "%s" and no domain uses it, so it can move out of Pending into that domain.',
                    $class->name,
                    $uses[0],
                ), 'placement.pendingUsesOneDomain');
            }
        }

        return $errors;
    }
}
