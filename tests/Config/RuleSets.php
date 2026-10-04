<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Config;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\DomainInternalRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\LibIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\NurseryIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\SharedIsolationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\CommandDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ConfigProviderDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ConfigProviderPropertyTypeRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ConfigProviderUsageRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\DtoDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\EntityManagerUsageRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\FormTypeDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\QueueProcessorDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\RepositoryDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\RepositoryDependencyRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\RepositoryMethodRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\SerializerDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ServiceDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ServiceDependencyRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ValueObjectDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ValueObjectPropertyTypeRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ViewModelDeclarationRule;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ViewModelPropertyTypeRule;
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
     * The rules turned off by each role's switch.
     */
    public const array ROLES = [
        'command' => [
            CommandDeclarationRule::class,
        ],
        'configProvider' => [
            ConfigProviderDeclarationRule::class,
            ConfigProviderPropertyTypeRule::class,
            ConfigProviderUsageRule::class,
        ],
        'dto' => [
            DtoDeclarationRule::class,
        ],
        'formType' => [
            FormTypeDeclarationRule::class,
        ],
        'queueProcessor' => [
            QueueProcessorDeclarationRule::class,
        ],
        'repository' => [
            EntityManagerUsageRule::class,
            RepositoryDeclarationRule::class,
            RepositoryDependencyRule::class,
            RepositoryMethodRule::class,
        ],
        'serializer' => [
            SerializerDeclarationRule::class,
        ],
        'service' => [
            ServiceDeclarationRule::class,
            ServiceDependencyRule::class,
        ],
        'valueObject' => [
            ValueObjectDeclarationRule::class,
            ValueObjectPropertyTypeRule::class,
        ],
        'viewModel' => [
            ViewModelDeclarationRule::class,
            ViewModelPropertyTypeRule::class,
        ],
    ];

    /**
     * All the role rules, sorted by class name.
     *
     * @param list<string> $exceptRoles
     *
     * @return list<string>
     */
    public static function roles(array $exceptRoles = []): array
    {
        $rules = [];
        foreach (self::ROLES as $role => $roleRules) {
            if (!in_array($role, $exceptRoles, true)) {
                $rules = [...$rules, ...$roleRules];
            }
        }
        sort($rules);

        return $rules;
    }

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
