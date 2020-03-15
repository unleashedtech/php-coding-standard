<?php

/**
 * This file is part of the Unleashed PHP coding standard (phpcs standard)
 *
 * @author  wicliff wolda <dev@bloody-wicked.com>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/unleashedtech/php-coding-standard
 */

namespace Unleashed\Tests\Formatting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ReturnOrThrowUnitTest sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class ReturnOrThrowUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getErrorList()
    {
        return array(
            16 => 1,
            45 => 1,
        );
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return array();
    }
}
