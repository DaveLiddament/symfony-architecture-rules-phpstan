<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Boundaries;

/**
 * Works out which architectural area a namespace or class belongs to.
 *
 * Any namespace directly under the app namespace that is not Shared, the
 * Nursery or ignored is a domain. A null Lib, Shared or Nursery namespace
 * means that area does not exist, so the rules that guard it never fire.
 */
final readonly class BoundaryClassifier
{
    private string $appNamespace;
    private ?string $libNamespace;
    private ?string $sharedNamespace;
    private ?string $nurseryNamespace;

    /** @var list<string> */
    private array $ignoredNamespaces;

    /**
     * @param list<string> $ignoredNamespaces
     */
    public function __construct(
        string $appNamespace,
        ?string $libNamespace,
        ?string $sharedNamespace,
        ?string $nurseryNamespace,
        array $ignoredNamespaces,
    ) {
        $this->appNamespace = self::normalise($appNamespace);
        $this->libNamespace = self::normaliseNullable($libNamespace);
        $this->sharedNamespace = self::normaliseNullable($sharedNamespace);
        $this->nurseryNamespace = self::normaliseNullable($nurseryNamespace);
        $this->ignoredNamespaces = array_map(self::normalise(...), $ignoredNamespaces);
    }

    public function classifyClass(string $className): Area
    {
        $lastSeparator = strrpos($className, '\\');

        return $this->classifyNamespace(false === $lastSeparator ? null : substr($className, 0, $lastSeparator));
    }

    public function classifyNamespace(?string $namespace): Area
    {
        if (null === $namespace || '' === $namespace) {
            return Area::of(AreaType::External);
        }

        foreach ($this->ignoredNamespaces as $ignoredNamespace) {
            if (self::isWithin($namespace, $ignoredNamespace)) {
                return Area::of(AreaType::Ignored);
            }
        }

        if (self::isWithin($namespace, $this->libNamespace)) {
            return Area::of(AreaType::Lib);
        }

        if (self::isWithin($namespace, $this->sharedNamespace)) {
            return Area::of(AreaType::Shared);
        }

        if (self::isWithin($namespace, $this->nurseryNamespace)) {
            return Area::of(AreaType::Nursery);
        }

        if ($namespace === $this->appNamespace) {
            return Area::of(AreaType::AppRoot);
        }

        if (self::isWithin($namespace, $this->appNamespace)) {
            return Area::domain(explode('\\', substr($namespace, strlen($this->appNamespace) + 1))[0]);
        }

        return Area::of(AreaType::External);
    }

    /**
     * The namespace an area is rooted at, e.g. "App\Registration" for the
     * Registration domain. Null for areas without a root (Lib, external
     * code, ...).
     */
    public function getAreaNamespace(Area $area): ?string
    {
        return match ($area->type) {
            AreaType::Shared => $this->sharedNamespace,
            AreaType::Nursery => $this->nurseryNamespace,
            AreaType::Domain => $this->appNamespace.'\\'.$area->domain,
            default => null,
        };
    }

    private static function isWithin(string $namespace, ?string $parent): bool
    {
        if (null === $parent) {
            return false;
        }

        return $namespace === $parent || str_starts_with($namespace, $parent.'\\');
    }

    private static function normalise(string $namespace): string
    {
        return trim($namespace, '\\');
    }

    private static function normaliseNullable(?string $namespace): ?string
    {
        if (null === $namespace) {
            return null;
        }

        $namespace = self::normalise($namespace);

        return '' === $namespace ? null : $namespace;
    }
}
