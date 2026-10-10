<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\DomainExportCollector;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\ExportedToUnknownDomainRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ExportedToUnknownDomainRule>
 */
final class ExportedToUnknownDomainRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ExportedToUnknownDomainRule();
    }

    #[\Override]
    protected function getCollectors(): array
    {
        return [new DomainExportCollector(ExportedFixtures::classifier())];
    }

    #[Test]
    public function domainsInToMustContainAnAnalysedClass(): void
    {
        $this->assertIssuesReported(
            ExportedFixtures::file('Stats/StatsReport.php'),
            ExportedFixtures::file('Nursery/NurseryHelper.php'),
            ...ExportedFixtures::filesIn('Walks/Exportable'),
            ...ExportedFixtures::filesIn('Walks/ExportedTo'),
        );
    }
}
