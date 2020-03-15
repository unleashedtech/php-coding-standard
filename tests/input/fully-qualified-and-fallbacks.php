<?php

declare(strict_types=1);

namespace Foo;

use DateInterval;

use function time as now;

use const DATE_RFC3339;

\strrev(strrev(
    (new \DateTimeImmutable('@' . now(), new DateTimeZone('UTC')))
        ->sub(new DateInterval('P1D'))
        ->format(DATE_RFC3339)
));
