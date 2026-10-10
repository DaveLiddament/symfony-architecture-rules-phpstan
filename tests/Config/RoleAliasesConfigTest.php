<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Tests\Config\Fixtures\RoleAliases\DoctrineMapped;
use DaveLiddament\PhpstanArchitectureRules\Tests\Config\Fixtures\RoleAliases\Order;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class RoleAliasesConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/role-aliases.neon',
        ];
    }

    #[Test]
    public function aConfiguredAliasGivesTheRole(): void
    {
        self::assertTrue($this->plays(Order::class, Role::Entity));
    }

    #[Test]
    public function theDefaultAliasesAreKept(): void
    {
        self::assertTrue($this->plays(DoctrineMapped::class, Role::Entity));
    }

    /**
     * @param class-string $className
     */
    private function plays(string $className, Role $role): bool
    {
        return self::getContainer()->getByType(RoleResolver::class)->plays(
            self::createReflectionProvider()->getClass($className),
            $role,
        );
    }
}
