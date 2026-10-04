<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;

return static function (Configuration $config): Configuration {
    return $config
        // PHPStan ships as a phar, so composer-unused cannot map its symbols
        // (PHPStan\*, PhpParser\*) back to the package.
        ->addNamedFilter(NamedFilter::fromString('phpstan/phpstan'));
};
