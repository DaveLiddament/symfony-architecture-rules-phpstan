<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries;

enum AreaType
{
    /** Application-agnostic code (e.g. Lib\*). */
    case Lib;

    /** Application code used throughout the app (e.g. App\Shared\*). */
    case Shared;

    /** Application code whose domain is not known yet (e.g. App\Nursery\*). */
    case Nursery;

    /** Application code in a domain (e.g. App\Registration\*). */
    case Domain;

    /** Classes directly in the app namespace (e.g. App\Kernel): framework glue. */
    case AppRoot;

    /** Code the boundary rules ignore (e.g. App\Tests\*). */
    case Ignored;

    /** Everything else (vendor code, the global namespace, ...). */
    case External;
}
