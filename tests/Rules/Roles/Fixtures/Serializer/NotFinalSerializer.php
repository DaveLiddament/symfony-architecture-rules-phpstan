<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Serializer;

use DaveLiddament\SymfonyArchitecture\Attribute\Serializer;

#[Serializer] // ERROR Serializer DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Serializer\NotFinalSerializer must be final.
class NotFinalSerializer
{
}
