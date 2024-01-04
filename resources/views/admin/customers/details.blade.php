@extends('admin.layouts.main')

@section('content-title', 'Customer')

@section('content')
<div id="customers-details" data-customer-id="{!! $customer_id !!}">
    <div class="sk-grid sk-primary">
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
        <div class="sk-grid-cube"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
.user-profile-header-banner img {
  width: 100%;
  object-fit: cover;
  height: 250px;
}

.user-profile-header {
  margin-top: -2rem;
}
.user-profile-header .user-profile-img {
  border: 5px solid;
  width: 120px;
  padding:5px;
}

.light-style .user-profile-header .user-profile-img {
  border-color: #fff;
}

.dark-style .user-profile-header .user-profile-img {
  border-color: #2f3349;
}
@media (max-width: 767.98px) {
  .user-profile-header-banner img {
    height: 150px;
  }
  .user-profile-header .user-profile-img {
    width: 100px;
  }
}

.card-header{
    text-align:center;
}
.error-message{
    color: red;
    font-style: italic;
}
</style>
@endpush

@push('scripts')
<script>
    window.app = window.app || {}
    window.app.currencies = {!! $currencies !!}
    window.app.payment_methods = {!! $payment_methods !!}
</script>
@endpush