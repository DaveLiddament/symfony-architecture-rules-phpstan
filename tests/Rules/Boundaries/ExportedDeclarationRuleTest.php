<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\ExportedDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ExportedDeclarationRule>
 */
final class ExportedDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ExportedDeclarationRule($this->createReflectionProvider(), new RoleResolver([], ['symfony' => true]));
    }

    #[Test]
    public function exportableClassesReportNoErrors(): void
    {
        $this->analyse([
            ...ExportedFixtures::filesIn('Walks/Exportable'),
            ...ExportedFixtures::filesIn('Walks/ExportedTo'),
        ], []);
    }

    #[Test]
    public function traitsRolesThatAreNeverExportedAndEmptyToListsAreReported(): void
    {
        $this->assertIssuesReported(...ExportedFixtures::filesIn('Walks/NotExportable'));
    }
}
