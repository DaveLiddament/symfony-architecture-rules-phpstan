<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ConfigProvider;

#[ConfigProvider]
final class BadProperties
{
    public $untyped; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties\BadProperties::$untyped

    public array $bare; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties\BadProperties::$bare

    /** @var array<string, string> */
    public array $map; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties\BadProperties::$map

    public \DateTimeImmutable $when; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties\BadProperties::$when

    public Level $level; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties\BadProperties::$level

    public \stdClass $anyObject; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties\BadProperties::$anyObject

    public string $fine;
}
