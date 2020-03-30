<?php

declare(strict_types=1);

namespace Foo;

use function Bar\hash as md5;
use function strlen as stringLength;

$foo = stringLength('foo');

$bar = \strrev(\strrev('bar'));

$baz = \time();

$hash = md5('stuff');
