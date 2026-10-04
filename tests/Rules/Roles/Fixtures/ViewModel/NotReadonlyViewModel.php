<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ViewModel;

use DaveLiddament\SymfonyArchitecture\Attribute\ViewModel;

#[ViewModel] // ERROR View model DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ViewModel\NotReadonlyViewModel must be final and readonly.
final class NotReadonlyViewModel
{
}
