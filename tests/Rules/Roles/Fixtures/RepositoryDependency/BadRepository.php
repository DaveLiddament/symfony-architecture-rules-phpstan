<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryDependency;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final class BadRepository
{
    public string $table; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$table

    public $untyped; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$untyped

    public PlainThing $thing; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$thing

    public function __construct(
        public SomeService $service, // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$service
    ) {
    }
}
