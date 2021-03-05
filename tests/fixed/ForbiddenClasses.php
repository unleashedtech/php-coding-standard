<?php

declare(strict_types=1);

namespace Test;

use DateTime as Date;

class ForbiddenClasses extends Date implements Date
{
    use Date;

    public function foo(): void
    {
        $x = new Date();
        $y = new \DateTime();

        if ($x instanceof Date) {
            echo Date::ISO8601;
        }
    }
}
