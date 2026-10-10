<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Placement;

use PHPStan\Node\CollectedDataNode;

/**
 * The collected placement data for the whole app: where each class lives,
 * which domains use it and which domains it uses. Domain names include
 * Pending, which acts as a domain. Class names are matched
 * case-insensitively, as PHP does.
 */
final readonly class PlacementData
{
    /**
     * @param list<PlacedClass> $classes
     * @param array<string, true> $domains domain => true
     * @param array<string, array<string, true>> $usedByDomains lowercased class => domain => true
     * @param array<string, true> $usedByShared lowercased class => true
     * @param array<string, array<string, true>> $usesDomains lowercased class => domain => true
     */
    private function __construct(
        public array $classes,
        private array $domains,
        private array $usedByDomains,
        private array $usedByShared,
        private array $usesDomains,
    ) {
    }

    public static function fromNode(CollectedDataNode $node): self
    {
        $classes = [];
        $domains = [];
        foreach ($node->get(PlacedClassCollector::class) as $file => $entries) {
            foreach ($entries as $entry) {
                $classes[] = new PlacedClass(
                    $entry['class'],
                    $entry['area'],
                    $entry['domain'],
                    $entry['exported'],
                    $entry['exportedTo'],
                    $file,
                    $entry['line'],
                );
                if (null !== $entry['domain']) {
                    $domains[$entry['domain']] = true;
                }
            }
        }

        $usedByDomains = [];
        $usedByShared = [];
        $usesDomains = [];
        foreach ($node->get(CrossAreaReferenceCollector::class) as $entries) {
            foreach ($entries as $entry) {
                $target = strtolower($entry['targetClass']);
                if (null === $entry['sourceDomain']) {
                    $usedByShared[$target] = true;
                } else {
                    $usedByDomains[$target][$entry['sourceDomain']] = true;
                }

                if (null !== $entry['sourceClass'] && null !== $entry['targetDomain']) {
                    $usesDomains[strtolower($entry['sourceClass'])][$entry['targetDomain']] = true;
                }
            }
        }

        return new self($classes, $domains, $usedByDomains, $usedByShared, $usesDomains);
    }

    public function domainExists(string $domain): bool
    {
        return isset($this->domains[$domain]);
    }

    /**
     * The domains, other than its own, whose code uses the class.
     *
     * @return list<string>
     */
    public function domainsUsing(PlacedClass $class): array
    {
        return self::sortedKeys($this->usedByDomains[strtolower($class->name)] ?? []);
    }

    /**
     * Whether another Shared class uses the class.
     */
    public function isUsedByShared(PlacedClass $class): bool
    {
        return isset($this->usedByShared[strtolower($class->name)]);
    }

    /**
     * The domains, other than its own, that the class uses.
     *
     * @return list<string>
     */
    public function domainsUsedBy(PlacedClass $class): array
    {
        return self::sortedKeys($this->usesDomains[strtolower($class->name)] ?? []);
    }

    /**
     * @param array<string, true> $set
     *
     * @return list<string>
     */
    private static function sortedKeys(array $set): array
    {
        $keys = array_map(strval(...), array_keys($set));
        sort($keys);

        return $keys;
    }
}
