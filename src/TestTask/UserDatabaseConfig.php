<?php


namespace TestTask;


abstract class UserDatabaseConfig
{
    const FIELD_SET = [
        'id' => [
            'length' => 11,
            'type' => 'int',
            'nullable' => false,
        ],
        'name' => [
            'length' => 32,
            'type' => 'varchar',
            'nullable' => false,
        ],
        'email' => [
            'length' => 254,
            'type' => 'varchar',
            'nullable' => false,
        ],
        'currency' => [
            'length' => 3,
            'type' => 'char',
            'nullable' => false,
        ],
        'total' => [
            'length' => 15,
            'type' => 'decimal',
            'nullable' => false,
        ],
    ];
    const PRIMARY_KEY = 'id';
    const TABLE = 'users';

}