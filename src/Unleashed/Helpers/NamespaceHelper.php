<?php

declare(strict_types=1);

namespace Unleashed\Helpers;

use PHP_CodeSniffer\Files\File;

/**
 * @internal
 *
 * Adapted from https://github.com/slevomat/coding-standard/blob/c7d4801da5b439cec0d7cd6fa770164b07a4d92b/SlevomatCodingStandard/Helpers/NamespaceHelper.php
 */
final class NamespaceHelper
{
    public static function getFirstNamespacePointer(File $phpcsFile): int|null
    {
        $lazyValue = static function () use ($phpcsFile): int|null {
            $token = $phpcsFile->findNext(T_NAMESPACE, 0);

            return $token === false ? null : $token;
        };

        return SniffLocalCache::getAndSetIfNotCached($phpcsFile, 'firstNamespacePointer', $lazyValue);
    }
}
