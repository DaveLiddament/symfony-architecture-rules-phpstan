<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes;

use DaveLiddament\SymfonyArchitecture\Attribute\Controller;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Page;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Response;

#[Controller]
final class InvalidController
{
    public function missingReturnType() // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\InvalidController|missingReturnType
    {
        return new Page('template.html.twig', new \stdClass());
    }

    public function bareResponse(): Response // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\InvalidController|bareResponse
    {
        return new Response('ok');
    }

    public function nullablePage(): ?Page // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\InvalidController|nullablePage
    {
        return null;
    }

    public function scalar(): string // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\InvalidController|scalar
    {
        return 'nope';
    }

    public function returnsNothing(): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\InvalidController|returnsNothing
    {
    }

    public function pageOrResponse(): Page|Response // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\InvalidController|pageOrResponse
    {
        return new Response('ok');
    }
}
