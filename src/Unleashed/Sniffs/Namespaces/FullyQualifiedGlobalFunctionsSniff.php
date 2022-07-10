<?php

declare(strict_types=1);

namespace Unleashed\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Sniffs\Classes\ModernClassNameReferenceSniff;
use Unleashed\Helpers\NamespaceHelper;
use Unleashed\Helpers\UseStatements;

final class FullyQualifiedGlobalFunctionsSniff implements Sniff
{
    public bool $onlyOptimizedFunctions = false;

    /** @var array<string, bool> */
    private array $optimizedFunctions = [
        // @see https://github.com/php/php-src/blob/PHP-7.4/Zend/zend_compile.c "zend_try_compile_special_func"
        'array_key_exists'     => true,
        'array_slice'          => true,
        'assert'               => true,
        'boolval'              => true,
        'call_user_func'       => true,
        'call_user_func_array' => true,
        'chr'                  => true,
        'count'                => true,
        'defined'              => true,
        'doubleval'            => true,
        'floatval'             => true,
        'func_get_args'        => true,
        'func_num_args'        => true,
        'get_called_class'     => true,
        'get_class'            => true,
        'gettype'              => true,
        'in_array'             => true,
        'intval'               => true,
        'is_array'             => true,
        'is_bool'              => true,
        'is_double'            => true,
        'is_float'             => true,
        'is_int'               => true,
        'is_integer'           => true,
        'is_long'              => true,
        'is_null'              => true,
        'is_object'            => true,
        'is_real'              => true,
        'is_resource'          => true,
        'is_string'            => true,
        'ord'                  => true,
        'sizeof'               => true,
        'strlen'               => true,
        'strval'               => true,
        // @see https://github.com/php/php-src/blob/php-7.2.6/ext/opcache/Optimizer/pass1_5.c
        'constant'             => true,
        'define'               => true,
        'dirname'              => true,
        'extension_loaded'     => true,
        'function_exists'      => true,
        'is_callable'          => true,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     * We're looking for all functions, so use T_STRING.
     *
     * {@inheritDoc}
     */
    public function register()
    {
        return [\T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * Code from ForbiddenFunctionsSniff:
     *
     * @link https://github.com/squizlabs/PHP_CodeSniffer/blob/master/src/Standards/Generic/Sniffs/PHP/ForbiddenFunctionsSniff.php#L118
     *
     * {@inheritDoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // Abort if we're not in namespaced code
        $firstNamespacePointer = NamespaceHelper::getFirstNamespacePointer($phpcsFile);
        if ($firstNamespacePointer === null || $stackPtr < $firstNamespacePointer) {
            return;
        }

        if (
            $this->onlyOptimizedFunctions !== null
            && \filter_var($this->onlyOptimizedFunctions, FILTER_VALIDATE_BOOLEAN) !== false
        ) {
            $globalFunctions = $this->optimizedFunctions;
        } else {
            $globalFunctions = \array_flip(\get_defined_functions()['internal']);
        }

        $whitelist = UseStatements::getAliasesAndNonGlobalFunctionsDefinedInUseStatements($phpcsFile);

        $tokens    = $phpcsFile->getTokens();
        $ignore    = [
            T_DOUBLE_COLON    => true,
            T_OBJECT_OPERATOR => true,
            T_FUNCTION        => true,
            T_CONST           => true,
            T_PUBLIC          => true,
            T_PRIVATE         => true,
            T_PROTECTED       => true,
            T_AS              => true,
            T_NEW             => true,
            T_INSTEADOF       => true,
            T_NS_SEPARATOR    => true,
            T_IMPLEMENTS      => true,
        ];
        $prevToken = $phpcsFile->findPrevious([T_WHITESPACE, T_COMMENT], $stackPtr - 1, null, true);

        // If function call is directly preceded by a NS_SEPARATOR don't try to fix it.
        if ($tokens[$prevToken]['code'] === T_NS_SEPARATOR && $tokens[$stackPtr]['code'] === T_STRING) {
            return;
        }

        if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;
        }

        $nextToken = $phpcsFile->findNext([T_WHITESPACE, T_COMMENT], $stackPtr + 1, null, true);
        if (isset($ignore[$tokens[$nextToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;
        }

        if ($tokens[$nextToken]['code'] !== T_OPEN_PARENTHESIS) {
            // Not a call to a PHP function.
            return;
        }

        $function           = \strtolower($tokens[$stackPtr]['content']);
        $functionNormalized = \strtolower($function);

        // Is it a whitelisted alias?
        if (isset($whitelist[$functionNormalized])) {
            return;
        }

        // Is it an global PHP function?
        if (isset($globalFunctions[$functionNormalized]) === false) {
            return;
        }

        $error = \sprintf('Function %1$s() should be referenced via a fully qualified name, e.g.: \%1$s()', $function);
        $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'NotFullyQualified');

        if ($fix === true) {
            $this->applyFix($phpcsFile, $stackPtr, $function);
        }
    }

    private function applyFix(File $phpcsFile, int $stackPtr, string $function): void
    {
        // This sniff conflicts with ModernClassNameReferenceSniff, so don't bother fixing things it will attempt to fix
        if (
            \array_key_exists(ModernClassNameReferenceSniff::class, $phpcsFile->ruleset->sniffs)
            && \in_array($function, ['get_class', 'get_parent_class', 'get_called_class'], true)
        ) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->addContentBefore($stackPtr, '\\');
        $phpcsFile->fixer->endChangeset();
    }
}
