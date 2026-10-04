<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Config;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\DomainInternalRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\LibIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\NurseryIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\SharedIsolationRule;
use PHPStan\DependencyInjection\Container;

/**
 * The rules in each config group, sorted by class name.
 */
final class RuleSets
{
    public const array BOUNDARIES = [
        DomainInternalRule::class,
        LibIsolationRule::class,
        NurseryIsolationRule::class,
        SharedIsolationRule::class,
    ];

    /**
     * The rules from $ruleSet that PHPStan will run with the container's config.
     *
     * @param list<string> $ruleSet
     *
     * @return list<string>
     */
    public static function enabledIn(Container $container, array $ruleSet): array
    {
        $enabled = [];
        foreach ($container->getServicesByTag('phpstan.rules.rule') as $rule) {
            if (is_object($rule) && in_array($rule::class, $ruleSet, true)) {
                $enabled[] = $rule::class;
            }
        }
        sort($enabled);

        return $enabled;
    }
}
