@extends('admin.layouts.main')

@section('content-title', 'Individual Customers')

@section('content')
<div id="customers-individual" @isset($type) data-type="{!!$type!!}" @endisset>
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
    @isset($customer)
    window.app.customer = {!! $customer !!}
    @endisset
</script>
@endpush
