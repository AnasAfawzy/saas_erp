<?php

require_once __DIR__ . '/vendor/autoload.php';

// بدء تطبيق Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Customer;
use App\Models\Account;
use App\Models\AccountableAccount;

echo "📊 تقرير نظام الربط التلقائي النهائي\n";
echo "Final Account Linking System Report\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// عرض هيكل الحسابات
echo "🏗️ هيكل الحسابات - Account Structure:\n";
echo "-" . str_repeat("-", 40) . "\n";

$customerAccounts = Account::whereIn('code', ['CUS-MAIN', 'CUS-IND', 'CUS-COM'])
    ->orderBy('level')
    ->get();

foreach ($customerAccounts as $account) {
    $indent = str_repeat('  ', $account->level - 1);
    $icon = match ($account->account_level_type) {
        'title' => '📊',
        'account' => '📋',
        'sub_account' => '🔸',
        default => '•'
    };

    echo "{$indent}{$icon} {$account->code} - {$account->name} ({$account->account_level_type})\n";
}

echo "\n";

// عرض العملاء وحساباتهم
echo "👥 العملاء وحساباتهم - Customers and Their Accounts:\n";
echo "-" . str_repeat("-", 50) . "\n";

$customers = Customer::with(['accountableAccount.account.parent'])->get();

foreach ($customers as $customer) {
    $hasAccount = $customer->hasAccount();
    $accountInfo = $hasAccount ? $customer->account : null;

    echo "👤 {$customer->code} - {$customer->name} ({$customer->customer_type})\n";
    echo "   💰 رصيد العميل: " . number_format($customer->current_balance, 2) . " ريال\n";

    if ($hasAccount) {
        echo "   ✅ مرتبط بالحساب: {$accountInfo->code} - {$accountInfo->name}\n";
        echo "   💼 الحساب الأب: " . ($accountInfo->parent ? $accountInfo->parent->name : 'لا يوجد') . "\n";
        echo "   📊 رصيد الحساب: " . number_format($accountInfo->balance, 2) . " ريال\n";

        $linkingInfo = $customer->accountableAccount;
        echo "   🕒 آخر مزامنة: " . $linkingInfo->last_sync_at->diffForHumans() . "\n";
        echo "   🤖 إنشاء تلقائي: " . ($linkingInfo->auto_created ? 'نعم' : 'لا') . "\n";
    } else {
        echo "   ❌ غير مرتبط بحساب\n";
    }

    echo "\n";
}

// إحصائيات عامة
echo "📈 الإحصائيات العامة - General Statistics:\n";
echo "-" . str_repeat("-", 40) . "\n";

$totalCustomers = Customer::count();
$linkedCustomers = Customer::has('accountableAccount')->count();
$totalAccounts = Account::count();
$totalLinkings = AccountableAccount::count();

echo "👥 إجمالي العملاء: {$totalCustomers}\n";
echo "🔗 العملاء المربوطين: {$linkedCustomers}\n";
echo "📊 إجمالي الحسابات: {$totalAccounts}\n";
echo "🔗 إجمالي الروابط: {$totalLinkings}\n";
echo "📈 نسبة الربط: " . round(($linkedCustomers / $totalCustomers) * 100, 2) . "%\n";

echo "\n";

// نظرة على الحسابات الفرعية للعملاء
echo "🔍 الحسابات الفرعية للعملاء - Customer Sub-Accounts:\n";
echo "-" . str_repeat("-", 50) . "\n";

$customerSubAccounts = Account::where('account_level_type', 'sub_account')
    ->where('code', 'LIKE', 'CUS-%')
    ->with('parent')
    ->get();

$individualCount = 0;
$companyCount = 0;

foreach ($customerSubAccounts as $account) {
    $parentName = $account->parent ? $account->parent->name : 'لا يوجد';
    echo "🔸 {$account->code} - {$account->name}\n";
    echo "   📋 الحساب الأب: {$parentName}\n";
    echo "   💰 الرصيد: " . number_format($account->balance, 2) . " ريال\n";
    echo "   📊 المستوى: {$account->level}\n";

    if ($account->parent && $account->parent->code === 'CUS-IND') {
        $individualCount++;
    } elseif ($account->parent && $account->parent->code === 'CUS-COM') {
        $companyCount++;
    }

    echo "\n";
}

echo "📊 ملخص التوزيع:\n";
echo "👤 عملاء أفراد: {$individualCount}\n";
echo "🏢 عملاء شركات: {$companyCount}\n";

echo "\n";
echo "🎉 تم اكتمال نظام الربط التلقائي بنجاح!\n";
echo "Account Linking System Setup Completed Successfully!\n";
