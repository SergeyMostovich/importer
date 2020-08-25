<?php


use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use TestTask\Import\Csv\RowValidator;

class RowValidatorTest extends TestCase
{

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_wrongSize()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertNotTrue(
            $validator->validateRow([['q', 'b']], 1)
        );
    }

    private function mockFieldSet()
    {
        return [
            'name' => [
                'length' => 32,
                'type' => 'varchar',
                'nullable' => false,
            ],
        ];
    }

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_wrongLength()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertNotTrue(
            $validator->validateRow([str_repeat('a', 33)], 1)
        );
    }

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_wrongType()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertNotTrue(
            $validator->validateRow([false], 1)
        );

    }

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_wrongNullable()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertNotTrue(
            $validator->validateRow([''], 1)
        );
    }

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_correctLength()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertTrue(
            $validator->validateRow(['qwe'], 1)
        );
    }

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_correctType()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertTrue(
            $validator->validateRow(['qwe'], 1)
        );
    }

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_correctNullable()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertTrue(
            $validator->validateRow(['qwe'], 1)
        );
    }

    /**
     * @covers \TestTask\Import\Csv\RowValidator::validateRow
     */
    public function testValidateRow_correctSize()
    {
        $validator = new RowValidator(new Logger('RowValidatorTest'), $this->mockFieldSet());
        $this->assertTrue(
            $validator->validateRow(['q'], 1)
        );
    }


}
