<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Config;

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
    }

    #[Test]
    public function roleRulesAreUnaffected(): void
    {
        self::assertSame(RuleSets::ROLES, RuleSets::enabledIn(self::getContainer(), RuleSets::ROLES));
    }
}
