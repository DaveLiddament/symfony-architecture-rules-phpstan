<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Config;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\DtoDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ServiceDeclarationRule;
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
        $expected = array_values(array_diff(RuleSets::ROLES, [DtoDeclarationRule::class, ServiceDeclarationRule::class]));

        self::assertSame($expected, RuleSets::enabledIn(self::getContainer(), RuleSets::ROLES));
    }

    #[Test]
    public function boundaryRulesAreUnaffected(): void
    {
        self::assertSame(RuleSets::BOUNDARIES, RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES));
    }
}
