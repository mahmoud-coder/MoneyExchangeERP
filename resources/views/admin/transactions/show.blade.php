@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
        @include('admin.transactions.invoice')
    </div>
    <div class="col-12 col-md-4 col-xl-3">
        <x-card title="Actions:">
        <a class="btn btn-label-primary w-100 mb-2 waves-effect" target="_blank" href="{!! route('admin.transaction.pdf', ['id'=>$transaction['id']]) !!}">
        <i class="ti ti-file-description"></i> PDF
    </a>
        <a class="btn btn-label-primary w-100 mb-2 waves-effect" target="_blank" href="{!! route('admin.transaction.print', ['id'=>$transaction['id']]) !!}">
        <i class="ti ti-printer"></i> Print
        </a>
        <a class="btn btn-label-primary w-100 mb-2 waves-effect" href="{!! route('admin.transaction.edit',['transaction'=>$transaction['id']]) !!}">
            <i class="ti ti-edit"></i> Edit
        </a>
        </x-card>
    </div>
</div>
@endsection