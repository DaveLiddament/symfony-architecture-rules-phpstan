<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\DomainDependencyCollector;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\DomainCycleRule;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class CyclesDisabledConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/cycles-disabled.neon',
        ];
    }

    #[Test]
    public function onlyTheCycleRuleAndItsCollectorAreDisabled(): void
    {
        self::assertSame(
            array_values(array_diff(RuleSets::BOUNDARIES, [DomainCycleRule::class])),
            RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES),
        );
        self::assertSame(
            array_values(array_diff(RuleSets::BOUNDARY_COLLECTORS, [DomainDependencyCollector::class])),
            RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARY_COLLECTORS, 'phpstan.collector'),
        );
    }
}
