@extends('layouts.buyer', ['title' => 'Bids'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the bids section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Header section within the bids container -->
        <div class="w-100 h-auto h-md-20">

            <!-- Filter options for bid status -->
            <div class="w-100 h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                {{-- <a href="#" class="anchor-button rounded-2 active">Pending</a>
                <a href="#" class="anchor-button rounded-2">Declined</a>
                <a href="#" class="anchor-button rounded-2">All</a> --}}
                <a href="{{ route('buyer.bids.index') }}"
                    class="anchor-button rounded-2 @if ($status == null) active @endif">All</a>
                <a href="{{ route('buyer.bids.index', 'pending') }}"
                    class="anchor-button rounded-2 @if ($status == 'pending') active @endif">Pending</a>
                <a href="{{ route('buyer.bids.index', 'declined') }}"
                    class="anchor-button rounded-2 @if ($status == 'declined') active @endif">Declined</a>
            </div>

            <!-- Sorting options section -->
            <div class="w-100 h-50 pt-2">

                <!-- Sorting dropdown with label -->
                {{-- <div class="ms-auto" style="width: fit-content">
                    <span class="sort" style="font-size: 14px">Sort By :</span>

                    <!-- Dropdown menu for sorting -->
                    <select class="p-1">
                        <option value="1">Most Recent</option>
                        <option value="2">Old</option>
                        <option value="3">Expensive</option>
                    </select>
                </div> --}}
            </div>
        </div>

        <!-- Main content area for displaying individual bids -->
        <div id="bids-container" class="w-100 h-80 p-md-3 p-1" style="background-color: #FFF;">
            @forelse ($bids as $bid)
                <!-- Bids card container with flex layout -->
                <div class="bids-card w-95 m-auto d-flex flex-md-row flex-column align-items-center my-3">
                    <!-- Image section for the bid -->
                    <div class="w-100 w-md-20 h-100 d-flex align-items-center justify-content-center">
                        <img src="{{ asset($bid->product->image ?? 'assets/home/stones.jpg') }}" alt=""
                            width="90%" height="90%" style="object-fit: cover">
                    </div>
                    <!-- Details section for the bid -->
                    <div class="w-100 w-md-40 h-100 p-2">
                        <!-- Title of the bid -->
                        <h3 class="text-truncate"><a
                                href="{{ route('products.show', $bid->product->id) }}">{{ $bid->product->weight . ' ct ' . $bid->product->name }}</a>
                        </h3>
                        <!-- Table for additional bid information -->
                        <table class="w-100">
                            <!-- Row for Auction Id -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Auction Id #:</td>
                                <td class="w-50 nunito-regular">GM{{ $bid->product->id }}</td>
                            </tr>
                            <!-- Row for Bid Starting Date -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Bid Starting Date:</td>
                                <td class="w-50 nunito-regular">{{ $bid->created_at->format('m/d/Y') }}</td>
                            </tr>
                            <!-- Row for Store information -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Store:</td>
                                <td class="w-50 nunito-regular">{{ $bid->product->store->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- Bid amount section -->
                    <div class="w-100 w-md-15 h-100 d-flex align-items-center justify-content-center">
                        <span class="nunito">${{ number_format($bid->price, 2) }}</span>
                    </div>
                    <!-- Bid status section -->
                    <div class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center">
                        <span class="anchor-button m-3 bg-warning rounded-3 border-0 text-capitalize"
                            style="cursor: default;background-color:@if ($bid->status == 'pending') #FFC107;
                        @elseif($bid->status == 'declined')
                        #DC3545!important; @else #28A745; @endif;color:white">{{ $bid->status }}</span>
                    </div>
                </div>
            @empty
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No bids found</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
