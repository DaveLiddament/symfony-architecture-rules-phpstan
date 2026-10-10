<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym;

use DaveLiddament\Architecture\Attribute\Controller;

#[Controller] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\BadController|Controller|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Controller
final class BadController
{
}
