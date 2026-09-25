<?php

/*
|--------------------------------------------------------------------------
| Staff roles & permissions (management panel)
|--------------------------------------------------------------------------
|
| Every permission is registered as a Gate in AppServiceProvider, so use
| $user->can('leads.view') / middleware('can:leads.view') everywhere.
| A role's '*' grants every permission.
|
*/

return [

    'permissions' => [
        'leads.view' => 'View sales leads (connection, coverage, business, contact…)',
        'leads.manage' => 'Update, assign and add notes to sales leads',
        'leads.export' => 'Export leads to CSV',
        'leads.delete' => 'Delete leads and support tickets',
        'support.view' => 'View support tickets',
        'support.manage' => 'Update, assign and add notes to support tickets',
        'packages.manage' => 'Manage internet packages',
        'coverage.manage' => 'Manage coverage areas',
        'content.manage' => 'Manage news, FAQs, testimonials and promotions',
        'network.manage' => 'Update network status',
        'settings.manage' => 'Change website settings',
        'users.manage' => 'Manage staff accounts and roles',
        'audit.view' => 'View the audit log',
    ],

    'roles' => [
        'admin' => [
            'label' => 'Administrator',
            'description' => 'Full access, including staff accounts, settings and the audit log.',
            'permissions' => ['*'],
        ],
        'manager' => [
            'label' => 'Manager',
            'description' => 'Runs day-to-day operations: leads, support, packages, coverage and content.',
            'permissions' => [
                'leads.view', 'leads.manage', 'leads.export', 'leads.delete',
                'support.view', 'support.manage',
                'packages.manage', 'coverage.manage', 'content.manage', 'network.manage',
                'audit.view',
            ],
        ],
        'sales' => [
            'label' => 'Sales',
            'description' => 'Follows up connection, coverage and business leads.',
            'permissions' => ['leads.view', 'leads.manage', 'leads.export'],
        ],
        'support' => [
            'label' => 'Support',
            'description' => 'Handles support tickets and keeps network status up to date.',
            'permissions' => ['support.view', 'support.manage', 'network.manage'],
        ],
        'content' => [
            'label' => 'Content editor',
            'description' => 'Publishes news, FAQs, testimonials and promotions.',
            'permissions' => ['content.manage'],
        ],
    ],

];
