<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RepositoryDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<RepositoryDeclarationRule>
 */
final class RepositoryDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new RepositoryDeclarationRule(new RoleResolver([]));
    }

    #[Test]
    public function aFinalReadonlyRepositoryIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Repository/GoodRepository.php');
    }

    #[Test]
    public function aRepositoryThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Repository/NotFinalRepository.php');
    }

    #[Test]
    public function aRepositoryThatIsNotReadonlyIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Repository/NotReadonlyRepository.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Repository/PlainRepository.php');
    }
}
