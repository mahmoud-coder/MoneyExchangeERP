@extends('admin.layouts.main')

@section('content-title')
    @if($type == 'new')
        Employee | Create New
    @else
        Employee | Edit
    @endif
@endsection

@section('content')
<div id="employee-create-edit" data-type="{!! $type !!}" @if($type == 'edit') data-employee="{{$employee}}" @endif>
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
