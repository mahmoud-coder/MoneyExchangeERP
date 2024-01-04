@extends('admin.layouts.main')

@section('content-title')
    @isset($type)
    Transactions - {!! $type!!}
    @else
    transitions - Monitor
    @endisset
@endsection

@section('content')
<div 
    id="transaction-component" 
    data-type ="{!! isset($type) ? $type : 'all'!!}" 
    {!!$show_mini_summary ? 'data-show-mini-summary' : null !!}
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
    window.app.customers = {!! $customers !!}
</script>
@endpush