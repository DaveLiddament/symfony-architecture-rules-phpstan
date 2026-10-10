<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\SharedApp\App\Shared;

final class UsedByPending // ERROR Only domain "Pending" uses Shared class DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\SharedApp\App\Shared\UsedByPending, so it may belong in that domain.
{
}
