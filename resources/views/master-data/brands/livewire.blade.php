@extends('layouts.main')

@section('title', __('app.brands'))

@section('header')
    <div class="page-pretitle">
        {{ __('app.master_data') }}
    </div>
    <h2 class="page-title">
        <i class="fas fa-award me-2"></i>
        {{ __('app.brands') }}
    </h2>
@endsection

@section('content')
    @livewire('brands-table')
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
    </style>
@endpush
