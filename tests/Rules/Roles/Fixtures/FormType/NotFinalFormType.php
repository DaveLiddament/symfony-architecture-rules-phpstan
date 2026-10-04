<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\FormType;

use DaveLiddament\SymfonyArchitecture\Attribute\FormType;

#[FormType] // ERROR Form type DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\FormType\NotFinalFormType must be final.
class NotFinalFormType
{
}
