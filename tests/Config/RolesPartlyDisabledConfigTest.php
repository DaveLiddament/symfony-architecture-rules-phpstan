<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Config;

use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class RolesPartlyDisabledConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/roles-partly-disabled.neon',
        ];
    }

    #[Test]
    public function onlyTheDisabledRolesAreTurnedOff(): void
    {
        self::assertSame(
            RuleSets::roles(exceptRoles: ['dto', 'service']),
            RuleSets::enabledIn(self::getContainer(), RuleSets::roles()),
        );
    }

    #[Test]
    public function boundaryRulesAreUnaffected(): void
    {
        self::assertSame(RuleSets::BOUNDARIES, RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES));
    }
}
