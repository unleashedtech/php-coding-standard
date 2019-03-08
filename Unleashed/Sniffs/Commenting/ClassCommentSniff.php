<?php

/**
 * This file is part of the Unleashed PHP coding standard (phpcs standard)
 *
 * @author   wicliff wolda <dev@bloody-wicked.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/unleashedtech/php-coding-standard
 */

namespace Unleashed\Sniffs\Commenting;

use PHP_CodeSniffer\Standards\PEAR\Sniffs\Commenting\ClassCommentSniff as Sniff;

/**
 * Parses and verifies the doc comments for classes.
 *
 * Verifies that :
 * <ul>
 *  <li>A doc comment exists.</li>
 *  <li>There is a blank newline after the short description.</li>
 *  <li>There is a blank newline between the long and short description.</li>
 *  <li>There is a blank newline between the long description and tags.</li>
 *  <li>Check the order of the tags.</li>
 *  <li>Check the indentation of each tag.</li>
 *  <li>Check required and optional tags and the format of their content.</li>
 * </ul>
 */
class ClassCommentSniff extends Sniff
{
    /**
     * Tags in correct order and related info.
     *
     * @var array
     */
    protected $tags = [
        'category' => [
            'required' => false,
            'allow_multiple' => false,
            'order_text' => 'precedes @package',
        ],
        'author' => [
            'required' => false,
            'allow_multiple' => true,
            'order_text' => 'follows @subpackage (if used) or @package',
        ],
        'copyright' => [
            'required' => false,
            'allow_multiple' => true,
            'order_text' => 'follows @author',
        ],
        'license' => [
            'required' => false,
            'allow_multiple' => false,
            'order_text' => 'follows @copyright (if used) or @author',
        ],
        'version' => [
            'required' => false,
            'allow_multiple' => false,
            'order_text' => 'follows @license',
        ],
        'link' => [
            'required' => false,
            'allow_multiple' => true,
            'order_text' => 'follows @version',
        ],
        'see' => [
            'required' => false,
            'allow_multiple' => true,
            'order_text' => 'follows @link',
        ],
        'since' => [
            'required' => false,
            'allow_multiple' => false,
            'order_text' => 'follows @see (if used) or @link',
        ],
        'deprecated' => [
            'required' => false,
            'allow_multiple' => false,
            'order_text' => 'follows @since (if used) or @see (if used) or @link',
        ],
    ];

    protected $blacklist = [
        '@subpackage',
    ];

    /**
     * Processes each tag and raise an error if there are blacklisted tags.
     *
     * @param File $phpcsFile    The file where the token was found.
     * @param int  $stackPtr     The position of the current token
     *                           in the stack passed in $tokens.
     * @param int  $commentStart Position in the stack where the comment started.
     *
     * @return void
     */
    protected function processTags($phpcsFile, $stackPtr, $commentStart)
    {
        $tokens = $phpcsFile->getTokens();

        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            $name = $tokens[$tag]['content'];

            if (in_array($name, $this->blacklist)) {
                $error = sprintf('The %s tag is not allowed.', $name);
                $phpcsFile->addError($error, $tag, 'Blacklisted');
            }
        }

        parent::processTags($phpcsFile, $stackPtr, $commentStart);
    }
}
