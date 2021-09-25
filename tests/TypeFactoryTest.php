<?php

namespace PChapl\Tests;

use PChapl\DoctrineIdBundle\Id\TypeFactory;
use PHPUnit\Framework\TestCase;

class TypeFactoryTest extends TestCase
{
    public function testInstantiate(): void
    {
        $typeOne = TypeFactory::instantiateType(TestId::class, 'test_id_type');
        $typeTwo = TypeFactory::instantiateType(TestId::class, 'test_id_type');

        self::assertNotSame($typeOne, $typeTwo);
        self::assertEquals($typeOne, $typeTwo);
    }
}
