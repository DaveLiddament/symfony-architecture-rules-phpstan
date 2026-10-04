<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Service;

use DaveLiddament\SymfonyArchitecture\Attribute\Service;

#[Service] // ERROR Service DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\Service\NotFinalService must be final and readonly.
readonly class NotFinalService
{
}
