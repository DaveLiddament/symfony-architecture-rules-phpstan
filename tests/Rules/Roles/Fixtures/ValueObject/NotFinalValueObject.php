<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObject;

use DaveLiddament\Architecture\Attribute\ValueObject;

#[ValueObject] // ERROR Value object DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObject\NotFinalValueObject must be final and readonly.
readonly class NotFinalValueObject
{
}
