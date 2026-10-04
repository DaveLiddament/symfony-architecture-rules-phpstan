<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\SymfonyArchitecture\Attribute\Service;

#[Service]
final class BadService
{
    public string $apiKey; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$apiKey

    public int $limit; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$limit

    public bool $enabled; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$enabled

    public $untyped; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$untyped

    public iterable $anything; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$anything

    public PlainCollaborator $plain; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$plain

    public function __construct(
        public SomeDto $dto, // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$dto
    ) {
    }
}
