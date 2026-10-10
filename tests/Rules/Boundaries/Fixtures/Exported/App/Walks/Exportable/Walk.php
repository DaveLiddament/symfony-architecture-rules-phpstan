<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable;

use DaveLiddament\Architecture\Attribute\Entity;
use DaveLiddament\Architecture\Attribute\Exported;

#[Exported(to: ['Stats'])]
#[Entity]
final class Walk
{
}
