<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="{{ app()->getLocale() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="{{ asset('tabler-dev/favicon.ico') }}" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('tabler-dev/favicon.ico') }}" />

    <title>@yield('title', config('app.name'))</title>

    <!-- Bootstrap 5 CSS (RTL/LTR based on language) -->
    @if (app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap">
    @else
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    @endif

    <!-- Custom styles for multi-language support -->
    <style>
        body {
            @if (app()->getLocale() == 'ar')
                font-family: 'Cairo', sans-serif;
            @else
                font-family: 'Inter', sans-serif;
            @endif
        }

        /* منع التمرير الأفقي */
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        /* تحسين الهيدر */
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .navbar-brand {
            font-weight: 600;
        }

        /* تحسين الأيقونات */
        .nav-link i {
            margin-left: 5px;
        }

        /* تحسين المحتوى الرئيسي */
        .main-content {
            min-height: calc(100vh - 120px);
            padding: 2rem 0;
        }

        /* تحسين الفوتر */
        .footer {
            background-color: #f8f9fa;
            padding: 1rem 0;
            margin-top: auto;
        }

        /* تحسين responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem 0;
            }
        }
    </style>

    @stack('styles')
    @livewireStyles
</head>

<body class="@yield('body-class', '')">
    @auth
        @include('layouts.header')
    @endauth

    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    @auth
        @include('layouts.footer')
    @endauth

    @stack('scripts')

    <!-- Sweet Alert 2 CDN - متاح لجميع الصفحات -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (إختياري) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>

    <!-- JavaScript بسيط للـ Dropdown -->
    <script>
        // Bootstrap 5 يعمل تلقائياً مع data-bs-toggle="dropdown"
        // لا حاجة لكود إضافي

        $(document).ready(function() {
            console.log('Bootstrap 5 loaded successfully');
            console.log('Current language: {{ app()->getLocale() }}');
        });
    </script>

    @livewireScripts
</body>

</html>
