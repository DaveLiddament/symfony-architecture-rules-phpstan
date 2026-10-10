<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes;

use DaveLiddament\Architecture\Attribute\Controller;

#[Controller]
final class UncheckedController
{
    public function list(): string
    {
        return '';
    }
}
