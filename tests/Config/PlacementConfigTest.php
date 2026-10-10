<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * extension.neon with placement.neon included: the placement report.
 */
final class PlacementConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/../../placement.neon',
        ];
    }

    #[Test]
    public function allPlacementRulesAndCollectorsAreEnabled(): void
    {
        self::assertSame(RuleSets::PLACEMENT, RuleSets::enabledIn(self::getContainer(), RuleSets::PLACEMENT));
        self::assertSame(
            RuleSets::PLACEMENT_COLLECTORS,
            RuleSets::enabledIn(self::getContainer(), RuleSets::PLACEMENT_COLLECTORS, 'phpstan.collector'),
        );
    }

    #[Test]
    public function theUsualRulesStillRun(): void
    {
        self::assertSame(RuleSets::BOUNDARIES, RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES));
    }
}
