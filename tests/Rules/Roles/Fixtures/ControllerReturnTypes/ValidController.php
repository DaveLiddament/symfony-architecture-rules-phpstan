<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes;

use DaveLiddament\Architecture\Attribute\Controller;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Page;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\JsonResponse;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\RedirectResponse;

#[Controller]
class ValidController
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function page(): Page
    {
        return new Page('template.html.twig', new \stdClass());
    }

    public function json(): JsonResponse
    {
        return new JsonResponse($this->name);
    }

    public function redirect(): RedirectResponse
    {
        return new RedirectResponse('/');
    }

    public function pageOrRedirect(): Page|RedirectResponse
    {
        return $this->redirect();
    }

    protected function protectedHelper(): string
    {
        return $this->name;
    }

    private function privateHelper(): void
    {
    }
}
