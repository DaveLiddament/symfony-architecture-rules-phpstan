<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency;

final class NoAttribute
{
    public string $anything = 'not a repository, so nothing is demanded';
}
