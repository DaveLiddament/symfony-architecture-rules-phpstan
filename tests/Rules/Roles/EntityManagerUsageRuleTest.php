<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\EntityManagerUsageRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage\FakeDoctrine\EntityManagerInterface;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<EntityManagerUsageRule>
 */
final class EntityManagerUsageRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new EntityManagerUsageRule(EntityManagerInterface::class);
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'The entity manager may only be held by a #[Repository], but {0} holds it.';
    }

    #[Test]
    public function aRepositoryMayHoldTheEntityManager(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/EntityManagerUsage/GoodRepository.php');
    }

    #[Test]
    public function anythingElseHoldingTheEntityManagerOrAnImplementationIsReported(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/EntityManagerUsage/GreedyService.php',
            __DIR__.'/Fixtures/EntityManagerUsage/PlainHolder.php',
        );
    }

    #[Test]
    public function classesWithoutEntityManagerPropertiesAreIgnored(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/EntityManagerUsage/Unrelated.php',
            __DIR__.'/Fixtures/EntityManagerUsage/FakeDoctrine/EntityManager.php',
        );
    }
}
