@extends('layouts.buyer', ['title' => 'Buyer Setting'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        legend {
            font-size: 17px;
            font-family: nunito-regular;
            line-height: 1;
        }

        .setting-input {
            height: 34px;
            border-radius: 8px;
            padding-left: 9px;
            border: none;
            background-color: rgba(34, 34, 34, 0.132) !important;
        }

        .setting-input:active,
        .setting-input:focus {
            box-shadow: none;
            outline: none;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the setting section -->
    <div class="bids-main-container w-100 h-100">
        <!-- Header section within the setting container -->
        <div class="w-100 h-auto h-md-15">
            <!-- navbar area -->
            <div class="w-100 h-auto h-md-100 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                <a href="#" class="anchor-button rounded-2 active">Basic Settings</a>
                <a href="{{ route('buyer.settings.shipping') }}" class="anchor-button rounded-2 ">Shipping Address</a>
                <a href="{{ route('buyer.settings.credit-card') }}" class="anchor-button rounded-2">Credit Card</a>
                <a href="{{ route('password.change') }}" class="anchor-button rounded-2">Change Password</a>
            </div>
        </div>
        <!-- Main content area for displaying individual bids -->
        <div class="w-100 h-auto h-md-85 p-4" style="background-color: #FFF; overflow:auto">
            {{-- member details --}}
            <form action="{{ route('buyer.settings.update.profile') }}" method="post" class="mb-4">
                @csrf
                @method('put')
                <div class="row w-100 my-2">
                    <h1 class="fs-5 fw-bold">Member Details</h1>
                    <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>Name</legend>
                            <input type="text" name="name" class="w-100 w-md-90 setting-input" value="{{ $user->name }}">
                        </fieldset>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>Email</legend>
                            <input type="email" name="email" class="w-100 w-md-90 setting-input" value="{{ $user->email }}">
                        </fieldset>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>TimeZone</legend>
                            <input type="email" class="w-100 w-md-90 setting-input">
                        </fieldset>
                    </div> --}}
                    <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>Phone</legend>
                            <input type="tel" name="phone" class="w-100 w-md-90 setting-input" value="{{ $user->profile->phone }}">
                        </fieldset>
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <button type="submit" class="anchor-button rounded-2 text-white" style="background-color:#105082">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
