<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable;

use DaveLiddament\Architecture\Attribute\ConfigProvider;
use DaveLiddament\Architecture\Attribute\Exported;

#[Exported, ConfigProvider] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable\WalkConfig has the configProvider role, so it cannot be exported.
final readonly class WalkConfig
{
}
