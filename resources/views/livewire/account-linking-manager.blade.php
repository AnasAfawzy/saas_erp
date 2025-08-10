<div>
    {{-- رأس الصفحة --}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">إدارة النظام</div>
                    <h2 class="page-title">
                        <i class="ti ti-link me-2"></i>
                        إدارة ربط الحسابات
                    </h2>
                    <div class="text-secondary mt-1">
                        ربط الكيانات (العملاء، الموردين، المخازن) بدليل الحسابات المحاسبي
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary"
                            @if (!empty($selectedEntities)) wire:click="openBulkModal"
                            @else
                                disabled @endif>
                            <i class="ti ti-link me-1"></i>
                            ربط مجمع ({{ count($selectedEntities) }})
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- المحتوى الرئيسي --}}
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">مربوط</div>
                                <div class="ms-auto lh-1">
                                    <span class="text-green">
                                        <i class="ti ti-link"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="h1 mb-3">{{ $this->linkingStatistics['linked'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">غير مربوط</div>
                                <div class="ms-auto lh-1">
                                    <span class="text-red">
                                        <i class="ti ti-unlink"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="h1 mb-3">{{ $this->linkingStatistics['unlinked'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">الحسابات المتاحة</div>
                                <div class="ms-auto lh-1">
                                    <span class="text-blue">
                                        <i class="ti ti-folder"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="h1 mb-3">{{ $this->availableAccounts->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- أدوات التصفية --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">البحث والتصفية</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="resetFilters">
                            <i class="ti ti-refresh me-1"></i>
                            إعادة تعيين
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">نوع الكيان</label>
                            <select class="form-select" wire:model.live="selectedEntityType">
                                @foreach ($entityTypes as $key => $type)
                                    <option value="{{ $key }}">{{ $type['icon'] }} {{ $type['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">البحث</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                <input type="text" class="form-control" placeholder="البحث بالاسم أو الكود..." wire:model.live.debounce.300ms="search">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">حالة الربط</label>
                            <select class="form-select" wire:model.live="linkingStatus">
                                <option value="all">جميع العناصر</option>
                                <option value="linked">مربوط فقط</option>
                                <option value="unlinked">غير مربوط فقط</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">نوع الحساب</label>
                            <select class="form-select" wire:model.live="selectedAccountType">
                                <option value="all">جميع الأنواع</option>
                                <option value="account">حساب عادي</option>
                                <option value="sub_account">حساب فرعي</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- جدول الكيانات --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $entityTypes[$selectedEntityType]['label'] ?? 'الكيانات' }}
                        <span class="badge bg-secondary ms-2">{{ $entities->total() }}</span>
                    </h3>
                    <div class="card-actions">
                        @if ($entities->count() > 0)
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" wire:model.live="selectAll">
                                <span class="form-check-label">تحديد الكل</span>
                            </label>
                        @endif
                    </div>
                </div>

                @if ($entities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input" type="checkbox" wire:model.live="selectAll"></th>
                                    <th>{{ $entityTypes[$selectedEntityType]['icon'] ?? '📋' }} الكيان</th>
                                    <th>🔗 حالة الربط</th>
                                    <th>💰 الرصيد</th>
                                    <th>📊 الحساب المرتبط</th>
                                    <th>🕒 آخر مزامنة</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entities as $entity)
                                    @php
                                        $hasAccount = $entity->accountableAccount ? true : false;
                                        $account = $hasAccount ? $entity->accountableAccount->account : null;
                                    @endphp
                                    <tr class="{{ $hasAccount ? 'table-success' : 'table-warning' }}">
                                        <td><input class="form-check-input" type="checkbox" wire:model.live="selectedEntities" value="{{ $entity->id }}"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="fw-bold">{{ $entity->name }}</div>
                                                    <div class="text-secondary">{{ $entity->code }}</div>
                                                    @if (isset($entity->customer_type))
                                                        <span class="badge badge-outline text-secondary mt-1">{{ $entity->customer_type === 'individual' ? '👤 فرد' : '🏢 شركة' }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($hasAccount)
                                                <span class="badge bg-success"><i class="ti ti-link me-1"></i> مربوط</span>
                                            @else
                                                <span class="badge bg-warning"><i class="ti ti-unlink me-1"></i> غير مربوط</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($entity->current_balance))
                                                <span class="text-{{ $entity->current_balance >= 0 ? 'success' : 'danger' }}">{{ number_format($entity->current_balance, 2) }} ريال</span>
                                            @else
                                                <span class="text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($hasAccount && $account)
                                                <div>
                                                    <div class="fw-bold">{{ $account->name }}</div>
                                                    <div class="text-secondary">{{ $account->code }}</div>
                                                    <span class="badge badge-outline text-blue mt-1">{{ $account->account_level_type === 'account' ? '📋 حساب عادي' : '🔸 حساب فرعي' }}</span>
                                                </div>
                                            @else
                                                <span class="text-secondary">غير مرتبط</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($hasAccount && $entity->accountableAccount->last_sync_at)
                                                <span class="text-secondary">{{ $entity->accountableAccount->last_sync_at->diffForHumans() }}</span>
                                            @else
                                                <span class="text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if ($hasAccount)
                                                    <button type="button" class="btn btn-sm btn-outline-success" wire:click="openLinkingModal({{ $entity->id }})" title="تغيير الربط"><i class="ti ti-edit"></i></button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="unlinkEntity({{ $entity->id }})" wire:confirm="هل أنت متأكد من إلغاء الربط؟" title="إلغاء الربط"><i class="ti ti-unlink"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openLinkingModal({{ $entity->id }})" title="ربط بحساب"><i class="ti ti-link"></i> ربط</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        {{ $entities->links() }}
                    </div>
                @else
                    <div class="empty">
                        <div class="empty-img"><img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjEyOCIgdmlld0JveD0iMCAwIDEyOCAxMjgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTY0IDE2QzM3LjQ5IDE2IDE2IDM3LjQ5IDE2IDY0UzM3LjQ5IDExMiA2NCAxMTJTMTEyIDkwLjUxIDExMiA2NFM5MC41MSAxNiA2NCAxNlpNNjQgOTZDNDYuMzMgOTYgMzIgODEuNjcgMzIgNjRTNDYuMzMgMzIgNjQgMzJTOTYgNDYuMzMgOTYgNjRTODEuNjcgOTYgNjQgOTZaIiBmaWxsPSIjQ0NFN0Y4Ii8+PC9zdmc+" alt=""></div>
                        <p class="empty-title">لا توجد نتائج</p>
                        <p class="empty-subtitle text-secondary">لم يتم العثور على {{ $entityTypes[$selectedEntityType]['label'] ?? 'كيانات' }} تطابق معايير البحث</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- نموذج الربط --}}
    <div class="modal modal-blur fade" id="linkingModal" tabindex="-1" @if ($showLinkingModal) style="display: block;" @endif wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-link me-2"></i> ربط بحساب محاسبي</h5>
                    <button type="button" class="btn-close" wire:click="closeLinkingModal"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedEntityId)
                        @php
                            $selectedEntity = null;
                            if ($selectedEntityType && isset($entityTypes[$selectedEntityType])) {
                                $selectedEntity = $entityTypes[$selectedEntityType]['model']::find($selectedEntityId);
                            }
                        @endphp
                        @if ($selectedEntity)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <div class="d-flex">
                                            <div><i class="ti ti-info-circle me-2"></i></div>
                                            <div>
                                                <h4>{{ $selectedEntity->name }}</h4>
                                                <div class="text-secondary">الكود: {{ $selectedEntity->code }} @if (isset($selectedEntity->current_balance)) | الرصيد: {{ number_format($selectedEntity->current_balance, 2) }} ريال @endif</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">اختيار الحساب المحاسبي</label>
                                <select class="form-select" wire:model="selectedAccountId">
                                    <option value="">-- اختر حساباً --</option>
                                    @foreach ($this->availableAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }} @if ($account->parent) (تحت: {{ $account->parent->name }}) @endif - {{ $account->account_level_type === 'account' ? 'حساب عادي' : 'حساب فرعي' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ملاحظات الربط (اختياري)</label>
                                <textarea class="form-control" rows="3" wire:model="linkingNotes" placeholder="أضف أي ملاحظات حول هذا الربط..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeLinkingModal">إلغاء</button>
                    <button type="button" class="btn btn-primary" wire:click="performLinking" @if (!$selectedAccountId) disabled @endif><i class="ti ti-link me-1"></i> تأكيد الربط</button>
                </div>
            </div>
        </div>
    </div>

    {{-- نموذج الربط المجمع --}}
    <div class="modal modal-blur fade" id="bulkModal" tabindex="-1" @if ($showBulkModal) style="display: block;" @endif wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-link me-2"></i> ربط مجمع - {{ count($selectedEntities) }} عنصر</h5>
                    <button type="button" class="btn-close" wire:click="closeBulkModal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <div><i class="ti ti-alert-triangle me-2"></i></div>
                            <div>
                                <strong>تحذير:</strong> سيتم ربط جميع العناصر المحددة ({{ count($selectedEntities) }}) بنفس الحساب المحاسبي. إذا كان أي عنصر مرتبط بالفعل، سيتم تغيير ربطه إلى الحساب الجديد.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">اختيار الحساب المحاسبي</label>
                                <select class="form-select" wire:model="bulkAccountId">
                                    <option value="">-- اختر حساباً --</option>
                                    @foreach ($this->availableAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }} @if ($account->parent) (تحت: {{ $account->parent->name }}) @endif - {{ $account->account_level_type === 'account' ? 'حساب عادي' : 'حساب فرعي' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeBulkModal">إلغاء</button>
                    <button type="button" class="btn btn-primary" wire:click="performBulkLinking" @if (!$bulkAccountId) disabled @endif><i class="ti ti-link me-1"></i> تأكيد الربط المجمع</button>
                </div>
            </div>
        </div>
    </div>

    {{-- تأثيرات الخلفية للنماذج --}}
    @if ($showLinkingModal || $showBulkModal)
        <div class="modal-backdrop fade show"></div>
    @endif

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('close-modal', () => {
                // You can add logic here to ensure modals are closed if needed
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    @this.call('closeLinkingModal');
                    @this.call('closeBulkModal');
                }
            });
        });
    </script>
</div>