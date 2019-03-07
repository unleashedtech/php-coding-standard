<?php

/**
 * This file is part of the Unleashed PHP coding standard (phpcs standard)
 *
 * @author   wicliff wolda <dev@bloody-wicked.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/unleashedtech/php-coding-standard
 */

namespace Unleashed\Sniffs\Formatting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Throws errors if there's no blank line before return statements.
 * Symfony coding standard specifies: "Add a blank line before return statements,
 * unless the return is alone inside a statement-group (like an if statement);"
 */
class BlankLineBeforeReturnSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
        'JS',
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_RETURN);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token in
     *                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens          = $phpcsFile->getTokens();
        $current         = $stackPtr;
        $previousLine    = $tokens[$stackPtr]['line'] - 1;
        $prevLineTokens  = array();

        while ($current >= 0 && $tokens[$current]['line'] >= $previousLine) {
            if ($tokens[$current]['line'] === $previousLine
                && $tokens[$current]['type'] !== 'T_WHITESPACE'
                && $tokens[$current]['type'] !== 'T_COMMENT'
                && $tokens[$current]['type'] !== 'T_DOC_COMMENT_CLOSE_TAG'
                && $tokens[$current]['type'] !== 'T_DOC_COMMENT_WHITESPACE'
            ) {
                $prevLineTokens[] = $tokens[$current]['type'];
            }
            $current--;
        }

        if (isset($prevLineTokens[0])
            && ($prevLineTokens[0] === 'T_OPEN_CURLY_BRACKET'
            || $prevLineTokens[0] === 'T_COLON')
        ) {
            return;
        }

        if (count($prevLineTokens) > 0) {
            $fix = $phpcsFile->addFixableError(
                'Missing blank line before return statement',
                $stackPtr,
                'MissedBlankLineBeforeReturn'
            );

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $i = 1;
                while ($tokens[$stackPtr-$i]['type'] === "T_WHITESPACE") {
                    $i++;
                }

                $phpcsFile->fixer->addNewLine($stackPtr-$i);
                $phpcsFile->fixer->endChangeset();
            }
        }

        return;
    }
}
