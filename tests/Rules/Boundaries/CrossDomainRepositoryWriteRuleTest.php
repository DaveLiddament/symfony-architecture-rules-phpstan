<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\CrossDomainRepositoryWriteRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<CrossDomainRepositoryWriteRule>
 */
final class CrossDomainRepositoryWriteRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new CrossDomainRepositoryWriteRule(ExportedFixtures::classifier(), new RoleResolver([]));
    }

    #[Test]
    public function theOwningDomainMayWrite(): void
    {
        $this->analyse(ExportedFixtures::filesIn('Walks/Exportable'), []);
    }

    #[Test]
    public function otherDomainsMayOnlyRead(): void
    {
        $this->assertIssuesReported(ExportedFixtures::file('Stats/UsesWalkRepository.php'));
    }

    #[Test]
    public function theNurseryMayOnlyRead(): void
    {
        $this->assertIssuesReported(ExportedFixtures::file('Nursery/NurseryUsesWalkRepository.php'));
    }
}
