@extends('admin.layouts.main')

@section('content-title', 'Trial Balance')

@section('content')
<x-card title="Balances:">
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>Code</th>
            <th>Name - English</th>
            <th>Name - Lithuanian</th>
            <th>Debit</th>
            <th>Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach([
            'Fixed Asset'           => 'Fixed Asset',
            'Current Asset'         => 'Current Asset',
            'Long Term Liability'   => 'Long Term Obligations',
            'Current Liability'     => 'Short Tirm Obligations', 
            'Equity'                => 'Capital',
            'Revenue'               => 'Income',
            'Expense'               => 'Expense' 
        ] as $type => $displayed_type_name)
            <tr>
                <th class="text-bg-secondary" colspan="5">{!! $displayed_type_name !!}:</td>
            </tr>
            @isset($accounts[$type])
            @foreach($accounts[$type] as $account)
            <tr>
                <td>{!! $account->code !!}</td>
                <td>{{ json_decode( $account->name )->en }}</td>
                <td>{{ json_decode( $account->name )->lt }}</td>
                @if($account->debit_credit == 'debit')
                <td>{!! (float) $account->sum !!}</td>
                <td></td>
                @else
                <td></td>
                <td>{!! (float) $account->sum !!}</td>
                @endif
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">No Accounts Found For {{$displayed_type_name}}</td>
            </tr>
            @endisset
        @endforeach
        <tr>
            <th colspan="3">Total:</th>
            <th>{!! (float) $accounts->flatten()->filter(fn($a) => $a->debit_credit == 'debit')->sum('sum') !!}</th>
            <th>{!! (float) $accounts->flatten()->filter(fn($a) => $a->debit_credit == 'credit')->sum('sum') !!}</th>
        </tr>
    </tbody>
</table>
</x-card>
@endsection