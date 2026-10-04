<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Config;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\DomainInternalRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\LibIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\NurseryIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\SharedIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\CommandDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ConfigProviderDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\DtoDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\FormTypeDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\QueueProcessorDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\RepositoryDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\SerializerDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ServiceDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ValueObjectDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ValueObjectPropertyTypeRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ViewModelDeclarationRule;
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

    public const array ROLES = [
        CommandDeclarationRule::class,
        ConfigProviderDeclarationRule::class,
        DtoDeclarationRule::class,
        FormTypeDeclarationRule::class,
        QueueProcessorDeclarationRule::class,
        RepositoryDeclarationRule::class,
        SerializerDeclarationRule::class,
        ServiceDeclarationRule::class,
        ValueObjectDeclarationRule::class,
        ValueObjectPropertyTypeRule::class,
        ViewModelDeclarationRule::class,
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
