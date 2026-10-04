<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Dto;

use DaveLiddament\SymfonyArchitecture\Attribute\Dto;

#[Dto] // ERROR Dto DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Dto\NotFinalDto must be final.
class NotFinalDto
{
}
