@extends('layouts.seller', ['title' => 'feedback index'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .anchor-button {
            width: 170px;
        }

        .nunito {
            font-family: nunito;
            font-size: 16px;
            padding: 0;
            margin: 0;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <!-- Header section within the bids container -->
    <div class="w-100 h-100">
        <div class="w-100 h-auto h-md-25 ">
            <div class=" h-auto h-md-40 d-flex justify-content-end w-100 px-4">
                <a href="{{ route('seller.sales.index') }}" class="anchor-button rounded-2"
                    style="width: 100px !important">Back</a>
            </div>
            <!-- Sorting options section -->
            <div class="w-100 h-60   d-flex rounded-2 " style="background-color:#E1E1E1">
                <div class="w-50 h-100  d-flex">
                    <div class="w-15 h-100  rounded-3 bg-warning">
                        <img class="rounded-3"
                            src="{{ asset($customer->profile->image ?? 'assets/buyer-assets/zaka.jpeg') }}" alt="pic"
                            style="width: 100%;max-width:100%;height:100%;object-fit:cover;border-bottom-right-radius: 0px!important;border-top-right-radius:0px!important;border-bottom-left-radius: 0px!important">
                    </div>
                    <div class="w-85 h-100">
                        <div class="w-100 h-50  p-2">
                            <h4 class="nunito text-start p-0 m-0" style="font-size: 19px">{{ $customer->name }}
                                @if ($customer->is_blocked)
                                    <span class="ms-2 badge bg-danger">Blocked</span>
                                @endif
                            </h4>
                        </div>
                        <div class="w-100 h-50 p-1">
                            @if ($customer->email_verified_at)
                            <span class="nunito-regular" style="color: green;font-size:14px">Account Verified </span>
                            @else
                            <span class="nunito-regular" style="color: red;font-size:14px">Account Not Verified </span>
                            @endif /
                            <span class="nunito-regular" style="font-size: 14px">{{ $customer->profile->address }} / </span>
                            <span class="nunito-regular"
                                style="font-size: 14px">{{ $customer->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Sorting dropdown with label -->
                <div class="w-50 h-100" style="">
                    <div class="w-90 h-100 ms-auto d-flex align-items-center justify-content-center gap-1">
                        <button type="button" class="anchor-button rounded-2 text-dark" style="border: 1px solid  #105082" onclick="document.getElementById('message-form').submit()">Message
                            <span></span></button>
                            <form id="message-form" action="{{ route('chats.store', ['role' => 'seller']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $customer->id }}">
                            </form>
                        @if ($customer->is_blocked)
                            <button class="anchor-button rounded-2" style="border: 1px solid #105082"
                                onclick="document.getElementById('unblock-form').submit()">Unblock
                            </button>
                            <form id="unblock-form" action="{{ route('seller.users.unblock', $customer->id) }}"
                                method="POST">
                                @csrf
                            </form>
                        @else
                            <button class="anchor-button rounded-2" style="border: 1px solid #105082"
                                onclick="document.getElementById('block-form').submit()">Block
                            </button>
                            <form id="block-form" action="{{ route('seller.users.block', $customer->id) }}" method="POST">
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedbacks section -->
        @include('layouts.includes.feedbacks', ['user' => 'buyer'])
    </div>
@endsection


@section('js')
@endsection
