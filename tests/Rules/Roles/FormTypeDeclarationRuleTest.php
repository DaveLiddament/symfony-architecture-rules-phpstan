<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\FormTypeDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * Form types are recognised through the Symfony preset (extends AbstractType).
 *
 * @extends AbstractRuleTestCase<FormTypeDeclarationRule>
 */
final class FormTypeDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new FormTypeDeclarationRule(new RoleResolver([], ['symfony' => true]));
    }

    #[Test]
    public function aFinalFormTypeIsAcceptedWithoutBeingReadonly(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/FormType/GoodFormType.php');
    }

    #[Test]
    public function aFormTypeThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/FormType/NotFinalFormType.php');
    }

    #[Test]
    public function classesThatAreNotFormTypesAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/FormType/PlainFormType.php');
    }
}
