@extends('layouts.buyer', ['title' => 'Awaiting Feedbacks'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .anchor-button {
            width: 170px;
            /* line-height: initial!important; */
        }

        textarea {
            border: 1px solid rgba(128, 128, 128, 0.192);
            /* border-right: none; */
            padding: 10px 10px
        }

        .bids-card {
            border: 1px solid rgba(128, 128, 128, 0.192);
        }

        textarea:active,
        textarea:focus {
            outline: none;
            box-shadow: none
        }


        .feedback-radio {
            box-sizing: border-box;
            appearance: none;
            background: white;
            outline: 2px solid #333;
            border: 3px solid white;
            width: 14px;
            height: 14px;
        }

        .feedback-radio:checked {
            background: #333;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the orders section -->
    <div class="bids-main-container w-100 h-100">
        <!-- Header section within the orders container -->
        <div class="w-100 h-auto h-md-15">
            <!-- Filter options for order status -->
            <div class="w-100 h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                <a href="{{ route('buyer.feedbacks.index') }}" class="anchor-button rounded-2">Feedbacks History</a>
                <a href="{{ route('buyer.feedbacks.awaiting') }}" class="anchor-button active rounded-2"
                    style="width: 200px !important">Awaiting Feedbacks</a>
            </div>
        </div>
        <!-- Main content area for displaying individual orders -->
        <div id="orders-container" class="w-100 h-85 p-md-3 p-1" style="background-color: #FFF; overflow:auto">

            <!-- Orders section -->
            @if ($products->count() > 0)
                @foreach ($products as $product)
                    <!-- orders card container with flex layout -->
                    <div class="bids-card d-flex h-auto  flex-column w-95 m-auto shadow-sm my-3">
                        <div class="w-100 d-flex flex-md-row flex-column align-items-center"
                            style="height:130px;border-bottom:1px solid rgba(128, 128, 128, 0.164)">
                            <!-- Image section for the order -->
                            <div class="w-100 w-md-20 h-100 d-flex align-items-center justify-content-center">
                                <img src="{{ asset($product->image ?? 'assets/home/stones.jpg') }}" alt=""
                                    width="100%" height="100%" style="object-fit: cover;border-top-left-radius:3px">
                            </div>
                            <!-- Details section for the order -->
                            <div
                                class="w-100 w-md-60 h-100 p-2 d-flex flex-column justify-content-center align-items-center ">
                                <!-- Title of the order -->
                                <h3 class=" w-100 text-truncate text-uppercase">
                                    {{ $product->weight . ' ct ' . $product->name }}
                                </h3>
                                <!-- Table for additional order information -->
                                <table class="w-100">
                                    <!-- Row for Auction Id -->
                                    <tr class="w-100">
                                        <td class="w-40 nunito-regular text-center">Auction Id #:</td>
                                        <td class="w-50 nunito-regular text-center">GM00{{ $product->id }}</td>
                                    </tr>
                                    <!-- Status -->
                                    <tr class="w-100">
                                        <td class="w-40 nunito-regular text-center">Status</td>
                                        <td class="w-50 nunito-regular text-capitalize text-center">
                                            {{ $product?->status }}
                                        </td>
                                    </tr>
                                    <!-- Row for winning date -->
                                    {{-- <tr class="w-100">
                                    <td class="w-40 nunito-regular"></td>
                                    <td class="w-50 nunito-regular">22/11/2024</td>
                                </tr> --}}
                                </table>
                            </div>
                            <!-- order amount section -->
                            <div class="w-100 w-md-20 h-100 d-flex align-items-center justify-content-center">
                                <span
                                    class="nunito">${{ $product?->productListing?->item_type == 'auction' ? $product?->productPricing?->starting_price : $product?->productPricing?->buy_it_now_price }}</span>
                            </div>
                        </div>
                        <div class="w-100 d-flex">
                            {{-- <div class="w-90 m-auto d-flex py-3"> --}}
                            <form action="{{ route('buyer.feedbacks.store') }}" method="post"
                                class="w-90 m-auto d-flex flex-column py-3">
                                @csrf
                                {{-- for textarea --}}
                                <div class="row">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="w-50">
                                        <textarea name="feedback" id="" cols="30" rows="6" class="w-100" placeholder="Please enter your feedback here...."></textarea>
                                    </div>
                                    <div class="w-50 bg-success">
                                        <table class="table w-100 h-100">
                                            <tr class="w-100 h-30">
                                                <td class="w-100 border-0">
                                                    <div class="w-50  ms-auto">
                                                        <div
                                                            class="w-100 d-flex align-items-center justify-content-start gap-2">
                                                            <input type="radio" name="rating" id="positive"
                                                                value="positive" class="feedback-radio">
                                                            <label for="positive">Positive</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="w-100 h-30">
                                                <td class="w-100 border-0">
                                                    <div class="w-50  ms-auto">
                                                        <div
                                                            class="w-100 d-flex align-items-center justify-content-start gap-2">
                                                            <input type="radio" name="rating" id="negative"
                                                                value="negative" class="feedback-radio">
                                                            <label for="negative">Negative</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="w-100 h-30">
                                                <td class="w-100 border-0">
                                                    <div class="w-50  ms-auto">
                                                        <div
                                                            class="w-100 d-flex align-items-center justify-content-start gap-2 ">
                                                            <input type="radio" name="rating" id="neutral"
                                                                value="neutral" class="feedback-radio">
                                                            <label for="neutral">Neutral</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="w-100 d-flex justify-content-end mt-3">
                                    <button type="submit" class="anchor-button rounded-3 d-block"
                                        style="background: #105082;color:white; width:120px !important">Submit</button>
                                </div>
                            </form>
                            {{-- </div> --}}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No items found</p>
                </div>
            @endif
        </div>
    </div>
@endsection
