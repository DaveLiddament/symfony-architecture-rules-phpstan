<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\PersistenceClasses;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\AppEntityManager;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\Plain;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class PersistenceClassesTest extends PHPStanTestCase
{
    #[Test]
    public function matchesConfiguredClassesTheirSubclassesAndImplementations(): void
    {
        $persistenceClasses = new PersistenceClasses(['\\'.EntityManagerInterface::class]);

        self::assertSame(EntityManagerInterface::class, $persistenceClasses->matching($this->classReflection(EntityManagerInterface::class)));
        self::assertSame(EntityManagerInterface::class, $persistenceClasses->matching($this->classReflection(AppEntityManager::class)));
        self::assertNull($persistenceClasses->matching($this->classReflection(ObjectManager::class)));
        self::assertNull($persistenceClasses->matching($this->classReflection(Plain::class)));
    }

    #[Test]
    public function theDoctrinePresetAddsItsClasses(): void
    {
        $persistenceClasses = new PersistenceClasses([], ['doctrine' => true]);

        self::assertNotNull($persistenceClasses->matching($this->classReflection(AppEntityManager::class)));
        self::assertNotNull($persistenceClasses->matching($this->classReflection(ObjectManager::class)));
        self::assertNull($persistenceClasses->matching($this->classReflection(Plain::class)));
    }

    #[Test]
    public function nothingMatchesWithoutConfigOrPresets(): void
    {
        self::assertNull((new PersistenceClasses([], ['doctrine' => false]))->matching($this->classReflection(AppEntityManager::class)));
    }

    /**
     * @param class-string $className
     */
    private function classReflection(string $className): ClassReflection
    {
        return self::createReflectionProvider()->getClass($className);
    }
}
