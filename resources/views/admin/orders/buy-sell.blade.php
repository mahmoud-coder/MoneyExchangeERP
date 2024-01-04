@extends('admin.layouts.main')

@section('content-title', 'Order')

@section('content')
<div 
    id="order-component" 
    data-type="{!! $type !!}" 
    {!! $use_stored_exchange_rate ? 'data-use-stored-exchange-rate' : null !!}
    {!! $use_stored_fees ? 'data-use-stored-fees' : null !!}
>
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
    window.app.currencies = {!! $currencies !!}
    window.app.payment_methods = {!! $payment_methods !!}
    window.app.customers = {!! $customers !!}
    @isset($transaction)
    window.app.transaction = {!!$transaction!!}
    @endisset
</script>
@endpush

