@extends('layouts.admin')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Audit Response</h4>
    </div>
    <div class="row my-4 mx-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.audits.index') }}" class="anchor-button rounded-2" style="width: 100px">Back</a>
            </div>
        </div>
    </div>
    <div class="text-start m-5">
        <div class="col-md-12">
            <div class="row">
                <h3 class="mb-4">User Details</h3>
                <div class="col-md-4">
                    <div>
                        <img src="{{ $user->profile->image ? asset($user->profile->image ?? 'assets/buyer-assets/user-icon.png') : asset('assets/buyer-assets/user-icon.png') }}" alt="img" class="img-fluid" width="200">
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <strong>Name: </strong>
                        <p>{{ $user->name }}</p>
                        <strong>Email: </strong>
                        <p>{{ $user->email }}</p>
                        <strong>Phone: </strong>
                        <p>{{ $user->profile->phone }}</p>
                        <strong>Status: </strong>
                        <p>{{ $user->status ? 'Active' : 'Inactive' }} (ct)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <strong>City: </strong>
                        <p>{{ $user->profile->city }}</p>
                        <strong>State: </strong>
                        <p>{{ $user->profile->state }}</p>
                        <strong>Zip: </strong>
                        <p>{{ $user->profile->zip }}</p>
                        <strong>Address: </strong>
                        <p>{{ $user->profile->address }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
