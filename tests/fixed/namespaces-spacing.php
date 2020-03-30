<?php

declare(strict_types=1);

namespace Foo;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

use function time as now;

use const DATE_RFC3339;

\strrev(
    (new DateTimeImmutable('@' . now(), new DateTimeZone('UTC')))
        ->sub(new DateInterval('P1D'))
        ->format(DATE_RFC3339)
);
