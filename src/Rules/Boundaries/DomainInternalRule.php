<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\Area;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\AreaType;
use PHPStan\Rules\IdentifierRuleError;

/**
 * A domain's root classes (e.g. App\Registration\*) are its public API;
 * everything in its subdirectories (e.g. App\Registration\Entity\*) is
 * internal and can only be used from within that domain.
 *
 * Shared and Lib are not checked here: they may not depend on domains at
 * all, which SharedIsolationRule and LibIsolationRule enforce.
 */
final class DomainInternalRule extends AbstractBoundaryRule
{
    #[\Override]
    protected function check(Area $source, Area $target, string $targetName): ?IdentifierRuleError
    {
        if (!$target->isDomainInternal()) {
            return null;
        }

        if ($source->is(AreaType::Domain)) {
            if ($source->domain === $target->domain) {
                return null;
            }

            $usedFrom = sprintf('domain "%s"', $source->domain);
        } elseif ($source->is(AreaType::Nursery)) {
            $usedFrom = 'the nursery';
        } else {
            return null;
        }

        return $this->error(
            sprintf(
                '%s is internal to domain "%s" and cannot be used from %s (only domain-root classes are public).',
                $targetName,
                $target->domain,
                $usedFrom,
            ),
            'architecture.domainInternal',
        );
    }
}
