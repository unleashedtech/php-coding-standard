<?php

namespace Unleashed\Tests\Helpers;

use Unleashed\Helpers\UseStatements;
use Unleashed\Tests\PHPCSTestCase;

final class UseStatementsTest extends PHPCSTestCase
{
    public function testGetAliasesAndNonGlobalFunctionsDefinedInUseStatements()
    {
        $file = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');

        $result = UseStatements::getAliasesAndNonGlobalFunctionsDefinedInUseStatements($file);
        $expected = [
            'stringlength',
            'isbar',
            'foo',
            'strpos',
        ];

        $this->assertSame($expected, array_keys($result));
    }
}
