<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final class BadRepository
{
    public string $table; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$table

    public $untyped; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$untyped

    public PlainThing $thing; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$thing

    public function __construct(
        public SomeService $service, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$service
    ) {
    }
}
