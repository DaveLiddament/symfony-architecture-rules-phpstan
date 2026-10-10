<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class RoleRequiredDisabledConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/role-required-disabled.neon',
        ];
    }

    #[Test]
    public function roleRequiredRuleDoesNotRunWhenDisabled(): void
    {
        self::assertSame([], RuleSets::enabledIn(self::getContainer(), RuleSets::ROLE_REQUIRED));
    }

    #[Test]
    public function otherRulesAreUnaffected(): void
    {
        self::assertSame(RuleSets::roles(), RuleSets::enabledIn(self::getContainer(), RuleSets::roles()));
        self::assertSame(RuleSets::BOUNDARIES, RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES));
    }
}
