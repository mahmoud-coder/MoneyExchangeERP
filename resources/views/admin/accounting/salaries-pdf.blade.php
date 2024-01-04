<style>
* { 
    font-family:DejaVu Sans; 
    font-size: 12px;
}
.page-break {
    page-break-after: always;
    break-after: always;
}

table {
    width:100%;
    border-collapse: collapse;
    border: 1px solid gray;
    text-align: center;
}
thead th {
    text-transform: capitalize;
    font-style: bold;
    border: 1px solid gray;
}

tr.totals td{
    font-weight: bold;
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
<h3>UAB "MONEYBEAT", 306008398</h3>
<h4>{!! __('wages incurred at') !!}: {{$payout->incurred_at}}</h4>
<p>
    <b>{!! __('Fixed w.d and w.h') !!}:</b> <span>{{$payout->worked_days}}, {{$payout->worked_hours}}</span>
</p>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>{!! __('name') !!}</th>
            <th>{!! __('gross salary') !!}</th>
            <th>{!! __('pension') !!}</th>
            <th>NPD</th>
            <th>(GPM) <br> 20%</th>
            <th>{!! __('soc. insurance') !!} <br> 19.5%</th>
            <th>{!! __('total deducted') !!}</th>
            <th>{!! __('net pay') !!}</th>
            <th>{!! __('soc. insurance') !!} <br> 1.77%, 2.49% </th>
        </tr>
    </thead>
    <tbody>
        @foreach($payout->details as $d)
        @php
            $additional_earnings = array_reduce((array) json_decode($d->additional_earnings_values) , fn($a,$b)=> $a+$b ,0);
            $additional_deductions = array_reduce((array) json_decode($d->additional_deductions_values) , fn($a,$b)=> $a+$b ,0);
            $gross_salary = $d->salary + $additional_earnings - $additional_deductions;
        @endphp
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$d->employee->name}} {{$d->employee->surname}}</td>
            <td>{{$gross_salary}}</td>
            <td>{{$d->pension}}</td>
            <td>{{$d->npd}}</td>
            <td>{{$d->tax}}</td>
            <td>{{$d->h_s_insurance}}</td>
            <td>{{$d->total_deducted}}</td>
            <td>{{$d->net_pay}}</td>
            <td>{{$d->insurance}}</td>
        </tr>
        @endforeach
        <tr class="totals">
            <td colspan="3">Totals:</td>
            <td>{{$payout->pension}}</td>
            <td></td>
            <td>{{$payout->tax}}</td>
            <td>{{$payout->insurance_h_s}}</td>
            <td></td>
            <td>{{$payout->net_pay}}</td>
            <td>{{$payout->soc_insurance}}</td>
        </tr>
    </tbody>
</table>

<table style="width: 50%; margin-top: 30px;">
    <thead>
        <tr>
            <th>{!! __('code') !!}</th>
            <th>{!! __('sum') !!}</th>
            <th>IBAN</th>
            <th>{!! __('purpose') !!}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>252</td>
            <td>&euro; {{$payout->insurance_sum}}</td>
            <td>LT77 7300 0101 2900 2656</td>
            <td>{!! __('wages.report.SIT') !!}</td> {{-- social insurance taxes --}}
        </tr>
        <tr>
            <td>1311</td>
            <td>&euro; {{$payout->tax}}</td>
            <td>LT24 7300 0101 1239 4300</td>
            <td>{!! __('wages.report.IT') !!}</td> {{-- Income Taxes --}}
        </tr>
    </tbody>
</table>

<div style="margin-top:30px">
    <b style="margin-right:10px">{!! __('head of the company') !!}:</b> Amr Gamaleldin _______________________
    <span style="float:right"><b style="margin-right:10px"> {!! __('accountant') !!}:</b>Odeta Muižininkienė _____________________</span>
</div>
