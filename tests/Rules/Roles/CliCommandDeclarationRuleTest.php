<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\CliCommandDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<CliCommandDeclarationRule>
 */
final class CliCommandDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new CliCommandDeclarationRule(new RoleResolver([]));
    }

    #[Test]
    public function aFinalCommandIsAcceptedWithoutBeingReadonly(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/CliCommand/GoodCommand.php');
    }

    #[Test]
    public function aCommandThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/CliCommand/NotFinalCommand.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/CliCommand/PlainCommand.php');
    }
}
