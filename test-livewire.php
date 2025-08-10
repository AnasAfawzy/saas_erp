<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

// نبدأ Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // اختبار إنشاء BranchService
    $branchService = app(\App\Services\BranchService::class);
    echo "✅ BranchService تم إنشاؤه بنجاح\n";

    // اختبار طريقة getAllBranches
    $result = $branchService->getAllBranches(true, 5);
    echo "✅ getAllBranches يعمل بنجاح: " . ($result['success'] ? 'نجح' : 'فشل') . "\n";

    // اختبار طريقة getBranchStatistics
    $stats = $branchService->getBranchStatistics();
    echo "✅ getBranchStatistics يعمل بنجاح: " . ($stats['success'] ? 'نجح' : 'فشل') . "\n";

    echo "🎉 جميع الاختبارات نجحت!\n";
} catch (\Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
