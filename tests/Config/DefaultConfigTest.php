<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * extension.neon on its own: the defaults a user gets out of the box.
 */
final class DefaultConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__.'/../../extension.neon'];
    }

    #[Test]
    public function allBoundaryRulesAreEnabled(): void
    {
        self::assertSame(RuleSets::BOUNDARIES, RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES));
        self::assertSame(
            RuleSets::BOUNDARY_COLLECTORS,
            RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARY_COLLECTORS, 'phpstan.collector'),
        );
    }

    #[Test]
    public function allRoleRulesAreEnabled(): void
    {
        self::assertSame(RuleSets::roles(), RuleSets::enabledIn(self::getContainer(), RuleSets::roles()));
    }

    #[Test]
    public function roleRequiredRuleIsEnabled(): void
    {
        self::assertSame(RuleSets::ROLE_REQUIRED, RuleSets::enabledIn(self::getContainer(), RuleSets::ROLE_REQUIRED));
    }

    #[Test]
    public function defaultNamespacesAreUsed(): void
    {
        $classifier = self::getContainer()->getByType(BoundaryClassifier::class);

        self::assertEquals(Area::of(AreaType::Lib), $classifier->classifyClass('Lib\Clock'));
        self::assertEquals(Area::of(AreaType::Shared), $classifier->classifyClass('App\Shared\User'));
        self::assertEquals(Area::of(AreaType::Nursery), $classifier->classifyClass('App\Nursery\NewThing'));
        self::assertEquals(Area::of(AreaType::Ignored), $classifier->classifyClass('App\Tests\SomeTest'));
        self::assertEquals(Area::of(AreaType::AppRoot), $classifier->classifyClass('App\Kernel'));
        self::assertEquals(Area::domain('Registration'), $classifier->classifyClass('App\Registration\Service'));
    }
}
