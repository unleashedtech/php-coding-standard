<?php

declare(strict_types=1);

namespace Test;

echo \chop('abc ');
echo \sizeof([1, 2, 3]);
echo \is_null(456) ? 'y' : 'n';

$foo = '1';
\settype($foo, 'int');
\var_dump($foo);

$bar = [
    'foo' => 1,
    'bar' => 2,
    'baz' => 3,
];
\extract($bar);

\compact('foo', 'bar');
