<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Dto;

use DaveLiddament\SymfonyArchitecture\Attribute\Dto;

#[Dto] // ERROR Dto DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Dto\NotFinalDto must be final.
class NotFinalDto
{
}
