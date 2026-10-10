<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Roles\PersistenceClasses;
use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Tests\Config\Fixtures\RoleAliases\DoctrineMapped;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\AppEntityManager;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class FrameworksDisabledConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/frameworks-disabled.neon',
        ];
    }

    #[Test]
    public function aDisabledPresetAddsNoAliases(): void
    {
        $roleResolver = self::getContainer()->getByType(RoleResolver::class);

        self::assertFalse($roleResolver->plays(
            self::createReflectionProvider()->getClass(DoctrineMapped::class),
            Role::Entity,
        ));
    }

    #[Test]
    public function aDisabledPresetAddsNoPersistenceClasses(): void
    {
        $persistenceClasses = self::getContainer()->getByType(PersistenceClasses::class);

        self::assertNull($persistenceClasses->matching(
            self::createReflectionProvider()->getClass(AppEntityManager::class),
        ));
    }

    #[Test]
    public function allRulesStayEnabled(): void
    {
        self::assertSame(RuleSets::roles(), RuleSets::enabledIn(self::getContainer(), RuleSets::roles()));
    }
}
