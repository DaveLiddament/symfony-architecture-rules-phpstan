<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable;

use DaveLiddament\Architecture\Attribute\Exported;
use DaveLiddament\Architecture\Attribute\Serializer;

#[Exported, Serializer] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable\WalkSerializer has the serializer role, so it cannot be exported.
final class WalkSerializer
{
}
