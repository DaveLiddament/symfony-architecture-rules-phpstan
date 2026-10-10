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
 * Shared is for code used throughout the app. A Shared class that only one
 * domain uses probably belongs in that domain, and one nothing uses may be
 * dead. A Shared class used by other Shared code is a building block, so it
 * is left alone.
 *
 * @implements Rule<CollectedDataNode>
 */
final class SharedUsageRule implements Rule
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
            if ('shared' !== $class->area || $data->isUsedByShared($class)) {
                continue;
            }

            $usedBy = $data->domainsUsing($class);
            if (1 === count($usedBy)) {
                $errors[] = $class->error(sprintf(
                    'Only domain "%s" uses Shared class %s, so it may belong in that domain.',
                    $usedBy[0],
                    $class->name,
                ), 'placement.sharedUsedByOneDomain');
            } elseif ([] === $usedBy) {
                $errors[] = $class->error(sprintf(
                    'No domain uses Shared class %s.',
                    $class->name,
                ), 'placement.sharedUnused');
            }
        }

        return $errors;
    }
}
