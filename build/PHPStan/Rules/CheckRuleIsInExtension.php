<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Build\PHPStan\Rules;

use Nette\Neon\Neon;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Internal to this package (not shipped in extension.neon): every concrete
 * rule in the package must be registered in extension.neon as a service
 * tagged "phpstan.rules.rule", either directly or via conditionalTags.
 *
 * @implements Rule<InClassNode>
 */
final class CheckRuleIsInExtension implements Rule
{
    private const string RULE_TAG = 'phpstan.rules.rule';

    /** @var list<string> */
    private array $registeredRules;

    /**
     * @param list<string> $excludedNamespaces
     */
    public function __construct(
        string $extensionFile,
        private string $namespace,
        private array $excludedNamespaces,
    ) {
        $this->registeredRules = self::readRegisteredRules($extensionFile);
    }

    #[\Override]
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $node->getClassReflection();
        if ($classReflection->isAbstract() || !$classReflection->implementsInterface(Rule::class)) {
            return [];
        }

        $className = $classReflection->getName();
        if (!$this->isChecked($className) || in_array($className, $this->registeredRules, true)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf('Rule [%s] not in extension.neon.', $className))
                ->identifier('phpstanExtensionLibrary.misconfigured')
                ->build(),
        ];
    }

    private function isChecked(string $className): bool
    {
        if (!str_starts_with($className, $this->namespace.'\\')) {
            return false;
        }

        foreach ($this->excludedNamespaces as $excludedNamespace) {
            if (str_starts_with($className, $excludedNamespace.'\\')) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<string>
     */
    private static function readRegisteredRules(string $extensionFile): array
    {
        $config = Neon::decodeFile($extensionFile);
        if (!is_array($config)) {
            throw new \InvalidArgumentException(sprintf('Expecting %s to be parseable.', $extensionFile));
        }

        $conditionalTags = $config['conditionalTags'] ?? [];
        if (!is_array($conditionalTags)) {
            throw new \InvalidArgumentException('Expecting conditionalTags to be a map.');
        }

        $services = $config['services'] ?? [];
        if (!is_array($services)) {
            throw new \InvalidArgumentException('Expecting services to be a list.');
        }

        $registeredRules = [];
        foreach ($services as $service) {
            if (!is_array($service) || !is_string($service['class'] ?? null)) {
                continue;
            }
            $class = $service['class'];

            $tags = $service['tags'] ?? [];
            $conditional = $conditionalTags[$class] ?? [];
            if (
                (is_array($tags) && in_array(self::RULE_TAG, $tags, true))
                || (is_array($conditional) && array_key_exists(self::RULE_TAG, $conditional))
            ) {
                $registeredRules[] = $class;
            }
        }

        return $registeredRules;
    }
}
