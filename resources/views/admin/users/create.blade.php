@extends('admin.layouts.main')

@section('content-title')
    @if($type == 'new')
        Users | Create New
    @else
        Users | Edit
    @endif
@endsection

@section('content')
<div class="row">
<div class="card col-md-6 offset-md-3">
    <div class="card-body">
        <h5 class="card-title">{{$type == 'new' ? 'add new user' : 'Edit: '.$user->name }}</h5>
        @include('partials.session-message')
        @if($type=='new')
        <form action="{!! route('admin.users.store') !!}" method='POST'>
        @else
        <form action="{!! route('admin.users.update', ['user' => $user->id]) !!}"  method='POST'>
            @method('PUT')
        @endif
            @csrf
            <div class="mb-3">
                <label for="user-name" class="form-label">Name</label>
                <input type="text" placeholder="Name" name="name" id="user-name" class="form-control" value="{{$type == 'new' ? old('name') : $user->name }}">
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user-email" class="form-label">eMail</label>
                <input type="text" Placeholder="eMail" name="email" id="user-email" class="form-control" value="{{$type == 'new'? old('email'):$user->email}}">
                @error('email')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user-role" class="form-label">Role</label>
                @php
                    if($type == 'new') $role = old('role'); else $role = $user->role;
                @endphp
                <select name="role" id="user-role" class="form-select">
                    <option value="0"{!! $role == '0' ? 'selected' : '' !!}>CEO / Admin</option>
                    <option value="1"{!! $role == '1' ? 'selected' : '' !!}>Sales team</option>
                    <option value="2"{!! $role == '2' ? 'selected' : '' !!}>Compliance team</option>
                    <option value="3"{!! $role == '3' ? 'selected' : '' !!}>Accounting team</option>
                </select>
                @error('role')
                <p class="error-message">{{$message}}</p>
                @enderror
            </div>
            @if($type == 'new')
            <div class="mb-3">
                <label for="user-password" class="form-label">Password</label>
                <input type="password" id="user-password" placeholder="Password" name="password" class="form-control">
                @error('password')
                <p class="error-message">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user-confirm-password" class="form-label">Confirm Password</label>
                <input type="password" placeholder="Confirm password" id="user-confirm-password" name="password_confirmation" class="form-control">
            </div>
            @endif
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
    font-size:0.8rem;
    font-style:italic;
}
</style>
@endpush