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

            'departments.view',
            'departments.create',
            'departments.update',
            'departments.delete',

        ],
    ],

    'hr' => [
        'permissions' => [
            'employees.view',
            'employees.create',
            'employees.update',
            'employees.delete',
        ],
    ],

    'employee' => [
        'permissions' => [],
    ],
];
