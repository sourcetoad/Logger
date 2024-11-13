<?php

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
        'table' => 'users',
        'foreign_key' => 'id',
        'foreign_key_type' => 'bigInteger',
    ],

    /*
    |--------------------------------------------------------------------------
    | Morphs
    |--------------------------------------------------------------------------
    |
    | A model to integer binding, so the compacted database can store ints
    | vs larger data-types like fully qualified namespaces.
    |
    */
    'morphs' => [
        0 => App\Models\User::class,
    ]
];
