<?php

declare(strict_types=1);

use function time as now;

strrev(strrev(
    (new DateTimeImmutable('@' . now(), new DateTimeZone('UTC')))
        ->sub(new DateInterval('P1D'))
        ->format(DATE_RFC3339)
));
