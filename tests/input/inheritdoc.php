<?php

declare(strict_types=1);

namespace Test;

interface Foo
{
    /**
     * @param array<int, string> $foo
     */
    public function foo(array $foo): void;
}

class A implements Foo
{
    /**
     * {@inheritDoc}
     */
    public function foo(array $foo): void
    {
    }
}

class B implements Foo
{
    /**
     * {@inheritdoc}
     */
    public function foo(array $foo): void
    {
    }
}

class C implements Foo
{
    /**
     * @inheritDoc
     */
    public function foo(array $foo): void
    {
    }
}

class D implements Foo
{
    /**
     * @inheritdoc
     */
    public function foo(array $foo): void
    {
    }
}
