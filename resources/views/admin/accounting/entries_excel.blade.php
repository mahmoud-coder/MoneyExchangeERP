@php
$style_for_head = 'style="font-weight:bold; background:#333333; color:white; font-size:15px;"';
$style_for_entry_head = 'style="background:#aaaaaa; font-weight: bold; font-size:11px;"';
$style_for_cell = 'style="border: 2px dashed #555555; font-size:11px;"';
@endphp
<table>
    <thead>
        <tr>
            <td {!! $style_for_head !!}>#</td>
            <td {!! $style_for_head !!}>{!! __('date') !!}</td>
            <td {!! $style_for_head !!}>{!! __('content') !!}</td>
            <td {!! $style_for_head !!}>{!! __('code') !!}</td>
            <td {!! $style_for_head !!}>{!! __('debit') !!}</td>
            <td {!! $style_for_head !!}>{!! __('credit') !!}</td>
        </tr>
    </thead>
    <tbody>
    @foreach($entries as $entry)
    <tr>
        <td {!!$style_for_entry_head!!}>{!! $loop->iteration !!}</td>
        <td {!!$style_for_entry_head!!}>{!! $entry->date !!}</td>
        <td {!!$style_for_entry_head!!}>
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
        <td {!!$style_for_entry_head!!}></td>
        <td {!!$style_for_entry_head!!}></td>
        <td {!!$style_for_entry_head!!}></td>
    </tr>
    @foreach($entry->details as $row)
    <tr>
        <td {!!$style_for_cell!!}></td>
        <td {!!$style_for_cell!!}></td>
        <td {!!$style_for_cell!!}>{{json_decode($row->account->name)->{$lang} }}</td>
        <td {!!$style_for_cell!!}>{!! $row->account->code !!}</td>
        @if($row->type == 'Debit')
        <td {!!$style_for_cell!!}>{!!  $row->amount !!}</td>
        <td {!!$style_for_cell!!}></td>
        @else
        <td {!!$style_for_cell!!}></td>
        <td {!!$style_for_cell!!}>{!!  $row->amount !!}</td>
        @endif
    </tr>
    @endforeach
    @endforeach
    </tbody>
</table>