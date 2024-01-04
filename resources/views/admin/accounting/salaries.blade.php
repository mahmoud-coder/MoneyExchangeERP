@extends('admin.layouts.main')

@section('content-title', 'Salaries Payouts')

@section('content')
<div id="salaries-payouts" data-employees="{{ $employees }}" data-accounts="{{ $accounts }}" data-equations="{{$equations}}">
    <div class="sk-grid sk-primary">
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
    </div>
</div>
@endsection