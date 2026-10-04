<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

#[ValueObject]
final class BadProperties
{
    public $untyped; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$untyped

    public array $bare; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$bare

    /** @var array<string, int> */
    public array $map; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$map

    public \DateTime $mutable; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$mutable

    public \DateTimeInterface $maybeMutable; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$maybeMutable

    public \stdClass $anyObject; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$anyObject

    public iterable $lazy; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$lazy

    public string $fine;

    public function __construct(
        public \DateTime $promoted, // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$promoted
    ) {
    }
}
