<?php

/**
 * This file is part of the Unleashed PHP coding standard (phpcs standard)
 *
 * @author   wicliff wolda <dev@bloody-wicked.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/unleashedtech/php-coding-standard
 */

namespace Unleashed\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether filename contains any other character than alphanumeric
 * and underscores.
 */
class AlphanumericFilenameSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_OPEN_TAG];
    }

    /**
     * Process.
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
        $filename = $phpcsFile->getFilename();

        if ($filename === 'STDIN') {
            return;
        }

        $filename = str_replace('_', '', basename($filename, '.php'));

        if (false === ctype_alnum($filename)) {
            $error = sprintf(
                'Filename "%s" contains non alphanumeric characters',
                $filename
            );
            $phpcsFile->addError($error, $stackPtr, 'Invalid');
            $phpcsFile->recordMetric($stackPtr, 'Alphanumeric filename', 'no');
        } else {
            $phpcsFile->recordMetric($stackPtr, 'Alphanumeric filename', 'yes');
        }

        // Ignore the rest of the file.
        return ($phpcsFile->numTokens + 1);
    }
}
