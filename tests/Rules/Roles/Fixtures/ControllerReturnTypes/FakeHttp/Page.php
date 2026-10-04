<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp;

final readonly class Page
{
    public function __construct(
        public string $template,
        public object $viewModel,
    ) {
    }
}
