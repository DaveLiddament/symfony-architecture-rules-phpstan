<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ConfigProviderUsage;

final class Unrelated
{
    public string $anything = 'no config providers here';

    public \stdClass $object;

    public SelfIterating $selfIterating;
}
