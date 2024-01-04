@extends('admin.layouts.main')

@section('content-title', 'Settings')

@section('content')
<div class="row">
    <x-card class="col-md-6 offset-md-3" title="Settings">
        @include('partials.session-message')
        <form action="{!! route('admin.settings.store') !!}" method='POST'>
            @csrf
            <x-divider label="SUMSUB" />
            <x-checkbox :checked="$options['sumsub-customer-create']" name="sumsub-customer-create" label="Automaticaly create customer once approved on Sumsub" />
            <x-divider label="ORDERS" />
            <x-checkbox :checked="$options['orders-use-stored-exchange-rate']" name="orders-use-stored-exchange-rate" label="use the exchange rate stored in the database" />
            <x-checkbox :checked="$options['orders-use-stored-fees']" name="orders-use-stored-fees" label="view the fees stored in the database" />
            <x-divider label="TRANSACTIONS" />
            <x-checkbox :checked="$options['transactions-show-mini-summary']" name="transactions-show-mini-summary" label="Show mini summary for each transaction, in the transactions pages (Sell, Buy, and Monitor)" />
            <hr>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary waves-effect waves-light float-end">Save</button>
            </div>
        </form>
    </x-card>
</div>
@endsection

