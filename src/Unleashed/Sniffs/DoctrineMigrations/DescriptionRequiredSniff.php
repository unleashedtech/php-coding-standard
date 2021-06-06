<?php

declare(strict_types=1);

namespace Unleashed\Sniffs\DoctrineMigrations;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

final class DescriptionRequiredSniff implements Sniff
{
    public const CODE_MISSING_DESCRIPTION = 'MissingDescription';
    public const CODE_EMPTY_DESCRIPTION   = 'EmptyDescription';

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * {@inheritDoc}
     */
    public function register()
    {
        return [\T_EXTENDS];
    }

    /**
     * {@inheritDoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $parentClassPtr = TokenHelper::findNext($phpcsFile, T_STRING, $stackPtr);
        if ($parentClassPtr === null) {
            return;
        }

        // Only continue if we've found a Doctrine Migration class
        $parentClass = NamespaceHelper::resolveClassName($phpcsFile, $tokens[$parentClassPtr]['content'], $stackPtr);
        if ($parentClass !== '\\Doctrine\\Migrations\\AbstractMigration') {
            return;
        }

        // Does a `getDescription()` method exist?
        $classEndPtr = self::findApproximateClassEndPointer($phpcsFile, $parentClassPtr);
        $methodPtr   = self::findMethodInClass($phpcsFile, 'getDescription', $parentClassPtr, $classEndPtr);
        if ($methodPtr === null) {
            // Nope - method is missing
            $fix = $phpcsFile->addFixableError(
                'Doctrine Migrations must have a getDescription() method.',
                TokenHelper::findPrevious($phpcsFile, T_CLASS, $stackPtr),
                self::CODE_MISSING_DESCRIPTION
            );

            if ($fix) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addContent(
                    TokenHelper::findNext($phpcsFile, T_OPEN_CURLY_BRACKET, $stackPtr),
                    "\n    public function getDescription(): string\n    {\n        return '';\n    }"
                );
                $phpcsFile->fixer->endChangeset();
            }

            return;
        }

        // Does the method have a description?
        $returnValuePtr = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $methodPtr + 1, null, false, null, true);
        if ($tokens[$returnValuePtr]['content'] !== '""' && $tokens[$returnValuePtr]['content'] !== "''") {
            return;
        }

        $phpcsFile->addError(
            'Doctrine Migrationss must return useful information from getDescription(); empty strings not allowed',
            $returnValuePtr,
            self::CODE_EMPTY_DESCRIPTION
        );
    }

    private static function findMethodInClass(File $phpcsFile, string $methodName, int $startPtr, int $endPtr): ?int
    {
        do {
            $nextFunctionPointer = TokenHelper::findNext($phpcsFile, T_FUNCTION, $startPtr + 1);
            if ($nextFunctionPointer === null) {
                break;
            }

            if ($nextFunctionPointer >= $endPtr) {
                return null;
            }

            $startPtr = $nextFunctionPointer;

            if (! FunctionHelper::isMethod($phpcsFile, $startPtr)) {
                continue;
            }

            $name = FunctionHelper::getName($phpcsFile, $nextFunctionPointer);

            if ($name !== $methodName) {
                continue;
            }

            return $nextFunctionPointer;
        } while (true);

        return null;
    }

    private static function findApproximateClassEndPointer(File $phpcsFile, int $ptrWithinClass): ?int
    {
        $classPtrs = \array_keys(ClassHelper::getAllNames($phpcsFile));

        while (\current($classPtrs) !== false) {
            $start = \current($classPtrs);
            $end   = \next($classPtrs);

            if ($start <= $ptrWithinClass && $end >= $ptrWithinClass) {
                return $end;
            }
        }

        return $phpcsFile->numTokens;
    }
}
