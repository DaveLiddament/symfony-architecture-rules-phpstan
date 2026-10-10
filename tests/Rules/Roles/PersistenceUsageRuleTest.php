<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\PersistenceClasses;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\PersistenceUsageRule;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\Connection;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<PersistenceUsageRule>
 */
final class PersistenceUsageRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new PersistenceUsageRule(
            new RoleResolver([]),
            new PersistenceClasses([EntityManagerInterface::class, '\\'.Connection::class]),
        );
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Only a #[Repository] may hold a persistence class, but {0} holds {1}.';
    }

    #[Test]
    public function aRepositoryMayHoldPersistenceClasses(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/PersistenceUsage/GoodRepository.php');
    }

    #[Test]
    public function anythingElseHoldingAPersistenceClassOrAnImplementationIsReported(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/PersistenceUsage/GreedyService.php',
            __DIR__.'/Fixtures/PersistenceUsage/PlainHolder.php',
        );
    }

    #[Test]
    public function classesWithoutPersistenceClassPropertiesAreIgnored(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/PersistenceUsage/Unrelated.php',
            __DIR__.'/Fixtures/PersistenceUsage/FakeDoctrine/EntityManager.php',
        );
    }
}
