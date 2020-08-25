<?php


namespace TestTask\Import;


interface RowValidatorInterface
{
    public function validateRow(array $row, int $line): bool;
}