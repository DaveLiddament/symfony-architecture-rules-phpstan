<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\SymfonyArchitecture\Attribute\QueueGateway;

#[QueueGateway]
final readonly class SomeGateway
{
}
