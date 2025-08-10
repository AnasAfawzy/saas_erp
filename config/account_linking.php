<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Account Linking System Configuration
    |--------------------------------------------------------------------------
    |
    | تكوين نظام الربط التلقائي بين الكيانات ودليل الحسابات
    |
    */

    // الإعدادات العامة
    'auto_create_accounts' => env('AUTO_CREATE_ACCOUNTS', true),
    'auto_sync_enabled' => env('AUTO_SYNC_ACCOUNTS', true),
    'sync_frequency' => env('ACCOUNT_SYNC_FREQUENCY', 'real_time'), // real_time, hourly, daily

    // إعدادات الحذف
    'delete_account_on_entity_delete' => env('DELETE_ACCOUNT_ON_ENTITY_DELETE', false),
    'deactivate_account_on_entity_delete' => env('DEACTIVATE_ACCOUNT_ON_ENTITY_DELETE', true),

    // إعدادات الأداء
    'enable_caching' => env('ACCOUNT_LINKING_CACHE', true),
    'cache_ttl' => env('ACCOUNT_LINKING_CACHE_TTL', 3600), // 1 hour

    // إعدادات السجلات
    'enable_logging' => env('ACCOUNT_LINKING_LOGGING', true),
    'log_level' => env('ACCOUNT_LINKING_LOG_LEVEL', 'info'),

    /*
    |--------------------------------------------------------------------------
    | Entities Configuration
    |--------------------------------------------------------------------------
    |
    | تكوين الكيانات القابلة للربط مع دليل الحسابات
    |
    */
    'entities' => [

        // العملاء
        'App\Models\Customer' => [
            'enabled' => true,
            'auto_create' => true,
            'auto_sync' => true,
            'parent_accounts' => [
                // حسابات أب مختلفة بناءً على نوع العميل
                'customer_type=individual' => 14, // حساب عملاء أفراد
                'customer_type=company' => 15,    // حساب عملاء شركات
            ],
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'sub_account',
            'code_prefix' => 'CUS',
            'sync_fields' => ['name', 'is_active'],
            'balance_field' => 'current_balance',
            'name_field' => 'name',
            'description_template' => 'حساب العميل: :name - كود: :code',
            'delete_account_on_entity_delete' => false,
        ],

        // الموردين - معطل مؤقتاً للتجربة
        'App\Models\Supplier' => [
            'enabled' => false,
            'auto_create' => false,
            'auto_sync' => false,
            'parent_accounts' => [
                'supplier_type=individual' => null, // سيتم تحديدها لاحقاً
                'supplier_type=company' => null,    // سيتم تحديدها لاحقاً
            ],
            'account_type' => 'liabilities',
            'account_nature' => 'credit',
            'account_level_type' => 'sub_account',
            'code_prefix' => 'SUP',
            'sync_fields' => ['name', 'is_active'],
            'balance_field' => 'current_balance',
            'name_field' => 'name',
            'description_template' => 'حساب المورد: :name - كود: :code',
            'delete_account_on_entity_delete' => false,
        ],

        // المخازن - معطل مؤقتاً للتجربة
        'App\Models\Warehouse' => [
            'enabled' => false,
            'auto_create' => false,
            'auto_sync' => false,
            'parent_account_id' => null, // سيتم تحديدها لاحقاً
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'sub_account',
            'code_prefix' => 'WHE',
            'sync_fields' => ['name', 'is_active'],
            'balance_field' => null, // المخازن قد لا تحتاج رصيد مالي
            'name_field' => 'name',
            'description_template' => 'حساب المخزن: :name - كود: :code',
            'delete_account_on_entity_delete' => false,
        ],

        // البنوك - معطل مؤقتاً للتجربة
        'App\Models\Bank' => [
            'enabled' => false,
            'auto_create' => true,
            'auto_sync' => true,
            'parent_account_id' => null, // سيتم تحديدها لاحقاً
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'sub_account',
            'code_prefix' => 'BNK',
            'sync_fields' => ['name', 'is_active'],
            'balance_field' => 'current_balance',
            'name_field' => 'name',
            'description_template' => 'حساب البنك: :name - رقم الحساب: :account_number',
            'delete_account_on_entity_delete' => false,
        ],

        // الفروع - معطل مؤقتاً للتجربة
        'App\Models\Branch' => [
            'enabled' => false,
            'auto_create' => false, // قد لا نحتاج إنشاء تلقائي للفروع
            'auto_sync' => true,
            'parent_account_id' => null, // سيتم تحديدها لاحقاً
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'sub_account',
            'code_prefix' => 'BRN',
            'sync_fields' => ['name', 'is_active'],
            'balance_field' => null,
            'name_field' => 'name',
            'description_template' => 'حساب الفرع: :name - كود: :code',
            'delete_account_on_entity_delete' => false,
        ],

        // فئات المنتجات (اختياري)
        'App\Models\Category' => [
            'enabled' => false, // معطل افتراضياً
            'auto_create' => false,
            'auto_sync' => false,
            'parent_account_id' => null,
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'sub_account',
            'code_prefix' => 'CAT',
            'sync_fields' => ['name', 'is_active'],
            'balance_field' => null,
            'name_field' => 'name',
            'description_template' => 'حساب فئة المنتجات: :name',
            'delete_account_on_entity_delete' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Parent Accounts Structure
    |--------------------------------------------------------------------------
    |
    | هيكل الحسابات الأب في دليل الحسابات
    | سيتم إنشاء هذه الحسابات تلقائياً إذا لم تكن موجودة
    |
    */
    'parent_accounts_structure' => [
        // حسابات العملاء
        'customers' => [
            'code' => 'CUS-MAIN',
            'name' => 'العملاء',
            'name_en' => 'Customers',
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'title',
            'children' => [
                'customers_individual' => [
                    'code' => 'CUS-IND',
                    'name' => 'عملاء أفراد',
                    'name_en' => 'Individual Customers',
                    'account_level_type' => 'account',
                ],
                'customers_companies' => [
                    'code' => 'CUS-COM',
                    'name' => 'عملاء شركات',
                    'name_en' => 'Corporate Customers',
                    'account_level_type' => 'account',
                ],
            ],
        ],

        // حسابات الموردين
        'suppliers' => [
            'code' => 'SUP-MAIN',
            'name' => 'الموردين',
            'name_en' => 'Suppliers',
            'account_type' => 'liabilities',
            'account_nature' => 'credit',
            'account_level_type' => 'title',
            'children' => [
                'suppliers_individual' => [
                    'code' => 'SUP-IND',
                    'name' => 'موردين أفراد',
                    'name_en' => 'Individual Suppliers',
                    'account_level_type' => 'account',
                ],
                'suppliers_companies' => [
                    'code' => 'SUP-COM',
                    'name' => 'موردين شركات',
                    'name_en' => 'Corporate Suppliers',
                    'account_level_type' => 'account',
                ],
            ],
        ],

        // حسابات المخازن
        'warehouses' => [
            'code' => 'WHE-MAIN',
            'name' => 'المخازن',
            'name_en' => 'Warehouses',
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'title',
        ],

        // حسابات البنوك
        'banks' => [
            'code' => 'BNK-MAIN',
            'name' => 'البنوك والنقدية',
            'name_en' => 'Banks and Cash',
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'title',
        ],

        // حسابات الفروع
        'branches' => [
            'code' => 'BRN-MAIN',
            'name' => 'الفروع',
            'name_en' => 'Branches',
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'title',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Events and Listeners
    |--------------------------------------------------------------------------
    |
    | إعدادات الأحداث والمستمعين
    |
    */
    'events' => [
        'entity_created' => \App\Events\EntityCreated::class,
        'entity_updated' => \App\Events\EntityUpdated::class,
        'entity_deleted' => \App\Events\EntityDeleted::class,
        'account_linked' => \App\Events\AccountLinked::class,
        'account_synced' => \App\Events\AccountSynced::class,
    ],

    'listeners' => [
        \App\Events\EntityCreated::class => [
            \App\Listeners\CreateAccountForEntity::class,
        ],
        \App\Events\EntityUpdated::class => [
            \App\Listeners\SyncAccountWithEntity::class,
        ],
        \App\Events\EntityDeleted::class => [
            \App\Listeners\HandleEntityAccountOnDelete::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | قواعد التحقق من صحة البيانات
    |
    */
    'validation' => [
        'account_code' => 'required|string|max:20|unique:accounts,code',
        'account_name' => 'required|string|max:255',
        'entity_exists' => true, // التحقق من وجود الكيان قبل الربط
        'prevent_duplicate_linking' => true, // منع الربط المزدوج
    ],
];
