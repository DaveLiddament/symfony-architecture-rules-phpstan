<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Response;

final class NotAController
{
    public function noReturnType()
    {
        return 'anything goes';
    }

    public function response(): Response
    {
        return new Response('ok');
    }

    public function scalar(): string
    {
        return 'not restricted';
    }
}
