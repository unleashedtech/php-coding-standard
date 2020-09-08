<?php

declare(strict_types=1);

namespace Test;

use BarException;
use FooException;

/**
 * @implements
 * @extends
 * @template T
 * @template-covariant
 * @phpstan-implements T
 * @phpstan-extends
 * @phpstan-template
 * @phpstan-template-covariant
 */
class Test
{
    /**
     * @var array<mixed>
     *
     * @phpstan-var array<mixed>
     *
     * @psalm-var list<mixed>
     */
    public $foo = [];

    /**
     *
     * Description
     *
     */
    public function a(): void
    {
    }

    /**
     * Description
     * More Description
     * Even More Description
     */
    public function b(): void
    {
    }

    /**
     * First Paragraph Description
     *
     * Second Paragraph Description
     * @throws FooException
     * @param int[] $foo
     */
    public function c(iterable $foo) : void
    {
    }

    /**
     *
     * Description
     * More Description
     * @throws FooException
     * @param int[] $foo
     * @uses other
     * @throws BarException
     * @return int[]
     * @ORM\Id
     * @internal
     * @link https://example.com
     * @ODM\Id
     * @deprecated Use c() instead
     * @PHPCR\Uuid
     * @param int[] $bar
     * @PHPCR\Field
     * @psalm-internal
     * @psalm-param list<int>
     * @psalm-return list<int>
     * @psalm-pure
     * @phpstan-param int[]
     * @phpstan-return int[]
     * @ODM\Column
     * @ORM\Column
     * @see  other
     *
     */
    public function d(iterable $foo, iterable $bar) : iterable
    {
    }
}
