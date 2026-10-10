<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable;

use DaveLiddament\Architecture\Attribute\Exported;
use Symfony\Component\Form\AbstractType;

#[Exported] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable\WalkFormType has the formType role, so it cannot be exported.
final class WalkFormType extends AbstractType
{
}
