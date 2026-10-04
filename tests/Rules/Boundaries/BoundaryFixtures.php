<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\BoundaryClassifier;

/**
 * The fixtures are a small app: App\Gym and App\Home are domains, alongside
 * App\Shared, App\Nursery, App\Tests (ignored) and Lib.
 */
final class BoundaryFixtures
{
    private const string NAMESPACE = 'DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures';
    private const string LIB = self::NAMESPACE.'\Lib';
    private const string SHARED = self::NAMESPACE.'\App\Shared';
    private const string NURSERY = self::NAMESPACE.'\App\Nursery';

    /**
     * Files that respect every boundary rule.
     */
    private const array ALLOWED_FILES = [
        'Lib/Clock.php',
        'Lib/GoodUtil.php',
        'App/Kernel.php',
        'App/Gym/GymApi.php',
        'App/Gym/Repository/GymRepository.php',
        'App/Home/HomeApi.php',
        'App/Home/Internal/Secret.php',
        'App/Shared/User.php',
        'App/Shared/Money.php',
        'App/Nursery/Helper.php',
        'App/Nursery/NewFeature.php',
        'App/Tests/GymRepositoryTestHelper.php',
    ];

    public static function classifier(
        ?string $libNamespace = self::LIB,
        ?string $sharedNamespace = self::SHARED,
        ?string $nurseryNamespace = self::NURSERY,
    ): BoundaryClassifier {
        return new BoundaryClassifier(
            self::NAMESPACE.'\App',
            $libNamespace,
            $sharedNamespace,
            $nurseryNamespace,
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
