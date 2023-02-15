<?php

declare(strict_types=1);

namespace Doctrine;

// phpcs:disable PSR1.Files.SideEffects

class TrailingCommaOnFunctions
{
    public function a(int $arg): void
    {
    }

    public function b(
        int $arg,
    ): void {
    }

    public function uses(): void
    {
        $var = null;

        $singleLine = static function (int $arg) use ($var): void {
            echo $var;
        };

        $multiLine = static function (int $arg) use (
            $var,
        ): void {
            echo $var;
        };
    }
}

$class = new TrailingCommaOnFunctions();

// phpcs:ignore Generic.Functions.FunctionCallArgumentSpacing.NoSpaceAfterComma
$class->a(1);

$class->a(
    1,
);
