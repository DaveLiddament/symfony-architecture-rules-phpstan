<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ControllerMethodReturnTypeRule;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\JsonResponse;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Page;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\RedirectResponse;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ControllerReturnTypes\FakeHttp\Response;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ControllerMethodReturnTypeRule>
 */
final class ControllerMethodReturnTypeRuleTest extends AbstractRuleTestCase
{
    private const array PAGE_JSON_OR_REDIRECT = [Page::class, JsonResponse::class, RedirectResponse::class];

    /** @var list<string> */
    private array $allowedReturnTypes = self::PAGE_JSON_OR_REDIRECT;

    #[\Override]
    protected function getRule(): Rule
    {
        return new ControllerMethodReturnTypeRule(self::createReflectionProvider(), $this->allowedReturnTypes);
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        $allowed = self::PAGE_JSON_OR_REDIRECT === $this->allowedReturnTypes
            ? 'Page, JsonResponse, RedirectResponse, or a union of these'
            : 'Response';

        return 'Public method {0}::{1}() on a #[Controller] must declare a return type of '.$allowed.'.';
    }

    #[Test]
    public function allowedReturnTypesAndUnionsOfThemAreAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ControllerReturnTypes/ValidController.php');
    }

    #[Test]
    public function otherNullableOrMissingReturnTypesAreReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ControllerReturnTypes/InvalidController.php');
    }

    #[Test]
    public function subclassesOfAnAllowedReturnTypeAreAccepted(): void
    {
        $this->allowedReturnTypes = [Response::class];

        $this->assertIssuesReported(__DIR__.'/Fixtures/ControllerReturnTypes/ResponseController.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ControllerReturnTypes/NotAController.php');
    }
}
