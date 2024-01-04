@extends('admin.layouts.main')

@section('content-title', 'Entity Customers')

@section('content')
<div id="customers-entity" @isset($type) data-type="{!!$type!!}" @endisset>
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

@push('scripts')
<script>
    window.app = window.app || {}
    window.app.countries = {!! $countries !!}
    window.app.activities = {!! $activities !!}
    @isset($customer)
    window.app.customer = {!! $customer !!}
    @endisset
</script>
@endpush
