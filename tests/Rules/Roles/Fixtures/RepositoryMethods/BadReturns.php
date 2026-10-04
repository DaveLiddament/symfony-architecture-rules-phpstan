<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final class BadReturns
{
    public function getMaybe(): ?Thing // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadReturns::getMaybe() is a get, so its return type must not be nullable.
    {
        return null;
    }

    public function findAll(): array // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadReturns::findAll() is a find, so it must return a nullable, non-iterable value.
    {
        return [];
    }

    /**
     * @return list<Thing>|null
     */
    public function findList(): ?array // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadReturns::findList() is a find, so it must return a nullable, non-iterable value.
    {
        return null;
    }

    public function persistThing(Thing $thing): bool // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadReturns::persistThing() is a write, so it must return void.
    {
        return true;
    }

    public function updateName(Thing $thing): int // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadReturns::updateName() is a write, so it must return void.
    {
        return 1;
    }

    public function deleteOld(): self // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadReturns::deleteOld() is a write, so it must return void.
    {
        return $this;
    }

    /**
     * @return list<Thing>|null
     */
    public function hasResults(): ?array // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadReturns::hasResults() must not return a nullable iterable.
    {
        return null;
    }
}
