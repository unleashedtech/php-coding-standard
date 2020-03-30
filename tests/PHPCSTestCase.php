<?php

namespace Unleashed\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Runner;
use PHPUnit\Framework\TestCase;

abstract class PHPCSTestCase extends TestCase
{
    protected function getCodeSnifferFile(string $filename): File
    {
        $codeSniffer = new Runner();
        $codeSniffer->config = new Config([
            '-s',
        ]);
        $codeSniffer->init();

        $phpcsFile = new LocalFile(
            $filename,
            $codeSniffer->ruleset,
            $codeSniffer->config
        );

        $phpcsFile->process();

        return $phpcsFile;
    }
}
