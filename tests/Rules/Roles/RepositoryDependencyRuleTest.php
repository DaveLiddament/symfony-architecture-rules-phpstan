<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\PersistenceClasses;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RepositoryDependencyRule;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\FakeDoctrine\EntityManager;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<RepositoryDependencyRule>
 */
final class RepositoryDependencyRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new RepositoryDependencyRule(new RoleResolver([]), new PersistenceClasses([EntityManager::class]));
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Repository dependency {0} must be a persistence class, another repository, or a list of entities or value objects.';
    }

    #[Test]
    public function persistenceClassesRepositoriesAndListsOfEntitiesOrValueObjectsAreAllowed(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/RepositoryDependency/GoodRepository.php',
            __DIR__.'/Fixtures/RepositoryDependency/FakeDoctrine/EntityManager.php',
        );
    }

    #[Test]
    public function anythingElseIsReported(): void
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
