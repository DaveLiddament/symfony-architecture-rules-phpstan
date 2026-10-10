<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use PHPStan\Rules\IdentifierRuleError;

/**
 * Shared is used by every domain, so it must not depend on any domain or on
 * the Nursery: dependencies only flow from domains to Shared.
 */
final class SharedIsolationRule extends AbstractBoundaryRule
{
    #[\Override]
    protected function check(Area $source, Area $target, string $targetName): ?IdentifierRuleError
    {
        if (!$source->is(AreaType::Shared)) {
            return null;
        }

        if ($target->is(AreaType::Domain)) {
            return $this->error(
                sprintf('Shared code cannot depend on domain code (%s is in domain "%s").', $targetName, $target->domain),
                'architecture.sharedIsolation',
            );
        }

        if ($target->is(AreaType::Nursery)) {
            return $this->error(
                sprintf('Shared code cannot depend on nursery code (%s).', $targetName),
                'architecture.sharedIsolation',
            );
        }

        return null;
    }
}
