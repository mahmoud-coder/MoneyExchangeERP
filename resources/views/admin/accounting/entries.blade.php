<style>
* { 
    font-family:DejaVu Sans; 
    font-size: 9px;
}
.page-break {
    page-break-after: always;
    break-after: always;
}

table{
    width:100%;
}

table {
    border-collapse: collapse;
    border: 1px solid gray;
    text-align: center;
}
thead td {
    text-transform: capitalize;
    font-style: bold;
}
td{
    border-left: 1px solid gray;
    border-right: 1px solid gray;
    border-top: 1px dashed gray;
    border-bottom: 1px dashed gray;
}

.entry {
    border-top: 1px solid gray;
}
h1,h2,h3{
    text-align:center;
}
h1{
    font-size: 2rem;
    font-weight: 200;
}
h2{
    text-align:1.5rem;
}

</style>
<h1>{{ $company_name }}</h1>
<h2>{{ $company_code }}</h2>
<h3>{{ __('General Journal') }}</h3>
<table>
    <thead>
        <tr>
            <td>#</td>
            <td>{!! __('date') !!}</td>
            <td>{!! __('content') !!}</td>
            <td>{!! __('code') !!}</td>
            <td>{!! __('debit') !!}</td>
            <td>{!! __('credit') !!}</td>
        </tr>
    </thead>
    <tbody>
    @foreach($entries as $entry)
    <tr class="entry">
        <td>{!! $loop->iteration !!}</td>
        <td class="date">{!! $entry->date !!}</td>
        <td>
        @if($entry->itemable_type == 'App\\Models\\Transaction')
        {!! __($entry->itemable->type_as_string) . ' ' . __('invoice') . ": MPTR{$entry->itemable_id}" !!}
        @elseif($entry->itemable_type == 'App\\Models\\WagesPayout')
        {!! __('wages incurred at').': '. App\Models\WagesPayout::find($entry->itemable_id)->incurred_at !!}
        @elseif($entry->itemable_type == 'App\\Models\\WagesPaid')
        {!! __('wages paid at').': '. App\Models\WagesPaid::find($entry->itemable_id)->paid_at !!}
        @else
        {{ $entry->notes }}
        @endif
        </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @foreach($entry->details as $row)
    <tr>
        <td></td>
        <td></td>
        <td class="account-name">{{json_decode($row->account->name)->{$lang} }}</td>
        <td class="account-code">{!! $row->account->code !!}</td>
        @if($row->type == 'Debit')
        <td class="amount">{!!  $row->amount !!}</td>
        <td></td>
        @else
        <td></td>
        <td class="amount">{!!  $row->amount !!}</td>
        @endif
    </tr>
    @endforeach
    @endforeach
    </tbody>
</table>