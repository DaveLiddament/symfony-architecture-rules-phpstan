<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RepositoryMethodRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<RepositoryMethodRule>
 */
final class RepositoryMethodRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new RepositoryMethodRule(new RoleResolver([]));
    }

    #[Test]
    public function theFullVocabularyWithMatchingReturnTypesReportsNoErrors(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/RepositoryMethods/GoodRepository.php',
            __DIR__.'/Fixtures/RepositoryMethods/Thing.php',
        );
    }

    #[Test]
    public function methodsOutsideTheVocabularyAreReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/RepositoryMethods/BadNames.php');
    }

    #[Test]
    public function returnContractViolationsAreReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/RepositoryMethods/BadReturns.php');
    }

    #[Test]
    public function methodsOfNonRepositoryClassesAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/RepositoryMethods/NotARepository.php');
    }
}
