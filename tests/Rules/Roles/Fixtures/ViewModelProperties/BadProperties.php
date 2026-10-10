<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties;

use DaveLiddament\Architecture\Attribute\ViewModel;

#[ViewModel]
final class BadProperties
{
    public $untyped; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties\BadProperties::$untyped

    public array $bare; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties\BadProperties::$bare

    /** @var array<string, int> */
    public array $map; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties\BadProperties::$map

    public \DateTime $mutable; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties\BadProperties::$mutable

    public \stdClass $anyObject; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties\BadProperties::$anyObject

    public SomeValue $value; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties\BadProperties::$value

    public string $fine;

    public function __construct(
        public \DateTime $promoted, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties\BadProperties::$promoted
    ) {
    }
}
