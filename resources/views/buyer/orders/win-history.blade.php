@extends('layouts.buyer', ['title' => 'Win History'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .order-status {
            display: block;
            width: fit-content;
            padding: 0px 10px;
            border-radius: 100px;
            color: white;
            font-size: 12px;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the bids section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Header section within the bids container -->
        <div class="w-100 h-auto h-md-20">
            <!-- Filter options for orders status -->
            <div class="w-100 h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                <a href="{{ route('buyer.orders.index') }}"
                    class="anchor-button rounded-2 @if ($status == null) active @endif"
                    style="width: 100px !important">All</a>
                <a href="{{ route('buyer.orders.index', 'pending') }}"
                    class="anchor-button rounded-2 @if ($status == 'pending') active @endif"
                    style="width: 200px !important">Awaiting Payments</a>
                <a href="{{ route('buyer.orders.index', 'paid awaiting shippment') }}"
                    class="anchor-button rounded-2 @if ($status == 'paid awaiting shippment') active @endif"
                    style="width: 200px !important">Awaiting Shipments</a>
                <a href="{{ route('buyer.orders.index', 'paid & shipped') }}"
                    class="anchor-button rounded-2 @if ($status == 'paid & shipped') active @endif"
                    style="width: 200px !important">Paid & Shipped</a>
                <a href="{{ route('buyer.orders.index', 'cancelled') }}"
                    class="anchor-button rounded-2 @if ($status == 'cancelled') active @endif"
                    style="width: 200px !important">Cancelled</a>
            </div>
            {{--
            <!-- Sorting options section -->
            <div class="w-100 h-50 pt-2">

                <!-- Sorting dropdown with label -->
                <div class="ms-auto" style="width: fit-content">
                    <span class="sort" style="font-size: 14px">Sort By :</span>

                    <!-- Dropdown menu for sorting -->
                    <select class="p-1">
                        <option value="1">Most Recent</option>
                        <option value="2">Old</option>
                        <option value="3">Expensive</option>
                    </select>
                </div>
            </div> --}}
        </div>

        <!-- Main content area for displaying individual bids -->
        <div class="w-100 @if ($orders->count() == 0) h-80 @else h-auto @endif p-md-3 p-1"
            style="background-color: #FFF; ">
            @forelse ($orders as $order)
                <!-- Bids card container with flex layout -->
                <div class="bids-card w-100 h-auto m-auto d-flex align-items-center justify-content-between my-3 shadow-none">
                    <div class="w-md-60">
                        @foreach ($order->products as $product)
                            <div class="d-flex  align-items-center">
                                <!-- Image section for the bid -->
                                <div class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center">
                                    <img src="{{ asset($product->image ?? 'assets/home/stones.jpg') }}" alt=""
                                        width="90%" height="90%" style="object-fit: cover">
                                </div>
                                <!-- Details section for the bid -->
                                <div class="w-100 w-md-75 h-100 p-2">
                                    <!-- Title of the bid -->
                                    <h3 class="text-truncate">
                                        {{ $product->weight . ' (Ct) ' . $product->name }}</h3>
                                    <!-- Table for additional bid information -->
                                    <table class="w-100">
                                        @if ($product->productListing?->item_type == 'auction')
                                            <!-- Row for Auction Id -->
                                            <tr class="w-100">
                                                <td class="w-100 nunito-regular">Auction Id #:
                                                    GM00{{ $product->id }}
                                                </td>
                                                <!-- <td class="w-50 nunito-regular">GM00{{ $product->id }}</td> -->
                                            </tr>
                                        @endif
                                        <!-- Row for Bid Starting Date -->
                                        <tr class="w-100">
                                            <td class="w-100 nunito-regular">
                                                @if ($order?->won_via == 'auction')
                                                    Won Via Bid
                                                @elseif ($order?->won_via == 'offer')
                                                    Won Via Make an Offer
                                                @else
                                                    Won Via Cart
                                                @endif
                                                - {{ $order->created_at->format('d M Y') }}
                                            </td>
                                            <!-- <td class="w-50 nunito-regular">2/12/2023</td> -->
                                        </tr>
                                        <!-- Row for Store information -->
                                        <tr class="w-100">
                                            <td class="w-100 nunito-regular">Store: {{ $order?->store?->name }}</td>
                                            <!-- <td class="w-50 nunito-regular">{{ $order?->store?->name }}</td> -->
                                        </tr>
                                        <tr class="w-100">
                                            <td class="w-100 nunito-regular text-capitalize">
                                                <div class="d-flex align-items-center gap-2">Status:
                                                    <span class="order-status"
                                                        style="background-color:
                                                        @if ($order->status == 'pending') gray;
                                                        @elseif($order->status == 'cancelled') red;
                                                        @elseif($order->status == 'paid awaiting shippment') #105082;
                                                        @elseif($order->status == 'paid & shipped') blue;
                                                        @else transparent; @endif">
                                                        {{ $order?->status == 'pending' ? 'Awaiting Payment' : $order?->status }}</span>
                                                </div>
                                            </td>
                                            <!-- <td class="w-50 nunito-regular">{{ $order?->store?->name }}</td> -->
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <span class="vr d-none d-md-block"></span>
                    <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                    <!-- Bid amount section -->
                    {{-- <div class="w-100 w-md-30 h-100 d-flex align-items-center justify-content-center"> --}}
                    <span class="nunito m-3">${{ number_format($order?->total, 2) }}</span>
                    @if ($order->status === 'pending')
                        <span class="vr d-none d-md-block"></span>
                        <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                        <a class="anchor-button rounded-3 m-3 text-white"
                            href="/buyer/cart/product/{{ $order?->products->first()?->id }}/checkout/{{ $order?->id }}"
                            style="background-color: #105082">Checkout</a>
                    @endif
                    {{-- </div> --}}
                </div>
            @empty
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No items</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
