@extends('layouts.main')

@section('title', __('app.suppliers'))

@section('header')
    <div class="page-pretitle">
        {{ __('app.master_data') }}
    </div>
    <h2 class="page-title">
        <i class="fas fa-truck me-2"></i>
        {{ __('app.suppliers_management') }}
    </h2>
@endsection

@section('content')
    <livewire:suppliers-table />
@endsection

@push('css')
    <style>
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            vertical-align: middle;
            user-select: none;
            border-radius: 50%;
        }

        .badge-outline {
            border: 1px solid currentColor;
            color: inherit;
            background: transparent;
        }

        .modal-xl {
            max-width: 1140px;
        }

        .nav-tabs .nav-link {
            color: var(--tblr-nav-tabs-link-color);
            border: var(--tblr-nav-tabs-border-width) solid transparent;
            border-top-left-radius: var(--tblr-nav-tabs-border-radius);
            border-top-right-radius: var(--tblr-nav-tabs-border-radius);
        }

        .nav-tabs .nav-link.active {
            color: var(--tblr-nav-tabs-link-active-color);
            background-color: var(--tblr-nav-tabs-link-active-bg);
            border-color: var(--tblr-nav-tabs-link-active-border-color);
        }

        .card-sm {
            --tblr-card-padding-x: 1rem;
            --tblr-card-padding-y: 0.75rem;
        }

        .text-blue {
            color: #206bc4 !important;
        }

        .text-green {
            color: #2fb344 !important;
        }

        .text-red {
            color: #d63384 !important;
        }

        .text-purple {
            color: #ae3ec9 !important;
        }

        .text-yellow {
            color: #f59f00 !important;
        }

        .text-azure {
            color: #45aaf2 !important;
        }

        .text-success {
            color: #2fb344 !important;
        }

        .bg-blue {
            background-color: #206bc4 !important;
        }

        .bg-green {
            background-color: #2fb344 !important;
        }

        .bg-red {
            background-color: #d63384 !important;
        }

        .bg-purple {
            background-color: #ae3ec9 !important;
        }

        .bg-yellow {
            background-color: #f59f00 !important;
        }

        .bg-azure {
            background-color: #45aaf2 !important;
        }

        .form-check-input:checked {
            background-color: #206bc4;
            border-color: #206bc4;
        }

        .btn-primary {
            background-color: #206bc4;
            border-color: #206bc4;
        }

        .btn-primary:hover {
            background-color: #1a5a9d;
            border-color: #1a5a9d;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #e2e8f0;
        }

        .empty-img img {
            opacity: 0.3;
        }

        .icon-filled {
            fill: currentColor !important;
        }

        .star-rating {
            color: #f59f00;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
