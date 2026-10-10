<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RepositoryDependencyRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<RepositoryDependencyRule>
 */
final class RepositoryDependencyRuleTest extends AbstractRuleTestCase
{
    private const string FAKE_DOCTRINE = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\FakeDoctrine';

    #[\Override]
    protected function getRule(): Rule
    {
        return new RepositoryDependencyRule(new RoleResolver([]), self::FAKE_DOCTRINE);
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Repository dependency {0} must come from '.self::FAKE_DOCTRINE.'.';
    }

    #[Test]
    public function doctrineDependenciesAreAllowed(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/RepositoryDependency/GoodRepository.php',
            __DIR__.'/Fixtures/RepositoryDependency/FakeDoctrine/EntityManager.php',
        );
    }

    #[Test]
    public function nonDoctrineDependenciesAreReported(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/RepositoryDependency/BadRepository.php',
            __DIR__.'/Fixtures/RepositoryDependency/SomeService.php',
            __DIR__.'/Fixtures/RepositoryDependency/PlainThing.php',
        );
    }

    #[Test]
    public function propertiesOfNonRepositoryClassesAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/RepositoryDependency/NoAttribute.php');
    }
}
