<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

final class NoAttribute
{
    public string $anything = 'not a service, so nothing is demanded';

    public function __construct(
        public PlainCollaborator $plain = new PlainCollaborator(),
    ) {
    }
}
