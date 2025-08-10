@extends('layouts.main')

@section('title', __('app.products'))

@section('header')
    <div class="page-pretitle">
        {{ __('app.master_data') }}
    </div>
    <h2 class="page-title">
        <i class="fas fa-boxes me-2"></i>
        {{ __('app.products') }}
    </h2>
@endsection

@section('content')
    @livewire('products-table')
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

        .product-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }

        .stock-status {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .stock-low {
            background-color: #fff2e8;
            color: #d9534f;
        }

        .stock-normal {
            background-color: #e8f5e8;
            color: #5cb85c;
        }

        .stock-high {
            background-color: #e3f2fd;
            color: #0275d8;
        }

        .form-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #666;
        }

        .form-tabs .nav-link.active {
            border-bottom: 2px solid #0054a6;
            color: #0054a6;
            background: none;
        }

        .tab-content {
            padding: 20px 0;
        }
    </style>
@endpush
