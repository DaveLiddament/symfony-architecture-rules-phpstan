<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Rules\Placement\PendingWayOutRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Placement\UnusedExportRule;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class PlacementPartlyDisabledConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/../../placement.neon',
            __DIR__.'/Fixtures/placement-partly-disabled.neon',
        ];
    }

    #[Test]
    public function onlyTheSwitchedOffRuleIsDisabled(): void
    {
        self::assertSame(
            [PendingWayOutRule::class, UnusedExportRule::class],
            RuleSets::enabledIn(self::getContainer(), RuleSets::PLACEMENT),
        );
    }
}
