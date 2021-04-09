<?php

declare(strict_types=1);

namespace Test;

use BarException;
use FooException;

/**
 * @template T
 * @template-covariant T
 * @extends
 * @implements
 *
 * @phpstan-template T
 * @phpstan-template-covariant T
 * @phpstan-extends
 * @phpstan-implements T
 */
class Test
{
    /**
     * @var array<mixed>
     * @psalm-var list<mixed>
     * @phpstan-var array<mixed>
     */
    public $foo = [];

    /**
     * Description
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
     *
     * @param int[] $foo
     *
     * @throws FooException
     */
    public function c(iterable $foo): void
    {
    }

    /**
     * Description
     * More Description
     *
     * @internal
     * @deprecated Use c() instead
     *
     * @link https://example.com
     * @see  other
     * @uses other
     *
     * @ORM\Id
     * @ORM\Column
     * @ODM\Id
     * @ODM\Column
     * @PHPCR\Uuid
     * @PHPCR\Field
     *
     * @param int[] $foo
     * @param int[] $bar
     *
     * @psalm-param list<int>
     *
     * @phpstan-param int[]
     *
     * @return int[]
     *
     * @psalm-return list<int>
     *
     * @phpstan-return int[]
     *
     * @throws FooException
     * @throws BarException
     *
     * @psalm-internal
     * @psalm-pure
     */
    public function d(iterable $foo, iterable $bar): iterable
    {
    }
}
