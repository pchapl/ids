<?php

namespace PChapl\Tests\Id;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PChapl\DoctrineIdBundle\Exception\ConversionException;
use PChapl\DoctrineIdBundle\Id\Base;
use PChapl\DoctrineIdBundle\Id\Type;
use PChapl\DoctrineIdBundle\Id\TypeFactory;
use PChapl\Tests\TestId;
use PHPUnit\Framework\TestCase;
use stdClass;

class TypeTest extends TestCase
{
    private const TEST_ID_TYPE = 'test_id_type';
    private const TEST_ID_VALUE = 'test-id-value';

    private Type $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = TypeFactory::instantiateType(TestId::class, self::TEST_ID_TYPE);
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testGetName(): void
    {
        self::assertSame(self::TEST_ID_TYPE, $this->type->getName());
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }

    public function testConvertToPHPValue(): void
    {
        $id = TestId::fromValue(self::TEST_ID_VALUE);
        $same = $this->type->convertToPHPValue($id, $this->platform);
        self::assertSame($id, $same);

        $new = $this->type->convertToPHPValue(self::TEST_ID_VALUE, $this->platform);
        self::assertInstanceOf(TestId::class, $new);
        self::assertSame(self::TEST_ID_VALUE, $new->getValue());

        $null = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($null);
    }

    public function testConvertToDatabaseValue(): void
    {
        $null = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($null);

        $value = $this->type->convertToDatabaseValue(TestId::fromValue(self::TEST_ID_VALUE), $this->platform);
        self::assertSame(self::TEST_ID_VALUE, $value);
    }

    public function testConvertToDatabaseValueException(): void
    {
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('"value" is not an instance of ' . Base::class);

        $this->type->convertToDatabaseValue(new stdClass(), $this->platform);
    }
}
