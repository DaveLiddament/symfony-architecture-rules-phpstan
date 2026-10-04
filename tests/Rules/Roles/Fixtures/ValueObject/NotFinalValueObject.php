<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObject;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

#[ValueObject] // ERROR Value object DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ValueObject\NotFinalValueObject must be final and readonly.
readonly class NotFinalValueObject
{
}
