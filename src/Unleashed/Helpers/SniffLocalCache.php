<?php

declare(strict_types=1);

namespace Unleashed\Helpers;

use PHP_CodeSniffer\Files\File;

/**
 * @internal
 *
 * Forked from https://github.com/slevomat/coding-standard/blob/6.3.3/SlevomatCodingStandard/Helpers/SniffLocalCache.php
 */
final class SniffLocalCache
{
    /** @var array<int, array<string, mixed>> */
    private static $cache = [];

    /**
     * @return mixed
     */
    public static function getAndSetIfNotCached(File $phpcsFile, string $key, \Closure $lazyValue)
    {
        $fixerLoops  = $phpcsFile->fixer !== null ? $phpcsFile->fixer->loops : 0;
        $internalKey = \sprintf('%s-%s', $phpcsFile->getFilename(), $key);

        self::setIfNotCached($fixerLoops, $internalKey, $lazyValue);

        return self::$cache[$fixerLoops][$internalKey] ?? null;
    }

    private static function setIfNotCached(int $fixerLoops, string $internalKey, \Closure $lazyValue): void
    {
        if (
            \array_key_exists($fixerLoops, self::$cache) &&
            \array_key_exists($internalKey, self::$cache[$fixerLoops])
        ) {
            return;
        }

        self::$cache[$fixerLoops][$internalKey] = $lazyValue();

        if ($fixerLoops > 0) {
            unset(self::$cache[$fixerLoops - 1]);
        }
    }
}
