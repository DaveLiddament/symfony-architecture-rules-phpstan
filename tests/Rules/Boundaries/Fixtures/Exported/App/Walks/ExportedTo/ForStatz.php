<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\ExportedTo;

use DaveLiddament\Architecture\Attribute\Exported;
use DaveLiddament\Architecture\Attribute\Service;

#[Exported(to: ['Stats', 'Statz']), Service] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\ExportedTo\ForStatz is exported to domain "Statz", which does not exist.
final readonly class ForStatz
{
}
