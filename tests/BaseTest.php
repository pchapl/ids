<?php

namespace PChapl\Tests;

use PChapl\DoctrineIdBundle\Id\Base;
use PChapl\DoctrineIdBundle\Id\Factory;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    private const ID_VALUE = 'test-value';

    private Base $id;
    private Factory $factory;

    protected function setUp(): void
    {
        $this->factory = new class(self::ID_VALUE) implements Factory {
            public function __construct(private string $value)
            {
            }

            public function generate(): string
            {
                return $this->value;
            }
        };

        $this->id = TestId::new($this->factory);
    }

    public function testNew(): void
    {
        $new = TestId::new($this->factory);

        self::assertInstanceOf(TestId::class, $new);
    }

    public function testToString(): void
    {
        self::assertSame(self::ID_VALUE, (string)$this->id);
    }

    public function testGetValue(): void
    {
        self::assertSame(self::ID_VALUE, $this->id->getValue());
    }

    public function testFromValue(): void
    {
        $value = 'test';
        $from = TestId::fromValue($value);

        self::assertInstanceOf(TestId::class, $from);
        self::assertSame($value, $from->getValue());
    }
}
