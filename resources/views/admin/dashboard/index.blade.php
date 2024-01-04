@extends('admin.layouts.main')

@section('content-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-6">
        @include('admin.dashboard.analytics-slider')
    </div>
    <div class="col-lg-3">
        <x-card title="Employees">
            <div class="d-flex justify-content-center m-2">
                <div class="badge bg-label-primary rounded p-3"><i class="ti ti-users ti-sm"></i></div>
            </div>
            <div class="d-flex align-items-center justify-content-between my-1">
                <span class="text-primary"><i class="ti ti-check me-1"></i>Total Employees:</span> <span class="bg-label-info p-2">{!! $Lefted_employees_count + $working_employees_count !!}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between my-1">
                <span class="text-primary"><i class="ti ti-check me-1"></i>Working Employees:</span> <span class="bg-label-info p-2">{!!  $working_employees_count !!}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between my-1">
                <span class="text-primary"><i class="ti ti-check me-1"></i>Lefted Employees:</span> <span class="bg-label-info p-2">{!! $Lefted_employees_count !!}</span>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3">
        <x-card title="Users">
            <div class="d-flex justify-content-center m-2">
                <div class="badge bg-label-info rounded p-3" style="font-size:1.5rem;">{!! $users_count !!}</div>
            </div>
            <div class="text-center">
                <span class="text-primary">Total Users</span>
            </div>
        </x-card>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6">
        <x-card title="Top Countries of The Customers">
            <p class="text-light small" style="font-style:italic">
                showing a maximum 5 countries
            </p>
            <div>
                <x-divider label="Individual Customers"></x-divider>
                @foreach($top_individual_customer_countries as $country)
                <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="bg-label-info flex-grow-1 rounded-start p-2">
                        <i class="ti ti-flag me-3"></i> {{$country->name}}
                    </span>
                    <span class="bg-label-warning rounded-end p-2">{{$country->count}} Customer</span>
                </div>
                @endforeach
            </div>
            <div>
                <x-divider label="Entity Customers"></x-divider>
                @foreach($top_entity_customer_countries as $country)
                <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="bg-label-primary flex-grow-1 rounded-start p-2">
                        <i class="ti ti-flag me-3"></i> {{$country->name}}
                    </span>
                    <span class="bg-label-warning rounded-end p-2">{{$country->count}} Entity</span>
                </div>
                @endforeach
            </div>

        </x-card>
    </div>
    <div class="col-lg-6">
        <x-card title="Last Wages Expense">
            @if($last_wages_payout)
            <div class="d-flex justify-content-center m-2">
                <div class="badge bg-label-danger rounded p-3"><i class="ti ti-credit-card ti-sm"></i></div>
            </div>
            <div class="text-center my-2">
                <b class="text-warning">The last wages expense incurred at: </b> <span>{!! $last_wages_payout->incurred_at !!}</span>
            </div>
            <div class="text-center my-2">
                <b class="text-warning">Total net pay:</b> <span>&euro; {!! $last_wages_payout->net_pay !!}</span>
            </div>
            @else
            <div class="border p-2 rounded-pill text-center">No Wages has been created yet!</div>
            @endif
        </x-card>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="/css/dashboard.css">
@endpush

@push('scripts')
<script src="/js/dashboard.js"></script>
@endpush



