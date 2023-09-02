<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'owner' => [
            'users' => cruds(),
            'profile' => 'r,u',
            'admins' => cruds('i,e'),
            'tourists' => cruds('i,e'),
            'tourguides' => cruds('i,e'),
            'packages' => cruds('i,e'),
            'settings' => cruds(),
        ],
        'admin' => [],
        'tourist' => [],
        'tourguide' => []
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'i' => 'import',
        'e' => 'export'
    ]
];
