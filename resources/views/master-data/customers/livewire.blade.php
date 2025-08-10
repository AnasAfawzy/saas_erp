@extends('layouts.main')

@section('title', __('app.customers'))

@section('header')
    <div class="page-pretitle">
        {{ __('app.master_data') }}
    </div>
    <h2 class="page-title">
        <i class="fas fa-users me-2"></i>
        {{ __('app.customers') }}
    </h2>
@endsection

@section('content')
    @livewire('customers-table')
@endsection

@push('styles')
    <style>
        .btn-link {
            text-decoration: none !important;
            border: none !important;
            background: none !important;
            box-shadow: none !important;
        }

        .btn-link:hover {
            text-decoration: none !important;
            opacity: 0.7;
            transform: scale(1.1);
            transition: all 0.2s ease;
        }

        .btn-link:focus {
            box-shadow: none !important;
            outline: none !important;
        }

        .nav-tabs .nav-link {
            border-radius: 0.375rem 0.375rem 0 0;
            border-bottom: 1px solid transparent;
        }

        .nav-tabs .nav-link.active {
            border-bottom-color: #fff;
            margin-bottom: -1px;
            border-color: var(--tblr-border-color) var(--tblr-border-color) #fff;
        }

        .modal-xl {
            max-width: 1200px;
        }

        .progress {
            height: 0.25rem;
        }

        .empty img {
            opacity: 0.5;
        }

        .card-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: var(--tblr-secondary);
        }

        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .btn-list {
            gap: 0.25rem;
        }

        .btn-list .btn {
            padding: 0.25rem 0.5rem;
        }

        .form-check-input:checked {
            background-color: var(--tblr-primary);
            border-color: var(--tblr-primary);
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .text-red {
            color: var(--tblr-red) !important;
        }

        .text-green {
            color: var(--tblr-green) !important;
        }

        .text-orange {
            color: var(--tblr-orange) !important;
        }

        .text-purple {
            color: var(--tblr-purple) !important;
        }

        .text-blue {
            color: var(--tblr-blue) !important;
        }

        .bg-green-lt {
            background-color: var(--tblr-green-lt) !important;
        }

        .bg-red-lt {
            background-color: var(--tblr-red-lt) !important;
        }

        .bg-blue-lt {
            background-color: var(--tblr-blue-lt) !important;
        }

        .bg-orange-lt {
            background-color: var(--tblr-orange-lt) !important;
        }

        .bg-purple-lt {
            background-color: var(--tblr-purple-lt) !important;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        @media (max-width: 768px) {
            .modal-xl {
                max-width: 95%;
                margin: 1rem auto;
            }

            .btn-list {
                flex-direction: column;
                gap: 0.5rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .card-table th,
            .card-table td {
                padding: 0.5rem 0.25rem;
            }

            .avatar {
                width: 2rem;
                height: 2rem;
            }

            .nav-tabs .nav-link {
                font-size: 0.875rem;
                padding: 0.5rem 0.75rem;
            }

            .nav-tabs .nav-link .icon {
                width: 16px;
                height: 16px;
            }
        }

        /* RTL Support */
        [dir="rtl"] .me-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }

        [dir="rtl"] .ms-2 {
            margin-right: 0.5rem !important;
            margin-left: 0 !important;
        }

        [dir="rtl"] .nav-tabs .nav-link {
            text-align: right;
        }

        [dir="rtl"] .table {
            text-align: right;
        }

        [dir="rtl"] .btn-list {
            flex-direction: row-reverse;
        }

        /* Loading states */
        .btn[wire\\:loading] {
            pointer-events: none;
        }

        .btn[wire\\:loading] .spinner-border {
            animation: spinner-border 0.75s linear infinite;
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        /* Form validation */
        .is-invalid {
            border-color: var(--tblr-red);
        }

        .invalid-feedback {
            color: var(--tblr-red);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Tab content spacing */
        .tab-content {
            padding-top: 1rem;
        }

        /* Statistics cards hover effect */
        .card:hover {
            transform: translateY(-1px);
            transition: transform 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Progress bar animations */
        .progress-bar {
            transition: width 0.6s ease;
        }

        /* Button hover effects */
        .btn:hover {
            transform: translateY(-1px);
            transition: transform 0.2s ease-in-out;
        }

        .btn-white:hover {
            background-color: var(--tblr-gray-50);
            border-color: var(--tblr-gray-300);
        }

        /* Modal animation */
        .modal.show {
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-backdrop.show {
            animation: backdropFadeIn 0.3s ease-out;
        }

        @keyframes backdropFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 0.5;
            }
        }
    </style>
@endpush
