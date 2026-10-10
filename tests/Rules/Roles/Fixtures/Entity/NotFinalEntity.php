<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Entity;

use DaveLiddament\Architecture\Attribute\Entity;

#[Entity] // ERROR Entity DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Entity\NotFinalEntity must be final (or @final).
class NotFinalEntity
{
}
