@extends('layouts.buyer', ['title' => 'Change Password'])
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
                <a href="{{ route('buyer.settings.index') }}" class="anchor-button rounded-2">Basic Settings</a>
                <a href="{{ route('buyer.settings.shipping') }}" class="anchor-button rounded-2 ">Shipping Address</a>
                <a href="{{ route('buyer.settings.credit-card') }}" class="anchor-button rounded-2">Credit Card</a>
                <a href="#" class="anchor-button rounded-2 active">Change Password</a>
            </div>
        </div>
        <!-- Main content area for displaying individual bids -->
        <div class="w-100 h-auto h-md-85 p-4" style="background-color: #FFF; overflow:auto">
            {{-- change password --}}
            @include('layouts.includes.change-password-form')
        </div>

    </div>
@endsection
