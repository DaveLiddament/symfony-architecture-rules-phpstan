<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\Export;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;

/**
 * A domain's classes are internal to it, wherever they live in the domain.
 * Another domain may use one only if it is #[Exported], and, when the
 * export lists domains in `to`, only if it is one of them. Pending acts as
 * a domain, both ways.
 *
 * Shared and Lib are not checked here: they may not depend on domains at
 * all, which SharedIsolationRule and LibIsolationRule enforce.
 */
final class DomainInternalRule extends AbstractBoundaryRule
{
    public function __construct(
        BoundaryClassifier $classifier,
        private readonly ReflectionProvider $reflectionProvider,
    ) {
        parent::__construct($classifier);
    }

    #[\Override]
    protected function check(Area $source, Area $target, string $targetName): ?IdentifierRuleError
    {
        if (
            !$target->actsAsDomain()
            || !$source->actsAsDomain()
            || null === $source->domain
            || $source->domain === $target->domain
        ) {
            return null;
        }

        $usedFrom = sprintf('domain "%s"', $source->domain);

        if (!$this->reflectionProvider->hasClass($targetName)) {
            return null;
        }

        $export = Export::of($this->reflectionProvider->getClass($targetName));
        if (null === $export) {
            return $this->error(
                sprintf(
                    '%s is internal to domain "%s" and cannot be used from %s (only #[Exported] classes are public).',
                    $targetName,
                    $target->domain,
                    $usedFrom,
                ),
                'architecture.domainInternal',
            );
        }

        if ($export->allows($source->domain)) {
            return null;
        }

        return $this->error(
            sprintf(
                '%s is not exported to %s (its #[Exported] lists %s).',
                $targetName,
                $usedFrom,
                $export->describeTo(),
            ),
            'architecture.domainInternal',
        );
    }
}
