<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;

/**
 * The #[Exported] fixtures are a small app: App\Walks and App\Stats are
 * domains, alongside App\Pending.
 */
final class ExportedFixtures
{
    private const string APP = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App';

    public static function classifier(): BoundaryClassifier
    {
        return new BoundaryClassifier(self::APP, null, self::APP.'\Shared', self::APP.'\Pending', []);
    }

    public static function file(string $path): string
    {
        return __DIR__.'/Fixtures/Exported/App/'.$path;
    }

    /**
     * @return list<string>
     */
    public static function filesIn(string $directory): array
    {
        $files = glob(self::file($directory).'/*.php');

        return false === $files ? [] : $files;
    }
}
