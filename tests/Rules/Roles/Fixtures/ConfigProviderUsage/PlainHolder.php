<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage;

final class PlainHolder
{
    public TheConfig $config; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage\PlainHolder::$config

    /** @var list<TheConfig> */
    public array $configs; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage\PlainHolder::$configs

    /** @var iterable<TheConfig> */
    public iterable $lazyConfigs; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage\PlainHolder::$lazyConfigs

    public string $harmless;
}
