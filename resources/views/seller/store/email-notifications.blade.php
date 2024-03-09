@extends('layouts.seller', ['title' => 'Store Details'])
@section('css')
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .anchor-button {
            /* Common styles for both anchor and button */
            display: inline-block;
            text-decoration: none;
            font-size: 16px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            color: #333;
            cursor: pointer;
            width: 150px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            color: #105082;
            font-family: nunito;
            background-color: transparent;
        }

        .anchor-button.active {
            background-color: #105082;
            color: white;
        }

        input[type='text'],
        input[type='email'],
        input[type='file'],
        input[type='number'],
        select {
            border: none;
            border-radius: 6px;
            padding-left: 15px;
            padding-right: 15px;
            background-color: rgba(34, 34, 34, 0.132);
            height: 34px;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, sans-serif;
            margin: 0;
        }

        input[type='text']:focus,
        input[type='email']:focus,
        input[type='file']:focus,
        input[type='number']:focus,
        select:focus {
            outline: none;
            box-shadow: none;
        }

        textarea {
            font-size: 14px;
            padding-left: 15px;
        }

        textarea:focus {
            outline: none;
            box-shadow: none;
        }

        legend {
            font-size: 14px;
            font-weight: 400;
            line-height: 0.5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: 0px;
            color: #000000;
            opacity: 1;
        }

        input[type='radio']:checked {
            background-color: #105082
        }

        input[type='checkbox'],
        input[type='radio'] {
            height: 19px;
            width: 19px;
        }

        .form-control {
            border-top-left-radius: 6px !important;
            border-bottom-left-radius: 6px !important;
        }

        .form-control:focus {
            background-color: rgba(34, 34, 34, 0.132);
        }
    </style>
@endsection
@section('content')
    <!-- Container for the store Details section -->
    <div class="settings-main-container w-100 h-100">

        <!-- Header section within the store Details container -->
        @include('seller.store.store_details_header')

        <!-- settings starts from here -->
        <div class="w-100 h-auto px-2 px-md-5 py-3" style="background-color:#FFF;">
            <div class="w-100 h-100">
                <h5 class="my-3"><b>Email Notifications</b></h5>
                <form action="{{ route('seller.store.update-email-notifications', $store->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <!-- row for minimum offer -->
                    <div class="row mt-3">
                        <div class="col-lg-12 col-12">
                            {{-- <h5 class="my-3"><b>Email Notifications</b></h5> --}}
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <input type="checkbox" name="offer_made" id="offer_made" value="1"
                                    @if ($store?->emailNotifications?->offer_made == 1) checked @endif><label for="offer_made"
                                    style="font-size: 14px">Send me an email
                                    every time an offer is made on my items</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <input type="checkbox" name="item_sold" id="item_sold" value="1" @if ($store?->emailNotifications?->item_sold == 1) checked @endif><label
                                    for="item_sold" style="font-size: 14px">Send me an email
                                    every time one of my item is sold</label>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex align-items-center gap-2 mt-3">
                                    <input type="checkbox" name="payment_received" id="payment_received" value="1" @if ($store?->emailNotifications?->payment_received == 1) checked @endif><label
                                        for="payment_received" style="font-size: 14px">Send me an email
                                        every time I receive a payment</label>
                                </div>
                            </div>
                            <div class="my-5">
                                <button type="submit" class="anchor-button text-white rounded-2 border-0"
                                    style="background-color: #105082">Save Changes</button>
                            </div>
                        </div>
                        <div class="col-lg-6 d-none d-lg-block">
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
