@extends('layouts.buyer', ['title' => 'Offers'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .status {
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
    <!-- Container for the offers section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Header section within the offers container -->
        <div class="w-100 h-auto h-md-15">

            <!-- Filter options for offer status -->
            <div class="w-100 h-auto h-md-100 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                {{-- <a href="{{ route('buyer.offers.index') }}"
                    class="anchor-button rounded-2 @if ($status == null) active @endif">All</a> --}}
                <a href="{{ route('buyer.offers.index', 'pending') }}"
                    class="anchor-button rounded-2 @if ($status == null || $status == 'pending') active @endif">Pending</a>
                <a href="{{ route('buyer.offers.index', 'accepted') }}"
                    class="anchor-button rounded-2 @if ($status == 'accepted') active @endif">Accepted</a>
                <a href="{{ route('buyer.offers.index', 'declined') }}"
                    class="anchor-button rounded-2 @if ($status == 'declined') active @endif">Declined</a>
            </div>
        </div>

        <!-- Main content area for displaying individual offers -->
        <div class="w-100 h-85">
            @if (count($offers) > 0)
                <table class="w-100">
                    <tr class="w-100" style="background-color:#EFF3F7">
                        <td class="w-auto w-md-5  nunito text-center head-before" style="height:45px">#</td>
                        <td class="w-auto w-md-40 nunito text-center head-before" style="height:45px">Product Title</td>
                        <td class="w-auto w-md-20 nunito text-center head-before" style="height:45px">Store Name</td>
                        <td class="w-auto w-md-10 nunito text-center head-before" style="height:45px">Status</td>
                        <td class="w-auto w-md-10 nunito text-center head-before" style="height:45px">Offer</td>
                        <td class="w-auto w-md-15 nunito text-center head-before" style="height:45px">Date</td>
                    </tr>
                    {{-- Offers list --}}
                    @foreach ($offers as $offer)
                        <tr class="w-100" style="background-color:#FFF;border-top:15px solid  #F8F7FA">
                            <td class="w-auto w-md-5  nunito-regular text-center" style="height:60px">{{ $loop->iteration }}
                            </td>
                            <td class="w-auto w-md-40 nunito-regular text-center" style="height:60px">
                                <a href="{{ route('products.show', $offer?->product?->id) }}">{{ $offer?->product?->weight . ' ct ' . $offer?->product?->name }}</a></td>
                            <td class="w-auto w-md-20 nunito-regular text-center" style="height:60px">
                                {{ $offer?->product?->store?->name }}</td>
                            <td class="w-auto w-md-10 nunito-regular text-center text-capitalize" style="height:60px">
                                <span class="status"
                                    style="background-color:@if ($offer?->status == 'pending') #FFC107;
                                     @elseif($offer?->status == 'accepted')#28A745;
                                    @elseif($offer?->status == 'declined')#DC3545;
                                    @else #6C757D @endif">{{ $offer?->status }}</span>
                            </td>
                            <td class="w-auto w-md-10 nunito-regular text-center" style="height:60px">
                                ${{ number_format($offer?->offer_value, 2) }}</td>
                            <td class="w-auto w-md-15 nunito-regular text-center" style="height:60px">
                                {{ $offer?->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No offers found</p>
                </div>
            @endif
        </div>

    </div>
@endsection
