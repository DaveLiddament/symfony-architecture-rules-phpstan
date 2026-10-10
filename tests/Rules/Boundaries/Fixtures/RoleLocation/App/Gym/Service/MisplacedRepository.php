<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service;

use DaveLiddament\Architecture\Attribute\Repository;

#[Repository] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Service\MisplacedRepository|Repository|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym\Repository or DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\App\Gym
final class MisplacedRepository
{
}
