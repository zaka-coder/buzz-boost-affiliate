@extends('layouts.seller', ['title' => 'sellerShipping'])
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
                <a href="/sellerSetting" class="anchor-button rounded-2 ">Basic Settings</a>
                <a href="/sellerShipping" class="anchor-button rounded-2 active">Shipping Address</a>
                <a href="#" class="anchor-button rounded-2">Credit Card</a>
            </div>
        </div>
        <!-- Main content area for displaying individual bids -->
        <div class="w-100 h-auto h-md-85 p-4" style="background-color: #FFF; overflow:auto">
            {{-- row for password --}}
            <div class="row w-100">
                <h1 class="fs-5 fw-bold">Shipping Address</h1>
                <div class="col-md-6 my-2">
                    <fieldset class="w-100">
                        <legend>Address</legend>
                        <input type="text" class="w-100 w-md-90 setting-input">
                    </fieldset>
                </div>
                <div class="col-md-6 my-2">
                    <fieldset class="w-100">
                        <legend>City / Town</legend>
                        <input type="text" class="w-100 w-md-90 setting-input">
                    </fieldset>
                </div>
                <div class="col-md-6 my-2">
                    <fieldset class="w-100">
                        <legend>State / Province</legend>
                        <input type="text" class="w-100 w-md-90 setting-input">
                    </fieldset>
                </div>
                <div class="col-md-6 my-2">
                    <fieldset class="w-100">
                        <legend>Country</legend>
                        <input type="text" class="w-100 w-md-90 setting-input">
                    </fieldset>
                </div>
                <div class="col-md-6 my-2">
                    <fieldset class="w-100">
                        <legend>Postal Code / Zip Code</legend>
                        <input type="number" class="w-100 w-md-90 setting-input">
                    </fieldset>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <button class="anchor-button rounded-2 text-white" style="background-color:#105082">Save Changes</button>
                </div>
            </div>
        </div>

    </div>
@endsection
