@extends('layouts.main')

@section('title', __('app.branches'))

@section('content')
    <div class="container-fluid">
        <livewire:branches-table />
    </div>
@endsection
