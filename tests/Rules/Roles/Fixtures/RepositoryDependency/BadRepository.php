<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency;

use DaveLiddament\Architecture\Attribute\Repository;

#[Repository]
final class BadRepository
{
    public string $table; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$table

    public $untyped; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$untyped

    public PlainThing $thing; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$thing

    /** @var array<string, Walk> */
    public array $walksByName; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$walksByName

    /** @var list<PlainThing> */
    public array $things; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$things

    /** @var list<string> */
    public array $names; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$names

    public function __construct(
        public SomeService $service, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\BadRepository::$service
    ) {
    }
}
