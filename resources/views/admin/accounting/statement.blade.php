@extends('admin.layouts.main')

@section('content-title', 'Statement')

@section('content')
<div class="row">
    <x-card title="Date Range" class="col-md-8 offset-md-2" >
        <form method="get">
            <div class="py-2">
                <label for="from-date" class="form-label">From</label>
                <input type="text" name="from" id="from-date" class="form-control flatpickr" value="{!!$from!!}">
            </div>
            <div class="py-2">
                <label for="to-date" class="form-label">To</label>
                <input type="text" name="to" id="to-date" class="form-control flatpickr" value="{!!$to!!}">
            </div>
            <button type="submit" class="btn btn-primary float-end">Select</button>
        </form>
    </x-card>
    
    <x-card title="Expenses Statement" class="col-md-8 offset-md-2 mt-3">
        <table class="table table-hover table-bordered">
            <tr>
                <th colspan="3" style="text-align:center">Once Expenses</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Amount</th>
            </tr>
            @foreach($once_expenses as $expense)
            <tr>
                <td>{!!$expense->date!!}</td>
                <td>{{$expense->title}}</td>
                <td>{!!(float) $expense->amount!!}</td>
            </tr>
            @endforeach
            <tr>
                <th>Sum:</th>
                <td colspan="2" style="text-align:center">{!!$once_expenses_sum!!}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align:center">Monthly Expenses</th>
            <tr>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Amount</th>
            </tr>
            @foreach($monthly_expenses as $expense)
            <tr>
                <td>{!!$expense->date!!}</td>
                <td>{{$expense->title}}</td>
                <td>{!!(float) $expense->amount!!}</td>
            </tr>
            @endforeach
            <tr>
                <th>Sum:</th>
                <td colspan="2" style="text-align:center">{!!$monthly_expenses_sum!!}</td>
            </tr>
        </table>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
jQuery(function($){
    $('.flatpickr').flatpickr()
})
</script>
@endpush

