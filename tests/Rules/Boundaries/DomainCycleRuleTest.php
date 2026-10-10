<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\DomainDependencyCollector;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\DomainCycleRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * The fixtures are a small app under Fixtures/Cycles/App: Registration and
 * Walks depend on each other, Billing, Stats and Pending form a cycle of
 * three, and Audit depends on others without being part of a cycle.
 *
 * @extends AbstractRuleTestCase<DomainCycleRule>
 */
final class DomainCycleRuleTest extends AbstractRuleTestCase
{
    private const string APP = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App';

    #[\Override]
    protected function getRule(): Rule
    {
        return new DomainCycleRule();
    }

    #[\Override]
    protected function getCollectors(): array
    {
        return [new DomainDependencyCollector(
            new BoundaryClassifier(self::APP, null, self::APP.'\Shared', self::APP.'\Pending', []),
        )];
    }

    #[Test]
    public function eachCycleIsReportedOnceWithAnExampleForEachStep(): void
    {
        $this->assertIssuesReported(...self::files());
    }

    #[Test]
    public function dependenciesWithoutACycleAreNotReported(): void
    {
        $this->analyse([
            __DIR__.'/Fixtures/Cycles/App/Audit/AuditLog.php',
            __DIR__.'/Fixtures/Cycles/App/Registration/Member.php',
            __DIR__.'/Fixtures/Cycles/App/Walks/WalkPlanner.php',
        ], []);
    }

    /**
     * @return list<string>
     */
    private static function files(): array
    {
        $files = glob(__DIR__.'/Fixtures/Cycles/App/*/*.php');

        return false === $files ? [] : $files;
    }
}
