<?php

/**
 * This file is part of the Unleashed PHP coding standard (phpcs standard)
 *
 * @author  wicliff wolda <dev@bloody-wicked.com>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/unleashedtech/php-coding-standard
 */

namespace Unleashed\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether there are (else)if or break statements after return or throw
 */
class ReturnOrThrowSniff implements Sniff
{
    private $openers = [
        T_IF,
        T_CASE,
    ];

    private $conditions = [
        T_ELSEIF,
        T_ELSE,
        T_BREAK,
    ];

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_THROW,
            T_RETURN,
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
        $elem = $tokens[$stackPtr];

        // find the function/closure we're currently in and an if or case statement
        $function = $phpcsFile->findPrevious([T_FUNCTION, T_CLOSURE], $stackPtr);

        if (false === $function) {
            return;
        }

        $opener = $phpcsFile->findPrevious(
            $this->openers,
            $stackPtr,
            $tokens[$function]['scope_opener']
        );

        $scopeCloserLine = -1;

        if (false === isset($tokens[$opener]['scope_closer'])) {
            return;
        }

        if ($opener) {
            $scopeCloserLine = $tokens[$tokens[$opener]['scope_closer']]['line'];
        }

        // check whether the return / throw is within a if or case statement
        if ($opener && $elem['line'] <= $scopeCloserLine) {
            // check whether there's an elseif, else or break
            // following the if or case statement
            $condition = $phpcsFile->findNext(
                $this->conditions,
                $stackPtr + 1,
                $tokens[$function]['scope_closer']
            );

            if (false !== $condition) {
                if (T_CASE === $tokens[$opener]['code']) {
                    $next = $phpcsFile->findNext(
                        [T_CASE, T_DEFAULT],
                        $stackPtr + 1,
                        $tokens[$function]['scope_closer']
                    );

                    $err = true;

                    if (false !== $next) {
                        $err = $tokens[$condition]['line'] < $tokens[$next]['line'];
                    }
                } else {
                    $err = $tokens[$condition]['line'] === $scopeCloserLine;
                }

                if ($err) {
                    $error = 'Do not use else, elseif, break after if and ';
                    $error .= 'case conditions which return or throw something';

                    $phpcsFile->addError(
                        $error,
                        $stackPtr,
                        'Invalid'
                    );
                }
            }
        }
    }
}
