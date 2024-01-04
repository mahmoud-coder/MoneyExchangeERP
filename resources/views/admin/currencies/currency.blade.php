@extends('admin.layouts.main')

@section('content-title' , 'Currencies')

@section('content')
<div class="row">
<div class="card col-md-6 offset-md-3">
    <div class="card-body">
        <h5 class="card-title">{{$type == 'new' ? 'add new currency' : 'Edit: ' . $currency->name }}</h5>
        @include('partials.session-message')
        @if($type=='new')
        <form action="{!! route('admin.currency.store') !!}" method='POST' enctype="multipart/form-data">
        @else
        <form action="{!! route('admin.currency.update', ['currency' => $currency->id]) !!}"  method='POST' enctype="multipart/form-data">
            @method('PUT')
        @endif
            @csrf
            <div class="mb-3">
                <label class="currency-upload-img">
                    <img class="sample" id="currency_img_sample">
                    <br>
                    <span>Upload the currency SVG image file</span>
                    <input type="file" name="img" id="currency_img_input" hidden accept=".svg">
                    @error('img')
                    <p class="error-message">You have to upload a SVG image file for the new currency</p>
                    @enderror
                </label>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" placeholder="Name" name="name" id="name" class="form-control" @if($type == 'edit') value="{{$currency->name }}" @endif>
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="symbol" class="form-label">Symbol</label>
                <input type="text" placeholder="Symbol" name="symbol" id="symbol" class="form-control" @if($type == 'edit') value="{{$currency->symbol }}" @endif>
                @error('symbol')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="purchase_exchange_rate" class="form-label">Purchase Exchange Rate</label>
                <input type="text" placeholder="Purchase Exchange Rate" name="purchase_exchange_rate" id="purchase_exchange_rate" class="form-control" @if($type=='edit') value="{{ is_null($currency->purchase_exchange_rate) ? null : (float) $currency->purchase_exchange_rate  }}" @endif>
                @error('purchase_exchange_rate')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="purchase_fees" class="form-label">Purchase Fees</label>
                <input type="text" placeholder="Purchase Exchange Rate" name="purchase_fees" id="purchase_fees" class="form-control" @if($type=='edit') value="{{ is_null($currency->purchase_fees)?null:(float) $currency->purchase_fees }}" @endif>
                @error('purchase_fees')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
             
            <label for="selling_exchange_rate" class="form-label">Selling Exchange Rate</label>
                <input type="text" placeholder="selling Exchange Rate" name="selling_exchange_rate" id="selling_exchange_rate" class="form-control" @if($type=='edit') value="{{ is_null($currency->selling_exchange_rate) ? null :(float) $currency->selling_exchange_rate }}" @endif>
                @error('selling_exchange_rate')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="selling_fees" class="form-label">Selling Fees</label>
                <input type="text" placeholder="selling Exchange Rate" name="selling_fees" id="selling_fees" class="form-control" @if($type=='edit') value="{{ is_null($currency->selling_fees)? null : (float) $currency->selling_fees }}" @endif>
                @error('selling_fees')
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
    .currency-upload-img{
        border: 2px dashed #dbdade;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        padding:20px;
        width: 100%;
    }
    #currency_img_sample{
        width:3.5rem;
        margin-bottom: 10px;
    }
    .error-message{
        color:red;
    }
</style>
@endpush

@push('scripts')
<script>
    var currency_img_sample = document.getElementById("currency_img_sample")
    var currency_img_input = document.getElementById("currency_img_input")
    currency_img_input.addEventListener('change', function(){
        currency_img_sample.src = window.URL.createObjectURL(currency_img_input.files[0])
    })
    @if($type == 'edit')
        currency_img_sample.src = '/storage/coins/{{$currency->symbol}}.svg'
    @endif
</script>
@endpush