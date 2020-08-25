<?php

namespace TestTask\Import\Csv;


use Monolog\Logger;
use TestTask\Import\NullableException;
use TestTask\Import\RowValidatorInterface;
use TestTask\Import\WrongLengthException;
use TestTask\Import\WrongTypeException;

class RowValidator implements RowValidatorInterface
{

    private int $fieldsCount;
    private array $fieldsList;
    private array $fieldSet;
    private Logger $logger;

    public function __construct(Logger $logger, array $fieldSet)
    {
        $this->logger = $logger;
        $this->fieldSet = $fieldSet;
        $this->fieldsCount = count($fieldSet);
        $this->fieldsList = array_keys($fieldSet);
    }

    public function validateRow(array $row, int $line): bool
    {

        try {
            if (!$this->checkSize(count($row), $this->fieldsCount)) {
                throw new \Exception(sprintf('Invalid row in line %s', $line).PHP_EOL);
            }
            foreach ($row as $k => $field) {
                $rowName = $this->fieldsList[$k];
                $rowData = $this->fieldSet[$rowName];
                if (!$this->validateLength(strlen($field), $rowData['length'])) {
                    throw new WrongLengthException(
                        sprintf('Invalid length for column %s in line %s'.PHP_EOL, $rowName, $line)
                    );
                }

                if (!$this->validateType($field, $rowData['type'])) {
                    throw new WrongTypeException(
                        sprintf('Invalid type for column %s in line %s'.PHP_EOL, $rowName, $line)
                    );
                }
                if (!$this->validateNullable($field, $rowData['nullable'])) {
                    throw new NullableException(
                        sprintf('Column %s can\'t be empty in line %s'.PHP_EOL, $rowName, $line)
                    );
                }

            }

            return true;
        } catch (\Exception $e) {
            $this->logger->warning($e->getMessage());

            return false;
        }
    }

    private function checkSize(int $row, int $fieldsCount): bool
    {
        return $row === $fieldsCount;
    }

    private function validateLength(int $fieldLength, int $length): bool
    {
        return $fieldLength <= $length;
    }

    private function validateType(string $field, string $type): bool
    {
        switch ($type) {
            case 'int':
                $state = $this->isInt($field);
                break;
            case 'decimal':
                $state = $this->isFloat($field);
                break;
            case 'char':
            case 'varchar':
            default:
                $state = is_string($field);
                break;
        }

        return $state;
    }

    private function isInt(string $string): bool
    {
        return ($string === (string)(int)$string);
    }

    private function isFloat(string $string): bool
    {
        return ($string === (string)(float)$string);
    }

    private function validateNullable(string $field, bool $nullable): bool
    {
        if ($nullable) {
            return true;
        } else {
            return !$this->isNullOrEmpty($field);
        }
    }

    private function isNullOrEmpty(string $field): bool
    {
        return empty($field) || is_null($field);
    }
}
