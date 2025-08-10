<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="ar" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <title>تسجيل الدخول - {{ config('app.name') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Google Fonts for Arabic -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo:300,400,500,600,700&subset=arabic">

    <!-- Tabler Core CSS -->
    <link href="{{ asset('tabler-dev/assets/css/dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler-dev/assets/css/dashboard.rtl.css') }}" rel="stylesheet" />

    <!-- Custom Arabic styles -->
    <style>
        body {
            font-family: 'Cairo', 'Source Sans Pro', sans-serif;
        }

        .form-label {
            font-weight: 500;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #495057;
        }

        .text-center.mb-6 {
            margin-bottom: 3rem !important;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            color: #206bc4;
            margin-top: 1rem;
        }

        .login-subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: #206bc4;
            border-color: #206bc4;
        }

        .btn-primary:hover {
            background-color: #1a5490;
            border-color: #1a5490;
        }

        .forgot-link {
            color: #206bc4;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #1a5490;
            text-decoration: underline;
        }

        /* RTL adjustments */
        .float-right {
            float: left !important;
        }

        .float-left {
            float: right !important;
        }

        .me-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }

        .ms-2 {
            margin-right: 0.5rem !important;
            margin-left: 0 !important;
        }

        .alert {
            text-align: right;
        }
    </style>
</head>

<body class="d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-6">
                <a href="{{ route('login') }}" class="navbar-brand">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-store"
                        width="44" height="44" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="3" y1="21" x2="21" y2="21"></line>
                        <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4">
                        </path>
                        <line x1="5" y1="21" x2="5" y2="10.85"></line>
                        <line x1="19" y1="21" x2="19" y2="10.85"></line>
                        <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                    </svg>
                </a>
                <h1 class="brand-title">نظام إدارة الأعمال</h1>
                <p class="login-subtitle">مرحباً بك، يرجى تسجيل الدخول للمتابعة</p>
            </div>

            <!-- عرض الأخطاء -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <div>
                            <h4 class="alert-title">خطأ في البيانات!</h4>
                            <div class="text-muted">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            <!-- عرض الرسائل -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="m9 12 2 2 4 -4"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="alert-title">نجح!</h4>
                            <div class="text-muted">{{ session('status') }}</div>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">تسجيل الدخول لحسابك</h2>

                    <form action="{{ route('login') }}" method="post" autocomplete="off" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="ادخل بريدك الإلكتروني"
                                autocomplete="off" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label">
                                كلمة المرور
                                <span class="form-label-description">
                                    <a href="{{ route('password.request') }}" class="forgot-link">نسيت كلمة
                                        المرور؟</a>
                                </span>
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" placeholder="ادخل كلمة المرور" autocomplete="off" required>
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" title="عرض كلمة المرور"
                                        data-bs-toggle="tooltip">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <path
                                                d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7">
                                            </path>
                                        </svg>
                                    </a>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" />
                                <span class="form-check-label">تذكرني</span>
                            </label>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-login"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2">
                                    </path>
                                    <path d="M20 12h-13l3 -3m0 6l-3 -3"></path>
                                </svg>
                                تسجيل الدخول
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center text-muted mt-3">
                ليس لديك حساب؟
                <a href="{{ route('register') }}" tabindex="-1" class="forgot-link">إنشاء حساب جديد</a>
            </div>

            <!-- معلومات تجريبية للاختبار -->
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="card-title text-center">بيانات تجريبية للاختبار</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>البريد الإلكتروني:</strong>
                                <code class="ms-2">test@test.com</code>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>كلمة المرور:</strong>
                                <code class="ms-2">123456</code>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">يمكنك استخدام هذه البيانات لتجربة النظام</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabler Core JS -->
    <script src="{{ asset('tabler-dev/assets/js/vendors/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('tabler-dev/assets/js/vendors/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('tabler-dev/assets/js/core.js') }}"></script>

    <!-- Password toggle functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.querySelector('[title="عرض كلمة المرور"]');
            const passwordInput = document.querySelector('input[name="password"]');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function(e) {
                    e.preventDefault();

                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Change icon
                    const icon = this.querySelector('svg');
                    if (type === 'text') {
                        icon.innerHTML =
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="3" y1="3" x2="21" y2="21"></line><path d="M10.584 10.587a2 2 0 0 0 2.828 2.83"></path><path d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c4 0 7.333 2.333 10 7c-.778 1.361 -1.612 2.524 -2.503 3.488m-2.14 1.861c-1.631 1.1 -3.415 1.651 -5.357 1.651c-4 0 -7.333 -2.333 -10 -7c1.369 -2.395 2.913 -4.175 4.632 -5.341"></path>';
                    } else {
                        icon.innerHTML =
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="2"></circle><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>';
                    }
                });
            }

            // Auto-focus on first input
            const firstInput = document.querySelector('input[name="email"]');
            if (firstInput) {
                firstInput.focus();
            }

            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>
