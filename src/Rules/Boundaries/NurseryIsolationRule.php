<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use PHPStan\Rules\IdentifierRuleError;

/**
 * The Nursery holds code whose domain is not known yet. Domains must not
 * depend on it: once a domain needs nursery code, that code has found its
 * home and should be moved into a domain (or Shared).
 *
 * Shared and Lib are not checked here: SharedIsolationRule and
 * LibIsolationRule already forbid them from depending on the Nursery.
 */
final class NurseryIsolationRule extends AbstractBoundaryRule
{
    #[\Override]
    protected function check(Area $source, Area $target, string $targetName): ?IdentifierRuleError
    {
        if (!$source->is(AreaType::Domain) || !$target->is(AreaType::Nursery)) {
            return null;
        }

        return $this->error(
            sprintf(
                'Domain "%s" cannot depend on nursery code (%s). Move it into a domain or Shared first.',
                $source->domain,
                $targetName,
            ),
            'architecture.nurseryIsolation',
        );
    }
}
