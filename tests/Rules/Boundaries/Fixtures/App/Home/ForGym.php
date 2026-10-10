<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home;

use DaveLiddament\Architecture\Attribute\Exported;

#[Exported(to: ['Gym'])]
final class ForGym
{
}
