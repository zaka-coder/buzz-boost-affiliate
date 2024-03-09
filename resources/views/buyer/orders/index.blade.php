@extends('layouts.buyer', ['title' => 'Orders'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the orders section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Header section within the orders container -->
        <div class="w-100 h-auto h-md-15">

            <!-- Filter options for orders status -->
            <div class="w-100 h-auto h-md-100 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                <a href="#" class="anchor-button rounded-2 active">All Orders</a>
                <a href="#" class="anchor-button rounded-2">Pending Orders</a>
                <a href="#" class="anchor-button rounded-2">Accepted Orders</a>
                <a href="#" class="anchor-button rounded-2">Declined Orders</a>
            </div>
        </div>

        <!-- Main content area for displaying individual orders -->
        <div class="w-100 h-85 table-responsive">
            <table class="w-100">
                <tr class="w-100" style="background-color:#EFF3F7">
                    <td class="w-auto w-md-5  nunito text-center head-before" style="height:45px">#</td>
                    <td class="w-auto w-md-30 nunito text-center head-before" style="height:45px">Item</td>
                    <td class="w-auto w-md-20 nunito text-center head-before" style="height:45px">Store</td>
                    <td class="w-auto w-md-15 nunito text-center head-before" style="height:45px">Date</td>
                    <td class="w-auto w-md-15 nunito text-center head-before" style="height:45px">Total</td>
                    <td class="w-auto w-md-15 nunito text-center head-before" style="height:45px">Payment Status</td>
                </tr>
                {{-- Orders list --}}
                @forelse ($orders as $order)
                    <tr class="w-100" style="background-color:#FFF;border-top:15px solid  #F8F7FA">
                        <td class="w-auto w-md-5  nunito-regular text-center" style="height:60px">{{ $loop->iteration }}
                        </td>
                        <td class="w-auto w-md-30 nunito-regular text-center px-1" style="height:60px">
                            {{ $order->products->first()->weight . ' ct ' . $order->products->first()->name }}</td>
                        <td class="w-auto w-md-20 nunito-regular text-center" style="height:60px">
                            {{ $order->store->name }}</td>
                        <td class="w-auto w-md-15 nunito-regular text-center" style="height:60px">
                            {{ $order->created_at->format('d/m/Y') }}
                        </td>
                        <td class="w-auto w-md-15 nunito-regular text-center" style="height:60px">
                            ${{ number_format($order->total, 2) }}</td>
                        <td class="w-auto w-md-15 nunito-regular text-center text-capitalize" style="height:60px">
                            {{ $order->payment_status }}</td>
                    </tr>
                @empty
                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                        <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                            style="filter: invert(1)">
                        <p class="nunito">No orders found</p>
                    </div>
                @endforelse
            </table>
        </div>

    </div>
@endsection
