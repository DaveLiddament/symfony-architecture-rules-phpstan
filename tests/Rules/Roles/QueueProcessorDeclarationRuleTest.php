<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\QueueProcessorDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<QueueProcessorDeclarationRule>
 */
final class QueueProcessorDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new QueueProcessorDeclarationRule(new RoleResolver([]));
    }

    #[Test]
    public function aFinalReadonlyQueueProcessorIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/QueueProcessor/GoodQueueProcessor.php');
    }

    #[Test]
    public function aQueueProcessorThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/QueueProcessor/NotFinalQueueProcessor.php');
    }

    #[Test]
    public function aQueueProcessorThatIsNotReadonlyIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/QueueProcessor/NotReadonlyQueueProcessor.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/QueueProcessor/PlainQueueProcessor.php');
    }
}
