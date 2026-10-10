<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class BoundariesDisabledConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/boundaries-disabled.neon',
        ];
    }

    #[Test]
    public function noBoundaryRulesRunWhenDisabled(): void
    {
        self::assertSame([], RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES));
        self::assertSame([], RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARY_COLLECTORS, 'phpstan.collector'));
    }

    #[Test]
    public function roleRulesAreUnaffected(): void
    {
        self::assertSame(RuleSets::roles(), RuleSets::enabledIn(self::getContainer(), RuleSets::roles()));
    }
}
