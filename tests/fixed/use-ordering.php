<?php

declare(strict_types=1);

namespace Foo;

use DateTimeImmutable;
use DateTimeInterface;

use function sprintf as s;

use const PHP_EOL;

echo s('Current date and time is %s', (new DateTimeImmutable())->format(DateTimeInterface::ATOM)) . PHP_EOL;
