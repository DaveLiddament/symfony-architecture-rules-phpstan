<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ServiceDeclarationRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ServiceDeclarationRule>
 */
final class ServiceDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ServiceDeclarationRule();
    }

    #[Test]
    public function aFinalReadonlyServiceIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Service/GoodService.php');
    }

    #[Test]
    public function aServiceThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Service/NotFinalService.php');
    }

    #[Test]
    public function aServiceThatIsNotReadonlyIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Service/NotReadonlyService.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Service/PlainService.php');
    }
}
