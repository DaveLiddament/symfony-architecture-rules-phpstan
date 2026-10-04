<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ViewModelDeclarationRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ViewModelDeclarationRule>
 */
final class ViewModelDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ViewModelDeclarationRule();
    }

    #[Test]
    public function aFinalReadonlyViewModelIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ViewModel/GoodViewModel.php');
    }

    #[Test]
    public function aViewModelThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ViewModel/NotFinalViewModel.php');
    }

    #[Test]
    public function aViewModelThatIsNotReadonlyIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ViewModel/NotReadonlyViewModel.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ViewModel/PlainViewModel.php');
    }
}
