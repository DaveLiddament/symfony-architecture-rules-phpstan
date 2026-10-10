<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use PHPStan\Rules\IdentifierRuleError;

/**
 * Lib holds application-agnostic code, so it must not depend on any
 * application code: app-root classes, domains, Shared or Pending.
 */
final class LibIsolationRule extends AbstractBoundaryRule
{
    #[\Override]
    protected function check(Area $source, Area $target, string $targetName): ?IdentifierRuleError
    {
        if (!$source->is(AreaType::Lib) || !$target->isAppCode()) {
            return null;
        }

        return $this->error(
            sprintf('Lib code must stay application-agnostic and cannot depend on %s.', $targetName),
            'architecture.libIsolation',
        );
    }
}
