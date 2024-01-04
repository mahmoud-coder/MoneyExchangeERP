@extends('admin.layouts.main')

@section('content-title', 'Expenses')

@section('content')
<div id="expenses">
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
    <expense type="{!! $type !!}" @isset($expense) :expense='{!! $expense !!}' @endisset/>
</div>
@endsection
