<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes;

use DaveLiddament\Architecture\Attribute\Controller;
use Symfony\Component\HttpFoundation\Response;

#[Controller]
final class SymfonyResponseController
{
    public function show(): Response
    {
        return new Response();
    }

    public function list(): string // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\SymfonyResponseController|list
    {
        return '';
    }
}
