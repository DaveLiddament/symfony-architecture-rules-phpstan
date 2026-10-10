<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;

/**
 * The fixtures are a small app: App\Gym, App\Home and App\Stats are domains, alongside
 * App\Shared, App\Pending, App\Tests (ignored) and Lib.
 */
final class BoundaryFixtures
{
    private const string NAMESPACE = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures';
    private const string LIB = self::NAMESPACE.'\Lib';
    private const string SHARED = self::NAMESPACE.'\App\Shared';
    private const string PENDING = self::NAMESPACE.'\App\Pending';

    /**
     * Files that respect every boundary rule.
     */
    private const array ALLOWED_FILES = [
        'Lib/Clock.php',
        'Lib/GoodUtil.php',
        'App/Kernel.php',
        'App/Gym/GymApi.php',
        'App/Gym/Repository/GymRepository.php',
        'App/Home/ForGym.php',
        'App/Home/ForStats.php',
        'App/Home/HomeApi.php',
        'App/Home/HomeHelper.php',
        'App/Home/Internal/Secret.php',
        'App/Stats/StatsApi.php',
        'App/Shared/User.php',
        'App/Shared/Money.php',
        'App/Pending/Helper.php',
        'App/Pending/NewFeature.php',
        'App/Pending/PendingApi.php',
        'App/Tests/GymRepositoryTestHelper.php',
    ];

    public static function classifier(
        ?string $libNamespace = self::LIB,
        ?string $sharedNamespace = self::SHARED,
        ?string $pendingNamespace = self::PENDING,
    ): BoundaryClassifier {
        return new BoundaryClassifier(
            self::NAMESPACE.'\App',
            $libNamespace,
            $sharedNamespace,
            $pendingNamespace,
            [self::NAMESPACE.'\App\Tests'],
        );
    }

    public static function file(string $path): string
    {
        return __DIR__.'/Fixtures/'.$path;
    }

    /**
     * @return list<string>
     */
    public static function allowedFiles(): array
    {
        return array_map(self::file(...), self::ALLOWED_FILES);
    }
}
