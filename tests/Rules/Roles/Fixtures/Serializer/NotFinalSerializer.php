<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Serializer;

use DaveLiddament\SymfonyArchitecture\Attribute\Serializer;

#[Serializer] // ERROR Serializer DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Serializer\NotFinalSerializer must be final.
class NotFinalSerializer
{
}
