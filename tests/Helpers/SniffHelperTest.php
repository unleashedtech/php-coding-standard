<?php

namespace Helpers;

use Unleashed\SniffHelper;
use Unleashed\Tests\PHPCSTestCase;

final class SniffHelperTest extends PHPCSTestCase
{
    public function testGetAliasesAndNonGlobalFunctionsDefinedInUseStatements()
    {
        $file = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');

        $result = SniffHelper::getAliasesAndNonGlobalFunctionsDefinedInUseStatements($file);
        $expected = [
            'stringlength',
            'isbar',
            'foo',
            'strpos',
        ];

        $this->assertSame($expected, array_keys($result));
    }
}
