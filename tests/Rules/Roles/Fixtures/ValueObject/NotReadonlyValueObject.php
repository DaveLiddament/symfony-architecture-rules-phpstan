<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObject;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

#[ValueObject] // ERROR Value object DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObject\NotReadonlyValueObject must be final and readonly.
final class NotReadonlyValueObject
{
}
