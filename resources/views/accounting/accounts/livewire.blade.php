@extends('layouts.main')

@section('title', __('app.chart_of_accounts'))

@section('content')
    <div class="container-fluid">
        <livewire:accounts-table />
    </div>
@endsection
