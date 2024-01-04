@extends('admin.layouts.main')

@section('content-title' , 'Payment Method')

@section('content')
<div class="row">
<div class="card col-md-6 offset-md-3">
    <div class="card-body">
        <h5 class="card-title">{{$type == 'new' ? 'add new Payment Method' : 'Edit: ' . $pm->method }}</h5>
        @include('partials.session-message')
        @if($type=='new')
        <form action="{!! route('admin.payment-methods.store') !!}" method='POST' enctype="multipart/form-data">
        @else
        <form action="{!! route('admin.payment-methods.update', ['payment_method' => $pm->id]) !!}"  method='POST' enctype="multipart/form-data">
            @method('PUT')
        @endif
            @csrf
            <div class="mb-3">
                <label for="method" class="form-label">Method</label>
                <input type="text" placeholder="method" name="method" id="method" class="form-control" @if($type == 'edit') value="{{$pm->method }}" @endif>
                @error('method')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Description</label>
                <textarea rows="5" placeholder="description" name="desc" id="desc" class="form-control">@if($type == 'edit'){{ $pm->desc }}@endif</textarea>
                @error('desc')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <hr>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary waves-effect waves-light float-end">Save</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
    .error-message{
        color:red;
    }
</style>
@endpush