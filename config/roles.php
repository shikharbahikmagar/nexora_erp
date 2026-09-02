<?php

return [
    'super_admin' => [
        'permissions' => '*',
    ],


    'company_admin' => [
        'permissions' => [
            'companies.view',
            'companies.create',
            'companies.update',
            'companies.delete',

            'branches.view',
            'branches.create',
            'branches.update',
            'branches.delete',

            'employees.view',
            'employees.create',
            'employees.update',
            'employees.delete',
        ],
    ],

    'hr' => [
        'permissions' => [],
    ],

    'employee' => [
        'permissions' => [],
    ],
];
