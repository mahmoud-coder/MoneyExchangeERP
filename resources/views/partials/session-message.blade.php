@if($success = Session::get('success'))
<div class="alert alert-primary alert-dismissible d-flex align-items-baseline" role="alert">
<span class="alert-icon alert-icon-lg text-primary me-2">
    <i class="ti ti-user ti-sm"></i>
</span>
<div class="d-flex flex-column ps-1">
    <h5 class="alert-heading mb-2">Success</h5>
    <p class="mb-0">{!! $success !!}</p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    </button>
</div>
</div>
@endif