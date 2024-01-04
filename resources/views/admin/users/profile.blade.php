@extends('admin.layouts.main')

@section('content-title', 'Profile')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="/assets/img/profile-banner.png" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    <img src="{!! Auth::user()->avatar_src !!}" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                    <div class="user-profile-info">
                        <h4>{{ Auth::user()->name }}</h4>
                        <ul class="list-inline d-flex align-items-center justify-content-sm-start justify-content-center gap-4">
                            <li><i class="ti ti-crown"></i> <span>{!! Auth::user()->role_text !!}</span></li>
                            <li><i class="ti ti-calendar"></i> <span>joined: {{ Auth::user()->created_at->format('d-m-Y')}}</span></li>
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <h5 class="card-header">Your Details</h5>
            <div class="card-body">
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-user"></i><span class="fw-bold mx-2">Name:</span> <span>{{ Auth::user()->name }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-mail"></i><span class="fw-bold mx-2">eMail:</span> <span>{{ Auth::user()->email }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-crown"></i><span class="fw-bold mx-2">Role:</span> <span>{{ Auth::user()->role_text }}</span>
                    </li>
                </ul>
                @include('partials.session-message')
                <div class="divider">
                    <span class="divider-text">EDIT</span>
                </div>
                <form action="{!! route('admin.profile.edit') !!}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="user-name" class="form-label">Name</label>
                        <input type="text" placeholder="Name" name="name" id="user-name" class="form-control" value="{{old('name') ?: Auth::user()->name}}">
                        @error('name')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="user-email" class="form-label">eMail</label>
                        <input type="text" Placeholder="eMail" name="email" id="user-email" class="form-control" value="{{old('email') ?: Auth::user()->email}}">
                        @error('email')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="d-flex align-items-start align-items-sm-center justify-content-center gap-4 my-4">
                        <img src="{!! Auth::user()->avatar_src !!}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-3 waves-effect waves-light" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <i class="ti ti-upload d-block d-sm-none"></i>
                            <input type="file" id="upload" class="account-file-input" hidden name="avatar" accept="image/png, image/jpeg">
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-3 waves-effect">
                            <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light float-end">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
            <form action="{!! route('admin.profile.changepassword') !!}" method="POST">
                @csrf
                <div class="divider">
                    <span class="divider-text">CHANGE PASSWORD</span>
                </div>
                <div class="mb-3">
                    <label for="user-password" class="form-label">Current Password</label>
                    <input type="password" placeholder="current password" name="current_password" class="form-control">
                    @error('current_password')
                    <p class="error-message">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="user-password" class="form-label">New Password</label>
                    <input type="password" placeholder="new password" name="new_password" class="form-control">
                    @error('new_password')
                    <p class="error-message">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="user-confirm-password" class="form-label">Confirm New Password</label>
                    <input type="password" placeholder="Confirm password" id="user-confirm-password" name="new_password_confirmation" class="form-control">
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary waves-effect waves-light float-end">Change</button>
                </div>
            </form>
            </div>
        </div>
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
let accountUserImage = document.getElementById('uploadedAvatar')
const fileInput = document.querySelector('.account-file-input'),
    resetFileInput = document.querySelector('.account-image-reset')

const resetImage = accountUserImage.src
fileInput.onchange = () => {
    if (fileInput.files[0]) {
        accountUserImage.src = window.URL.createObjectURL(fileInput.files[0])
    }
}
resetFileInput.onclick = () => {
    fileInput.value = ''
    accountUserImage.src = resetImage
}
</script>
@endpush