# CurrencyHelper - دليل الاستخدام

## نظرة عامة

CurrencyHelper هو نظام شامل لتنسيق وعرض الأرقام والعملات في التطبيق بناءً على إعدادات الشركة (`decimal_places` و `currency`).

## الميزات الرئيسية

### 1. Functions (الدوال العامة)

-   `format_currency($amount, $showCurrency = true)` - تنسيق العملة
-   `format_balance($balance, $showCurrency = false)` - تنسيق الرصيد
-   `format_number($number, $decimals = null)` - تنسيق الأرقام
-   `format_customer_balance($balance, $returnArray = false)` - تنسيق رصيد العملاء
-   `format_supplier_balance($balance, $returnArray = false)` - تنسيق رصيد الموردين
-   `format_account_balance($balance, $accountType = 'asset', $returnArray = false)` - تنسيق رصيد الحسابات

### 2. Blade Directives (توجيهات Blade)

-   `@currency($amount)` - عرض العملة
-   `@balance($amount)` - عرض الرصيد
-   `@number($amount)` - عرض الرقم
-   `@accountBalance($balance)` - عرض رصيد الحساب مع التنسيق
-   `@customerBalance($balance)` - عرض رصيد العميل مع الألوان
-   `@supplierBalance($balance)` - عرض رصيد المورد مع الألوان

## أمثلة الاستخدام

### 1. في Blade Templates

```blade
<!-- استخدام Functions -->
<td>{{ format_balance($account->balance) }}</td>
<td>{{ format_currency($product->price, true) }}</td>
<td>{{ format_number($quantity, 0) }}</td>

<!-- استخدام Blade Directives -->
<td>@balance($account->balance)</td>
<td>@currency($product->price)</td>
<td>@accountBalance($account->balance)</td>

<!-- رصيد العملاء مع الألوان -->
<td>@customerBalance($customer->balance)</td>

<!-- رصيد الموردين مع الألوان -->
<td>@supplierBalance($supplier->balance)</td>
```

### 2. في PHP (Controllers/Services)

```php
use App\Helpers\CurrencyHelper;

// تنسيق بسيط
$formatted = CurrencyHelper::formatCurrency(1250.567, true);
// النتيجة: "1,250.57 SAR" (حسب decimal_places في الإعدادات)

// تنسيق رصيد العميل مع معلومات إضافية
$customerBalance = CurrencyHelper::formatCustomerBalance(-500.75, true);
/*
النتيجة:
[
    'text' => '500.75 SAR',
    'class' => 'text-danger',
    'label' => 'رصيد مدين (عليه)',
    'amount' => '500.75'
]
*/

// تنسيق رصيد الحساب حسب نوعه
$accountBalance = CurrencyHelper::formatAccountBalance(1000.5, 'asset', true);
/*
النتيجة:
[
    'text' => '1,000.50',
    'class' => 'text-success',
    'amount' => '1,000.50',
    'sign' => ''
]
*/
```

### 3. في Livewire Components

```php
// في accounts-table.blade.php
@php
    $balanceData = format_account_balance(
        $account->balance,
        $account->account_nature ?? 'asset',
        true
    );
@endphp
<span class="fw-bold {{ $balanceData['class'] }}">
    {{ $balanceData['text'] }}
</span>
```

## أنواع الأرصدة والألوان

### رصيد العملاء

-   **أخضر (text-success)**: العميل له رصيد (الشركة مدينة للعميل)
-   **أحمر (text-danger)**: العميل عليه رصيد (العميل مدين للشركة)
-   **رمادي (text-muted)**: لا يوجد رصيد

### رصيد الموردين

-   **أحمر (text-danger)**: المورد له رصيد (مستحق للمورد)
-   **أخضر (text-success)**: المورد عليه رصيد (مستحق من المورد)
-   **رمادي (text-muted)**: لا يوجد رصيد

### أرصدة الحسابات

يعتمد اللون على نوع الحساب:

-   **الأصول والمصروفات**: موجب = أخضر، سالب = أحمر
-   **الخصوم وحقوق الملكية والإيرادات**: سالب = أخضر، موجب = أحمر

## الإعدادات المطلوبة

تأكد من وجود البيانات التالية في جدول `company_settings`:

-   `decimal_places`: عدد الخانات العشرية (افتراضي: 2)
-   `currency`: رمز العملة (افتراضي: 'SAR')

## فوائد الاستخدام

1. **توحيد التنسيق**: جميع الأرقام تظهر بنفس التنسيق في كل التطبيق
2. **سهولة التغيير**: تغيير إعدادات الشركة يؤثر على كل التطبيق
3. **أداء محسن**: cache للإعدادات لتجنب الاستعلامات المتكررة
4. **وضوح بصري**: ألوان مختلفة حسب نوع الرصيد
5. **مرونة الاستخدام**: يمكن استخدامه كـ functions أو Blade directives

## ملاحظات مهمة

-   Helper يحفظ الإعدادات في cache لتحسين الأداء
-   يمكن تمرير `decimal_places` مخصص لكل استدعاء
-   جميع Functions تتعامل مع القيم `null` بأمان
-   Blade Directives أسرع من استخدام `@php` blocks
