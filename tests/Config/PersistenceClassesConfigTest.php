<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Roles\PersistenceClasses;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\AppEntityManager;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\Plain;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class PersistenceClassesConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/persistence-classes.neon',
        ];
    }

    #[Test]
    public function configuredClassesAreAddedToThePresets(): void
    {
        $persistenceClasses = self::getContainer()->getByType(PersistenceClasses::class);
        $reflectionProvider = self::createReflectionProvider();

        self::assertSame(Plain::class, $persistenceClasses->matching($reflectionProvider->getClass(Plain::class)));
        self::assertNotNull($persistenceClasses->matching($reflectionProvider->getClass(AppEntityManager::class)));
    }
}
