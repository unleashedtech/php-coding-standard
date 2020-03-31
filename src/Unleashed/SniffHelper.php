<?php

declare(strict_types=1);

namespace Unleashed;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\SniffLocalCache;
use SlevomatCodingStandard\Helpers\UseStatement;
use SlevomatCodingStandard\Helpers\UseStatementHelper;

/**
 * @internal
 */
final class SniffHelper
{
    /**
     * @return array<string,bool>
     */
    public static function getAliasesAndNonGlobalFunctionsDefinedInUseStatements(File $file): array
    {
        static $cache;
        $cache = $cache ?? new SniffLocalCache();

        $lazyValue = static function () use ($file): array {
            $result = [];

            foreach (UseStatementHelper::getFileUseStatements($file) as $useStatements) {
                foreach ($useStatements as $useStatement) {
                    \assert($useStatement instanceof UseStatement);
                    if ($useStatement->getType() !== 'function') {
                        continue;
                    }

                    if (
                        $useStatement->getAlias() !== null
                        || \strpos($useStatement->getFullyQualifiedTypeName(), '\\') !== false
                    ) {
                        $result[$useStatement->getCanonicalNameAsReferencedInFile()] = true;
                    }
                }
            }

            return $result;
        };

        return $cache->getAndSetIfNotCached($file, $lazyValue);
    }
}
