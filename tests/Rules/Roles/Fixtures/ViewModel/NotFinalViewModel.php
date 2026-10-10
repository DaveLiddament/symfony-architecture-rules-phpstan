<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModel;

use DaveLiddament\SymfonyArchitecture\Attribute\ViewModel;

#[ViewModel] // ERROR View model DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModel\NotFinalViewModel must be final and readonly.
readonly class NotFinalViewModel
{
}
