<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp;

class Response
{
    public function __construct(
        public string $content = "",
    ) {
    }
}
