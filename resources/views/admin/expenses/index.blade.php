@extends('admin.layouts.main')

@section('content-title', 'Expenses')

@section('content')
<div id="expenses-list">
    <div class="sk-grid sk-primary" v-if="false">
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
    <expenses-list @can('create-expenses') controls @endcan />
</div>
@endsection
