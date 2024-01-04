<style>
* { 
    font-family:DejaVu Sans; 
    font-size: 12px;
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
thead th {
    text-transform: capitalize;
    font-style: bold;
    border: 1px solid gray;
}
td{
    border-left: 1px solid gray;
    border-right: 1px solid gray;
    border-top: 1px dashed gray;
    border-bottom: 1px dashed gray;
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
<h1>{!! __('ledger') !!} - {{ json_decode($account->name)->{$lang} }}</h1>
<table>
    <thead>
    <tr>
        <th rowspan="2">#</th>
        <th rowspan="2">{!! __('date') !!}</th>
        <th rowspan="2">{!! __('content') !!}</th>
        <th rowspan="2">{!! __('debit') !!}</th>
        <th rowspan="2">{!! __('credit') !!}</th>
        <th colspan="2">{!! __('balance') !!}</th>
    </tr>
    <tr>
        <th>{!! __('debit') !!}</th>
        <th>{!! __('credit') !!}</th>
    </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{!! $loop->iteration  !!}</td>
            <td>{!! $row->general_journal->date !!}</td>
            <td>{{ $row->general_journal->notes->text }}</td>
            @if($row->type == 'Debit')
            <td>{!! $row->amount !!}</td>
            <td></td>
            @else
            <td></td>
            <td>{!! $row->amount !!}</td>
            @endif
            <td>{!! $row->running_balance_debit !!}</td>
            <td>{!! $row->running_balance_credit !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
