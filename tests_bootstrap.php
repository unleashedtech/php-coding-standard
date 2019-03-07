<?php
/**
 * Bootstrap file for PHP_CodeSniffer Unleashed Coding Standard unit tests.
 */

$myStandardName = 'Unleashed';

require_once __DIR__.'/vendor/squizlabs/php_codesniffer/tests/bootstrap.php';

// Add this Standard
PHP_CodeSniffer\Config::setConfigData(
    'installed_paths',
    __DIR__.DIRECTORY_SEPARATOR.'Unleashed',
    true
);

// Ignore all other Standards in tests
$standards   = PHP_CodeSniffer\Util\Standards::getInstalledStandards();
$standards[] = 'Generic';

$ignoredStandardsStr = implode(
    ',', array_filter(
        $standards, function ($v) use ($myStandardName) {
            return $v !== $myStandardName;
        }
    )
);

putenv("PHPCS_IGNORE_TESTS={$ignoredStandardsStr}");
