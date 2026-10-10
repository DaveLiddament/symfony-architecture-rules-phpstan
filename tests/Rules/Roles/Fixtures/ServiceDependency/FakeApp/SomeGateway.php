<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\Architecture\Attribute\QueueGateway;

#[QueueGateway]
final readonly class SomeGateway
{
}
