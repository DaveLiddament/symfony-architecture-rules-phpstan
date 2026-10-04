<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Build\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Internal to this package (not shipped in extension.neon): every concrete
 * rule in the rule directories must have a <RuleName>Test.php somewhere in
 * the tests directory, with a non-empty Fixtures directory next to it.
 * Several rules may share one Fixtures directory.
 *
 * @implements Rule<InClassNode>
 */
final class CheckRuleHasTest implements Rule
{
    private const string FIXTURES = 'Fixtures';

    /** @var list<string> */
    private array $ruleDirectories;

    private string $testsDirectory;

    /** @var array<string, string>|null test file name => directory it is in */
    private ?array $testFiles = null;

    /**
     * @param list<string> $ruleDirectories
     */
    public function __construct(array $ruleDirectories, string $testsDirectory)
    {
        $this->ruleDirectories = array_map(self::normalise(...), $ruleDirectories);
        $this->testsDirectory = self::normalise($testsDirectory);
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
        if (
            $classReflection->isAnonymous()
            || $classReflection->isAbstract()
            || !$classReflection->implementsInterface(Rule::class)
            || !$this->isInRuleDirectory($scope->getFile())
        ) {
            return [];
        }

        $testFileName = $classReflection->getNativeReflection()->getShortName().'Test.php';
        $testDirectory = $this->getTestFiles()[$testFileName] ?? null;

        if (null === $testDirectory) {
            return [$this->error(sprintf(
                'Rule %s has no test: expected %s somewhere in the tests directory.',
                $classReflection->getName(),
                $testFileName,
            ))];
        }

        $fixturesDirectory = $testDirectory.'/'.self::FIXTURES;
        if (!self::containsAFile($fixturesDirectory)) {
            return [$this->error(sprintf(
                'Rule %s has no fixtures: expected at least one file in %s.',
                $classReflection->getName(),
                substr($fixturesDirectory, strlen($this->testsDirectory) + 1),
            ))];
        }

        return [];
    }

    private function isInRuleDirectory(string $file): bool
    {
        foreach ($this->ruleDirectories as $ruleDirectory) {
            if (str_starts_with(self::normalise($file), $ruleDirectory.'/')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Test files are found once per run. Files inside Fixtures directories
     * are fixtures, not tests, so they don't count.
     *
     * @return array<string, string>
     */
    private function getTestFiles(): array
    {
        if (null !== $this->testFiles) {
            return $this->testFiles;
        }

        $this->testFiles = [];
        foreach (self::filesIn($this->testsDirectory) as $file) {
            $path = self::normalise($file->getPathname());
            $pathInTestsDirectory = substr($path, strlen($this->testsDirectory));
            if (str_ends_with($path, 'Test.php') && !str_contains($pathInTestsDirectory, '/'.self::FIXTURES.'/')) {
                $this->testFiles[$file->getFilename()] = dirname($path);
            }
        }

        return $this->testFiles;
    }

    /**
     * Hidden files (e.g. .gitkeep) are not fixtures.
     */
    private static function containsAFile(string $directory): bool
    {
        foreach (self::filesIn($directory) as $file) {
            if (!str_starts_with($file->getFilename(), '.')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return iterable<\SplFileInfo>
     */
    private static function filesIn(string $directory): iterable
    {
        if (!is_dir($directory)) {
            return;
        }

        $entries = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
        );
        foreach ($entries as $entry) {
            if ($entry instanceof \SplFileInfo && $entry->isFile()) {
                yield $entry;
            }
        }
    }

    private static function normalise(string $path): string
    {
        return rtrim(str_replace('\\', '/', $path), '/');
    }

    private function error(string $message): IdentifierRuleError
    {
        return RuleErrorBuilder::message($message)
            ->identifier('phpstanExtensionLibrary.ruleHasTest')
            ->build();
    }
}
