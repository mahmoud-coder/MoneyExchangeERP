@php
if(! function_exists('rowcount')):
    function rowcount($t){
        if(isset($t['rowcount'])) return $t['rowcount'];
        $rows_in_running_stock = count($t['running_stock']) == 1 ? 1 : count($t['running_stock']) + 1;
        if($t['type'] == 'buy'){
            $rowcount = $rows_in_running_stock;
        }else{
            $rows_in_cost_of_sold_item = count($t['cost_of_sold_item']) == 1? 1 : count($t['cost_of_sold_item']) + 1;
            ++$rows_in_cost_of_sold_item; //increase by 1 for the summary extra line
            $rowcount = max($rows_in_running_stock, $rows_in_cost_of_sold_item);
        }
        $t['rowcount'] = $rowcount;
        return $rowcount;
    }
endif;

if(! function_exists('total_cost')):
    function total_cost($t){
        if(isset($t['total_cost'])) return $t['total_cost'];
        $t['total_cost'] = array_reduce($t['cost_of_sold_item'], fn($a,$b) => $a + $b['amount'] * $b['rate'], 0);
        return round($t['total_cost'], 3);
    }
endif;
@endphp

@if(isset($for_pdf) && $for_pdf)
<style>
* { 
    font-family:DejaVu Sans; 
    font-size: 12px;
}
.page-break {
    page-break-after: always;
    break-after: always;
}
.sum{
    background-color: gray;
    color: white;
}
tr.seperator{
    border: 1px solid gray;
}
table{
    width:100%;
    border-collapse: collapse;
    border: 2px solid gray;
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
</style>
@endif

@isset($error)
<div class="alert alert-danger d-flex align-items-baseline" role="alert">
    <span class="alert-icon alert-icon-lg me-2">
        <i class="ti ti-alert-triangle ti-sm"></i>
    </span>
    <div class="d-flex flex-column ps-1">
        <h5 class="alert-heading mb-2">Error</h5>
        <p class="mb-0">{!! $error->getMessage() !!}</p>
    </div>
</div>
@else
<style>
table.cost-grid td,
table.cost-grid th
{
    padding:5px;
}
table.cost-grid .sum{
    font-weight: bold;
}
</style>
@if(isset($for_pdf) && $for_pdf)
<h1>FIFO Cost Grid for {{App\Models\Currency::find($currency_id)->name}} ({{App\Models\Currency::find($currency_id)->symbol}})</h1>
@else
<a href="/admin/accounting/fifo-grid-pdf?currency_id={!! $currency_id !!}" target="_blank" class="btn btn-outline-danger mb-2">Show in PDF</a>
@endif
<table class="table table-bordered table-sm text-center cost-grid" style="text-wrap: nowrap; vertical-align: middle;">
    <colgroup>
        <col span="2" class="bg-label-light">
        <col span="3">
        <col span="3" class="bg-label-light">
        <col span="3">
    </colgroup>
    <thead class="bg-lighter" style="border-top: solid gainsboro 1px">
        <tr>
            <th rowspan="2" style="vertical-align: middle;">Date</th>
            <th rowspan="2" style="vertical-align: middle;">Invoice</th>
            <th colspan="3">Purchase</th>
            <th colspan="3">Selling</th>
            <th colspan="3">Stock</th>
        </tr>
        <tr>
            <th>amount</th>
            <th>price</th>
            <th>total</th>

            <th>amount</th>
            <th>cost</th>
            <th>total</th>

            <th>amount</th>
            <th>cost</th>
            <th>total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $t)
        @for($i=0; $i < rowcount($t); ++$i)
        <tr>
            {{-- DATE & ID --}}
            @if($i==0)
            <td rowspan="{!!  rowcount($t) !!}">{!! $t['date'] !!}</td>
            <td rowspan="{!!  rowcount($t) !!}">
                <a href="{!! '/admin/transaction/' . $t['id'] !!}" target="_blank">{!! 'mbtr'.$t['id'] !!}</a>
            </td>
            @endif

            {{-- PURCHASE & SELLING --}}
            @if($t['type'] == 'buy')
                @if($i==0) 
                <td rowspan="{!!  rowcount($t) !!}">{!! $t['amount'] !!}</td> 
                <td rowspan="{!!  rowcount($t) !!}">{!! $t['rate'] !!}</td>
                <td rowspan="{!!  rowcount($t) !!}">{!! round($t['price'],3) !!}</td>
                <td rowspan="{!!  rowcount($t) !!}"></td>
                <td rowspan="{!!  rowcount($t) !!}"></td>
                <td rowspan="{!!  rowcount($t) !!}"></td>
                @endif
            @else
                @php
                    $count=count($t['cost_of_sold_item']);
                    $rowspan =  rowcount($t) - $count- ($count > 1 ? 1 : 0)
                @endphp
                @if($i==0)
                <td rowspan="{!!  rowcount($t) !!}"></td>
                <td rowspan="{!!  rowcount($t) !!}"></td>
                <td rowspan="{!!  rowcount($t) !!}"></td>
                @endif
                @if($count > $i)
                <td @if($count-1 == $i) rowspan="{!! $rowspan !!}"@endif>{!! $t['cost_of_sold_item'][$i]['amount'] !!}</td>
                <td @if($count-1 == $i) rowspan="{!! $rowspan !!}"@endif>{!! $t['cost_of_sold_item'][$i]['rate'] !!}</td>
                <td @if($count-1 == $i) rowspan="{!! $rowspan !!}"@endif>{!! round($t['cost_of_sold_item'][$i]['rate'] * $t['cost_of_sold_item'][$i]['amount'], 3) !!}</td>
                @endif
                @if($count >1 && $i == rowcount($t)-2)
                    <td class="bg-label-primary sum">{!!  array_reduce($t['cost_of_sold_item'], fn($a, $b) => $a + $b['amount'], 0) !!}</td> 
                    <td class="bg-label-primary sum"></td>
                    <td class="bg-label-primary sum">{!! total_cost($t) !!}</td>
                @endif
                @if($i == rowcount($t)-1)
                    @php $profit_loss = $t['total_sell_price'] - total_cost($t); @endphp
                    <td colspan="3" class="{!! $profit_loss >= 0 ? 'text-success':'text-danger'  !!}">
                        {!!$profit_loss >= 0 ? 'Profit':'Loss' !!} = {!! $t['total_sell_price'] !!} - {!! total_cost($t) !!} = {!! $t['total_sell_price'] - total_cost($t) !!}
                    </td>
                @endif
            @endif

            {{-- RUNING STOCK --}}
            @php
                $count=count($t['running_stock']);
                $rowspan =  rowcount($t)-$count + ($count > 1 ? 0 : 1)
            @endphp
            @if($count > $i)
            <td @if($count-1 == $i) rowspan="{!! $rowspan !!}"@endif>{!! $t['running_stock'][$i]['amount'] !!}</td>
            <td @if($count-1 == $i) rowspan="{!! $rowspan !!}"@endif>{!! $t['running_stock'][$i]['rate'] !!}</td>
            <td @if($count-1 == $i) rowspan="{!! $rowspan !!}"@endif>{!! round( $t['running_stock'][$i]['rate'] * $t['running_stock'][$i]['amount'] , 3) !!}</td>
            @endif
            @if($count >1 && $i == rowcount($t)-1)
                <td class="bg-label-primary sum">{!! array_reduce($t['running_stock'], fn($a, $b) => $a + $b['amount'], 0)!!}</td>
                <td class="bg-label-primary sum"></td>
                <td class="bg-label-primary sum">{!! round( array_reduce($t['running_stock'], fn($a,$b) => $a + $b['amount']*$b['rate'], 0) , 3)!!}</td>
            @endif
        </tr>
        @endfor
        <tr class="seperator">
            <td colspan="11" style="padding:1px;"></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endisset