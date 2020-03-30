<?php

use Bar\Baz;
use Foo;
use Lorem\Ipsum as LoremIpsum;
use const Lerdorf\IS_BAR;
use const Rasmus\FOO;
use function time;
use function strlen as stringLength;
use function Lerdorf\isBar;
use function Rasmus\foo;
use function Rasums\bar as strpos;

class FooBar
{

    use BarTrait;

    function foo() {
        $test = 'foo';
        function() use ($test) {

        };
    }

}

$test = 'foo';

function () use ($test) {

};

use Zero;
