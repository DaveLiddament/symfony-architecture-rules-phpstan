<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Entity;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Entity\FakeOrm\OrmEntity;

#[OrmEntity] // ERROR Entity DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Entity\NotFinalOrmEntity must be final (or @final).
class NotFinalOrmEntity
{
}
