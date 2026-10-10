<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\Architecture\Attribute\Service;

#[Service]
final class BadService
{
    public string $apiKey; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$apiKey

    public int $limit; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$limit

    public bool $enabled; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$enabled

    public $untyped; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$untyped

    public iterable $anything; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$anything

    public PlainCollaborator $plain; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$plain

    public function __construct(
        public SomeDto $dto, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp\BadService::$dto
    ) {
    }
}
