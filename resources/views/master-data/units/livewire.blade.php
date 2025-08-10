@extends('layouts.main')

@section('title', __('app.units'))

@section('content')
    <div class="container-fluid">
        <livewire:units-table />
    </div>
@endsection
