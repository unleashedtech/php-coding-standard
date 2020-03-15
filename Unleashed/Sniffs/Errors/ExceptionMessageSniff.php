<?php

/**
 * This file is part of the Unleashed PHP coding standard (phpcs standard)
 *
 * @author  wicliff wolda <dev@bloody-wicked.com>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/unleashedtech/php-coding-standard
 */

namespace Unleashed\Sniffs\Errors;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether functions are defined on one line.
 */
class ExceptionMessageSniff implements Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_THROW,
        ];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param File $phpcsFile The file where the token was found.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void|int Optionally returns a stack pointer. The sniff will not be
     *                  called again on the current file until the returned stack
     *                  pointer is reached. Return (count($tokens) + 1) to skip
     *                  the rest of the file.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $opener = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);

        // No parenthesis found after the throw, no additional check required
        if ($opener === false) {
            return;
        }

        // check the content of the found parenthesis,
        // only if it is in the same statement as the throw
        $endOfStatement = $phpcsFile->findNext(T_SEMICOLON, $stackPtr);
        if ($endOfStatement > $opener) {
            if ($phpcsFile->findNext(
                T_STRING_CONCAT,
                $tokens[$opener]['parenthesis_opener'],
                $tokens[$opener]['parenthesis_closer']
            )
            ) {
                $error = 'Exception and error message strings must be ';
                $error .= 'concatenated using sprintf';

                $phpcsFile->addError($error, $stackPtr, 'Invalid');
            }
        }
    }
}
