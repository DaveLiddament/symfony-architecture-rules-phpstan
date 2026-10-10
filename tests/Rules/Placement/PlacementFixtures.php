<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Placement\CrossAreaReferenceCollector;
use DaveLiddament\PhpstanArchitectureRules\Placement\PlacedClassCollector;

/**
 * Each placement rule has its own small app under Fixtures/<app>/App, with
 * App\Shared, App\Pending and domains.
 */
final class PlacementFixtures
{
    private const string NAMESPACE = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures';

    /**
     * @return array{PlacedClassCollector, CrossAreaReferenceCollector}
     */
    public static function collectors(string $app): array
    {
        $appNamespace = self::NAMESPACE.'\\'.$app.'\\App';
        $classifier = new BoundaryClassifier($appNamespace, null, $appNamespace.'\\Shared', $appNamespace.'\\Pending', []);

        return [new PlacedClassCollector($classifier), new CrossAreaReferenceCollector($classifier)];
    }

    /**
     * Every file in the app.
     *
     * @return list<string>
     */
    public static function files(string $app): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(__DIR__.'/Fixtures/'.$app, \FilesystemIterator::SKIP_DOTS),
        );
        foreach ($iterator as $file) {
            if ($file instanceof \SplFileInfo && 'php' === $file->getExtension()) {
                $files[] = $file->getPathname();
            }
        }
        sort($files);

        return $files;
    }
}
