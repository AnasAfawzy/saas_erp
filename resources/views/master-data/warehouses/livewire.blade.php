@extends('layouts.main')

@section('title', __('app.warehouses'))

@section('content')
    <div class="container-fluid">
        <livewire:warehouses-table />
    </div>
@endsection

@push('styles')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
