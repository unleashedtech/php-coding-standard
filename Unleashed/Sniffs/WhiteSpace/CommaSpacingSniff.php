<?php

/**
 * This file is part of the Unleashed PHP coding standard (phpcs standard)
 *
 * @author   wicliff wolda <dev@bloody-wicked.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/unleashedtech/php-coding-standard
 */

namespace Unleashed\Sniffs\Whitespace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Throws warnings if comma isn't followed by a whitespace.
 */
class CommaSpacingSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_COMMA,
        );

    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $line   = $tokens[$stackPtr]['line'];

        if ($tokens[$stackPtr + 1]['line'] === $line
            && $tokens[$stackPtr + 1]['code'] !== T_WHITESPACE
        ) {
            $fix = $phpcsFile->addFixableError(
                'Add a single space after each comma delimiter',
                $stackPtr,
                'Invalid'
            );

            if ($fix === true) {
                $phpcsFile->fixer->addContent($stackPtr, " ");
            }
        }
    }
}
