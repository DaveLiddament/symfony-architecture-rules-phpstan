<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final class BadNames
{
    public function fetchFor(int $id): ?Thing // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadNames::fetchFor() must start with find, get, persist, update, delete, has or is.
    {
        return null;
    }

    public function all(): array // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadNames::all() must start with find, get, persist, update, delete, has or is.
    {
        return [];
    }

    public function flush(): void // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadNames::flush() must start with find, get, persist, update, delete, has or is.
    {
    }

    public function remove(Thing $thing): void // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadNames::remove() must start with find, get, persist, update, delete, has or is.
    {
    }

    public function getaway(): string // ERROR Repository method DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods\BadNames::getaway() must start with find, get, persist, update, delete, has or is.
    {
        return 'a lowercase letter after the prefix is not a verb boundary';
    }
}
