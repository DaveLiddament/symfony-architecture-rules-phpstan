<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryMethods;

final class NotARepository
{
    public function flush(): void
    {
    }

    public function anythingGoes(): ?array
    {
        return null;
    }
}
