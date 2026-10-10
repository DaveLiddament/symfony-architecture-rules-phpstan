<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\AliasAttribute;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\AliasAttributed;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\AttributedService;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\BaseEntity;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\ChildEntity;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\DoctrineEntity;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\EntityInterface;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\GrandchildEntity;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\ImplementsEntityInterface;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\Plain;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\ServiceAndDto;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\SubclassOfAttributedService;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\SymfonyCommand;
use DaveLiddament\PhpstanArchitectureRules\Tests\Roles\Fixtures\SymfonyMessageHandler;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class RoleResolverTest extends PHPStanTestCase
{
    #[Test]
    public function aClassPlaysTheRoleOfItsAttribute(): void
    {
        $resolver = new RoleResolver([]);

        self::assertTrue($resolver->plays($this->classReflection(AttributedService::class), Role::Service));
        self::assertFalse($resolver->plays($this->classReflection(AttributedService::class), Role::Dto));
        self::assertFalse($resolver->plays($this->classReflection(Plain::class), Role::Service));
    }

    #[Test]
    public function aRoleAttributeIsNotInherited(): void
    {
        $resolver = new RoleResolver([]);

        self::assertFalse($resolver->plays($this->classReflection(SubclassOfAttributedService::class), Role::Service));
    }

    #[Test]
    public function anAliasAttributeGivesTheRole(): void
    {
        $resolver = new RoleResolver(['entity' => ['attributes' => [AliasAttribute::class]]]);

        self::assertTrue($resolver->plays($this->classReflection(AliasAttributed::class), Role::Entity));
        self::assertFalse($resolver->plays($this->classReflection(AliasAttributed::class), Role::Service));
    }

    #[Test]
    public function extendingAnAliasClassGivesTheRole(): void
    {
        $resolver = new RoleResolver(['entity' => ['extends' => ['\\'.BaseEntity::class]]]);

        self::assertTrue($resolver->plays($this->classReflection(ChildEntity::class), Role::Entity));
        self::assertTrue($resolver->plays($this->classReflection(GrandchildEntity::class), Role::Entity));
        self::assertFalse($resolver->plays($this->classReflection(BaseEntity::class), Role::Entity));
    }

    #[Test]
    public function implementingAnAliasInterfaceGivesTheRole(): void
    {
        $resolver = new RoleResolver(['entity' => ['implements' => [EntityInterface::class]]]);

        self::assertTrue($resolver->plays($this->classReflection(ImplementsEntityInterface::class), Role::Entity));
        self::assertFalse($resolver->plays($this->classReflection(Plain::class), Role::Entity));
    }

    #[Test]
    public function aliasesNamingMissingClassesNeverMatch(): void
    {
        $resolver = new RoleResolver(['controller' => [
            'attributes' => ['Missing\Attribute'],
            'extends' => ['Missing\BaseClass'],
            'implements' => ['Missing\SomeInterface'],
        ]]);

        self::assertFalse($resolver->plays($this->classReflection(Plain::class), Role::Controller));
    }

    #[Test]
    public function rolesOfListsEveryRoleTheClassPlays(): void
    {
        $resolver = new RoleResolver(['entity' => ['extends' => [BaseEntity::class]]]);

        self::assertSame([Role::Dto, Role::Service], $resolver->rolesOf($this->classReflection(ServiceAndDto::class)));
        self::assertSame([Role::Entity], $resolver->rolesOf($this->classReflection(ChildEntity::class)));
        self::assertSame([], $resolver->rolesOf($this->classReflection(Plain::class)));
    }

    #[Test]
    public function hasAnyRoleCountsAttributesAndAliases(): void
    {
        $resolver = new RoleResolver(['entity' => ['attributes' => [AliasAttribute::class]]]);

        self::assertTrue($resolver->hasAnyRole($this->classReflection(AttributedService::class)));
        self::assertTrue($resolver->hasAnyRole($this->classReflection(AliasAttributed::class)));
        self::assertFalse($resolver->hasAnyRole($this->classReflection(Plain::class)));
    }

    #[Test]
    public function enabledFrameworkPresetsAddTheirAliases(): void
    {
        $resolver = new RoleResolver([], ['doctrine' => true, 'symfony' => true]);

        self::assertTrue($resolver->plays($this->classReflection(DoctrineEntity::class), Role::Entity));
        self::assertTrue($resolver->plays($this->classReflection(SymfonyCommand::class), Role::CliCommand));
        self::assertTrue($resolver->plays($this->classReflection(SymfonyMessageHandler::class), Role::QueueProcessor));
    }

    #[Test]
    public function disabledFrameworkPresetsAddNothing(): void
    {
        $resolver = new RoleResolver([], ['doctrine' => false, 'symfony' => false]);

        self::assertFalse($resolver->plays($this->classReflection(DoctrineEntity::class), Role::Entity));
        self::assertFalse($resolver->plays($this->classReflection(SymfonyCommand::class), Role::CliCommand));
        self::assertFalse($resolver->plays($this->classReflection(SymfonyMessageHandler::class), Role::QueueProcessor));
    }

    #[Test]
    public function presetAliasesAreAddedToConfiguredOnes(): void
    {
        $resolver = new RoleResolver(['entity' => ['extends' => [BaseEntity::class]]], ['doctrine' => true]);

        self::assertTrue($resolver->plays($this->classReflection(DoctrineEntity::class), Role::Entity));
        self::assertTrue($resolver->plays($this->classReflection(ChildEntity::class), Role::Entity));
    }

    #[Test]
    public function anUnknownRoleIsRejected(): void
    {
        $this->expectException(\ValueError::class);

        new RoleResolver(['servise' => ['attributes' => [AliasAttribute::class]]]);
    }

    /**
     * @param class-string $className
     */
    private function classReflection(string $className): ClassReflection
    {
        return self::createReflectionProvider()->getClass($className);
    }
}
