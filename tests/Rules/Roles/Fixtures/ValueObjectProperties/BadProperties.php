<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

#[ValueObject]
final class BadProperties
{
    public $untyped; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$untyped

    public array $bare; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$bare

    /** @var array<string, int> */
    public array $map; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$map

    public \DateTime $mutable; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$mutable

    public \DateTimeInterface $maybeMutable; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$maybeMutable

    public \stdClass $anyObject; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$anyObject

    public iterable $lazy; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$lazy

    public string $fine;

    public function __construct(
        public \DateTime $promoted, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties\BadProperties::$promoted
    ) {
    }
}
