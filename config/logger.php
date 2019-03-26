<?php

use Sourcetoad\Logger\Enums\ModelMapping;

return [
    /*
    |--------------------------------------------------------------------------
    | User Table
    |--------------------------------------------------------------------------
    |
    | The default user information used to create foreign keys in migrations.
    |
    */

    'user' => [
        'table'       => 'users',
        'foreign_key' => 'id',
    ],

    'morphs' => [
        ModelMapping::USER => 'App\User'
    ]
];