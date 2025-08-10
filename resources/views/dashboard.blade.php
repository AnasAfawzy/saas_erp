@extends('layouts.main')

@section('title', __('app.dashboard'))

@section('content')
    <div class="container-fluid">
        <!-- ترحيب المستخدم -->
        <div class="page-header d-print-none">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('app.welcome') }}
                    </div>
                    <h2 class="page-title">
                        {{ Auth::user()->name }}
                    </h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            إضافة جديد
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقات الإحصائيات -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">إجمالي المستخدمين</div>
                            <div class="ms-auto lh-1">
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown">آخر 7
                                        أيام</a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">آخر 7 أيام</a>
                                        <a class="dropdown-item" href="#">آخر 30 يوم</a>
                                        <a class="dropdown-item" href="#">آخر 3 أشهر</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ App\Models\User::count() }}</div>
                        <div class="d-flex mb-2">
                            <div>معدل النمو</div>
                            <div class="ms-auto">
                                <span class="text-green d-inline-flex align-items-center lh-1">
                                    +5%
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <polyline points="3,17 9,11 13,15 21,7"></polyline>
                                        <polyline points="14,7 21,7 21,14"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-blue" style="width: 75%" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">المستخدمين النشطين</div>
                            <div class="ms-auto lh-1">
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown">آخر 7
                                        أيام</a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">آخر 7 أيام</a>
                                        <a class="dropdown-item" href="#">آخر 30 يوم</a>
                                        <a class="dropdown-item" href="#">آخر 3 أشهر</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ App\Models\User::count() }}</div>
                        <div class="d-flex mb-2">
                            <div>معدل النشاط</div>
                            <div class="ms-auto">
                                <span class="text-green d-inline-flex align-items-center lh-1">
                                    +12%
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <polyline points="3,17 9,11 13,15 21,7"></polyline>
                                        <polyline points="14,7 21,7 21,14"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" style="width: 87%" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">الأدوار</div>
                        </div>
                        <div class="h1 mb-3">{{ App\Models\Role::count() }}</div>
                        <div class="d-flex mb-2">
                            <div>مجموع الأدوار المتاحة</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-yellow" style="width: 60%" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">الصلاحيات</div>
                        </div>
                        <div class="h1 mb-3">{{ App\Models\Permission::count() }}</div>
                        <div class="d-flex mb-2">
                            <div>مجموع الصلاحيات</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-red" style="width: 40%" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- محتوى الصفحة الرئيسي -->
        <div class="row row-deck row-cards">
            <!-- معلومات المستخدم -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">معلومات حسابك</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الاسم</label>
                                    <div class="form-control-plaintext">{{ Auth::user()->name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <div class="form-control-plaintext">{{ Auth::user()->email }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">تاريخ التسجيل</label>
                                    <div class="form-control-plaintext">{{ Auth::user()->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الدور</label>
                                    <div class="form-control-plaintext">
                                        @if (Auth::user()->roles->count() > 0)
                                            <span class="badge bg-blue">{{ Auth::user()->roles->first()->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">غير محدد</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">آخر دخول</label>
                                    <div class="form-control-plaintext">الآن</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">الحالة</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-green">نشط</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- إجراءات سريعة -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">إجراءات سريعة</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <span class="avatar me-3 bg-blue-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                    </svg>
                                </span>
                                إضافة مستخدم جديد
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <span class="avatar me-3 bg-green-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M9 11h6l-3 3z"></path>
                                        <path
                                            d="M4 4h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2v-8a2 2 0 0 1 2 -2z">
                                        </path>
                                        <path d="M4 15v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                    </svg>
                                </span>
                                عرض التقارير
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <span class="avatar me-3 bg-yellow-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M12 1v6m0 6v6m11 -7h-6m-6 0h-6"></path>
                                    </svg>
                                </span>
                                إدارة الأدوار
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <span class="avatar me-3 bg-red-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </span>
                                الإعدادات العامة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
