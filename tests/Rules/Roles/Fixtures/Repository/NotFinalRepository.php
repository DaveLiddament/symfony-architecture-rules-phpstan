<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Repository;

use DaveLiddament\Architecture\Attribute\Repository;

#[Repository] // ERROR Repository DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Repository\NotFinalRepository must be final and readonly.
readonly class NotFinalRepository
{
}
