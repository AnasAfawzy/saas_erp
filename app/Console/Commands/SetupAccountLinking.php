<?php

namespace App\Console\Commands;

use App\Services\AccountStructureService;
use App\Services\AccountLinkingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetupAccountLinking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account-linking:setup
                            {--check : Check current setup status}
                            {--create-structure : Create basic account structure}
                            {--link-existing : Link existing entities to accounts}
                            {--entity= : Specify entity type to link (Customer, Supplier, etc.)}
                            {--force : Force creation even if accounts exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the Account Linking System - إعداد نظام الربط التلقائي للحسابات';

    protected AccountStructureService $structureService;
    protected AccountLinkingService $linkingService;

    public function __construct(
        AccountStructureService $structureService,
        AccountLinkingService $linkingService
    ) {
        parent::__construct();
        $this->structureService = $structureService;
        $this->linkingService = $linkingService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 مرحباً بك في نظام الربط التلقائي للحسابات');
        $this->info('Welcome to Account Linking System Setup');
        $this->newLine();

        try {
            // فحص الحالة الحالية
            if ($this->option('check')) {
                return $this->checkSetupStatus();
            }

            // إنشاء هيكل الحسابات الأساسي
            if ($this->option('create-structure')) {
                return $this->createAccountStructure();
            }

            // ربط الكيانات الموجودة
            if ($this->option('link-existing')) {
                return $this->linkExistingEntities();
            }

            // إذا لم يتم تحديد خيار، عرض القائمة التفاعلية
            return $this->showInteractiveMenu();
        } catch (\Exception $e) {
            $this->error('❌ خطأ في تنفيذ الأمر: ' . $e->getMessage());
            $this->error('Error executing command: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * عرض القائمة التفاعلية
     */
    protected function showInteractiveMenu(): int
    {
        $choice = $this->choice(
            'ما الذي تريد فعله؟ What would you like to do?',
            [
                'check' => '🔍 فحص حالة النظام - Check system status',
                'structure' => '🏗️ إنشاء هيكل الحسابات - Create account structure',
                'link' => '🔗 ربط الكيانات الموجودة - Link existing entities',
                'full' => '⚡ الإعداد الكامل - Full setup',
                'exit' => '❌ خروج - Exit'
            ],
            'check'
        );

        return match ($choice) {
            'check' => $this->checkSetupStatus(),
            'structure' => $this->createAccountStructure(),
            'link' => $this->linkExistingEntities(),
            'full' => $this->fullSetup(),
            'exit' => Command::SUCCESS,
            default => Command::SUCCESS,
        };
    }

    /**
     * فحص حالة الإعداد
     */
    protected function checkSetupStatus(): int
    {
        $this->info('🔍 فحص حالة نظام الربط...');
        $this->info('Checking Account Linking System status...');
        $this->newLine();

        // فحص قاعدة البيانات
        $this->checkDatabase();

        // فحص هيكل الحسابات
        $this->checkAccountStructure();

        // فحص إحصائيات الربط
        $this->checkLinkingStatistics();

        return Command::SUCCESS;
    }

    /**
     * فحص قاعدة البيانات
     */
    protected function checkDatabase(): void
    {
        $this->info('📊 فحص قاعدة البيانات - Database Check:');

        try {
            // فحص جدول accounts
            $accountsCount = DB::table('accounts')->count();
            $this->line("  ✅ جدول الحسابات: {$accountsCount} حساب");

            // فحص جدول accountable_accounts
            if (DB::getSchemaBuilder()->hasTable('accountable_accounts')) {
                $linkingCount = DB::table('accountable_accounts')->count();
                $this->line("  ✅ جدول الربط: {$linkingCount} ربط");
            } else {
                $this->warn('  ⚠️  جدول الربط غير موجود - يجب تشغيل Migration');
                $this->warn('  ⚠️  Linking table not found - Run migrations');
            }
        } catch (\Exception $e) {
            $this->error('  ❌ خطأ في فحص قاعدة البيانات: ' . $e->getMessage());
        }

        $this->newLine();
    }

    /**
     * فحص هيكل الحسابات
     */
    protected function checkAccountStructure(): void
    {
        $this->info('🏗️ فحص هيكل الحسابات - Account Structure Check:');

        $stats = $this->structureService->getStructureStatistics();
        $structure = $this->structureService->checkBasicStructureExists();

        $this->line("  📈 إجمالي الحسابات المطلوبة: {$stats['total_accounts']}");
        $this->line("  ✅ الحسابات الموجودة: {$stats['existing_accounts']}");
        $this->line("  ❌ الحسابات المفقودة: {$stats['missing_accounts']}");
        $this->line("  📊 نسبة الإكمال: {$stats['completion_percentage']}%");

        if ($stats['is_complete']) {
            $this->info('  🎉 هيكل الحسابات مكتمل!');
        } else {
            $this->warn('  ⚠️  هيكل الحسابات غير مكتمل');
        }

        $this->newLine();
    }

    /**
     * فحص إحصائيات الربط
     */
    protected function checkLinkingStatistics(): void
    {
        $this->info('🔗 إحصائيات الربط - Linking Statistics:');

        try {
            $stats = $this->linkingService->getLinkingStatistics();

            foreach ($stats as $entityClass => $stat) {
                $entityName = class_basename($entityClass);
                $this->line("  📋 {$entityName}:");
                $this->line("    - الإجمالي: {$stat['total']}");
                $this->line("    - مربوط: {$stat['linked']} ({$stat['percentage']}%)");
                $this->line("    - غير مربوط: {$stat['unlinked']}");
            }
        } catch (\Exception $e) {
            $this->warn('  ⚠️  لا يمكن الحصول على إحصائيات الربط');
        }

        $this->newLine();
    }

    /**
     * إنشاء هيكل الحسابات الأساسي
     */
    protected function createAccountStructure(): int
    {
        $this->info('🏗️ إنشاء هيكل الحسابات الأساسي...');
        $this->info('Creating basic account structure...');

        // فحص الحالة الحالية
        $stats = $this->structureService->getStructureStatistics();

        if ($stats['is_complete'] && !$this->option('force')) {
            $this->warn('⚠️  هيكل الحسابات موجود بالفعل. استخدم --force للإنشاء القسري');
            return Command::SUCCESS;
        }

        try {
            $this->withProgressBar(['إنشاء الحسابات الأساسية...'], function () {
                $results = $this->structureService->createBasicAccountStructure();
                $this->structureService->updateEntityConfigs();
            });

            $this->newLine(2);
            $this->info('✅ تم إنشاء هيكل الحسابات بنجاح!');
            $this->info('Account structure created successfully!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ فشل في إنشاء هيكل الحسابات: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * ربط الكيانات الموجودة
     */
    protected function linkExistingEntities(): int
    {
        $this->info('🔗 ربط الكيانات الموجودة بدليل الحسابات...');
        $this->info('Linking existing entities to chart of accounts...');

        $entityTypes = [
            'App\Models\Customer' => 'العملاء - Customers',
            'App\Models\Supplier' => 'الموردين - Suppliers',
            'App\Models\Warehouse' => 'المخازن - Warehouses',
            'App\Models\Bank' => 'البنوك - Banks',
        ];

        $selectedEntity = $this->option('entity');

        if ($selectedEntity) {
            $entityClass = "App\\Models\\{$selectedEntity}";
            if (!array_key_exists($entityClass, $entityTypes)) {
                $this->error("❌ نوع كيان غير صالح: {$selectedEntity}");
                return Command::FAILURE;
            }

            return $this->linkEntityType($entityClass);
        }

        // ربط جميع الأنواع
        foreach ($entityTypes as $entityClass => $name) {
            $this->info("🔗 ربط {$name}...");
            $this->linkEntityType($entityClass);
            $this->newLine();
        }

        $this->info('✅ تم ربط جميع الكيانات بنجاح!');
        return Command::SUCCESS;
    }

    /**
     * ربط نوع كيان محدد
     */
    protected function linkEntityType(string $entityClass): int
    {
        try {
            if (!class_exists($entityClass)) {
                $this->warn("⚠️  الكيان {$entityClass} غير موجود");
                return Command::SUCCESS;
            }

            $results = $this->linkingService->bulkCreateAccounts($entityClass);

            $this->line("  ✅ تم الربط بنجاح: " . count($results['success']));
            $this->line("  ⚠️  تم تخطيها: " . count($results['skipped']));
            $this->line("  ❌ فشلت: " . count($results['failed']));

            if (!empty($results['failed'])) {
                $this->warn("  المشاكل:");
                foreach ($results['failed'] as $failed) {
                    $this->line("    - ID {$failed['id']}: {$failed['error']}");
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ خطأ في ربط {$entityClass}: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * الإعداد الكامل
     */
    protected function fullSetup(): int
    {
        $this->info('⚡ بدء الإعداد الكامل لنظام الربط...');
        $this->info('Starting full Account Linking System setup...');
        $this->newLine();

        try {
            // 1. إنشاء هيكل الحسابات
            $this->info('1️⃣ إنشاء هيكل الحسابات...');
            $this->createAccountStructure();

            // 2. ربط الكيانات الموجودة
            $this->info('2️⃣ ربط الكيانات الموجودة...');
            $this->linkExistingEntities();

            // 3. عرض التقرير النهائي
            $this->info('3️⃣ تقرير الإعداد النهائي...');
            $this->checkSetupStatus();

            $this->info('🎉 تم الإعداد الكامل بنجاح!');
            $this->info('Full setup completed successfully!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ فشل الإعداد الكامل: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
