<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes;

use DaveLiddament\SymfonyArchitecture\Attribute\Controller;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\JsonResponse;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Page;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\RedirectResponse;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Response;

/**
 * Checked with only Response allowed: its subclasses are allowed too.
 */
#[Controller]
final class ResponseController
{
    public function response(): Response
    {
        return new Response('ok');
    }

    public function json(): JsonResponse
    {
        return new JsonResponse('ok');
    }

    public function jsonOrRedirect(): JsonResponse|RedirectResponse
    {
        return new RedirectResponse('/');
    }

    public function page(): Page // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\ResponseController|page
    {
        return new Page('template.html.twig', new \stdClass());
    }

    public function nullableResponse(): ?Response // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\ResponseController|nullableResponse
    {
        return null;
    }
}
