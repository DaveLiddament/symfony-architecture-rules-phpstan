<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModel;

use DaveLiddament\Architecture\Attribute\ViewModel;

#[ViewModel] // ERROR View model DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModel\NotReadonlyViewModel must be final and readonly.
final class NotReadonlyViewModel
{
}
