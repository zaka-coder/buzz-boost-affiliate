@extends('layouts.seller', ['title' => 'sales index'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .anchor-button {
            width: 170px;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the bids section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Header section within the bids container -->
        <div class="w-100 h-auto h-md-25">
            <!-- Filter options for bid status -->
            <div class="w-100  h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                <a href="sales-index" class="anchor-button rounded-2 ">Awaiting Payments</a>
                <a href="sales-cancel" class="anchor-button rounded-2 ">Cancelled</a>
                <a href="sales-awaiting" class="anchor-button rounded-2">Awaiting Shipments</a>
                <a href="sales-paid" class="anchor-button rounded-2 active">Paid & Shipped</a>
            </div>
            <!-- Sorting options section -->
            <div class="w-100  h-50 d-flex rounded-2" style="background-color:#105082">
                <h3 class="p-0 m-0 w-60  h-100 d-flex align-items-center justify-content-start ms-3"
                    style="font-size:17px;color:white;">7 sales shipped and paid </h3>
                <!-- Sorting dropdown with label -->
                <div class="w-40 h-100 " style="">
                    <div class="d-flex align-items-center justify-content-center ms-auto mt-3 me-2"
                        style="width:fit-content">
                        <span class="sort text-white" style="font-size: 14px">Sort By :&nbsp;&nbsp;</span>
                        <!-- Dropdown menu for sorting -->
                        <select class="p-1 text-dark bg-white" style="cursor: pointer">
                            <option value="1">Most Recent</option>
                            <option value="2">Price(Low to high)</option>
                            <option value="3">Price(High to Low)</option>
                            <option value="4">Oldest Sales</option>
                            <option value="5">Most Bids</option>
                            <option value="6">Most Views</option>
                            <option value="7">Most Bids</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content area for displaying individual bids -->
        <div id="bids-container" class="w-100 h-75  p-md-3 p-1" style="background-color: #FFF; overflow:auto">
            @for ($i = 0; $i < 3; $i++)
                <!-- Bids card container with flex layout -->
                <div class="bids-card w-95 m-auto d-flex flex-md-row flex-column align-items-center my-3">
                    <!-- Image section for the bid -->
                    <div class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('assets/home/stones.jpg') }}" alt="" width="90%" height="90%"
                            style="object-fit: cover">
                    </div>
                    <!-- Details section for the bid -->
                    <div class="w-100 w-md-45 h-100 p-2">
                        <!-- Title of the bid -->
                        <h3 class="text-truncate">Yeah that stone</h3>
                        <!-- Table for additional bid information -->
                        <table class="w-100">
                            <!-- Row for Auction Id -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Auction Id #:</td>
                                <td class="w-50 nunito-regular">1292929</td>
                            </tr>
                            <!-- Row for Bid Starting Date -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Bid Starting Date:</td>
                                <td class="w-50 nunito-regular">22/11/2024</td>
                            </tr>
                            <!-- Row for Store information -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Store:</td>
                                <td class="w-50 nunito-regular">Stone Store</td>
                            </tr>
                        </table>
                    </div>
                    <span class="vr d-none d-md-block"></span>
                    <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                    <!-- Bid amount section -->
                    <div class="w-100 w-md-10 h-100 d-flex align-items-center justify-content-center">
                        <span class="nunito">$ 225</span>
                    </div>
                    <span class="vr d-none d-md-block"></span>
                    <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                    <!-- Bid status section -->
                    <div class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center position-relative">
                        <button class="anchor-button rounded-3 m-auto d-block" id="update-cancel-sales-button"
                            style="background: #105082;color:white;width:120px!important">Action</button>
                        <table class="rounded-2 position-absolute py-2" id="update-cancel-sales-table"
                            style="width: 160px;height:auto;background-color:#105082;display:none;top:80px;z-index:1">
                            <tr>
                                <td class="ps-2 text-white">
                                    <h2 class="nunito p-0 m-0 text-white">Update Sales Status</h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">
                                    <button class="bg-transparent border-0 text-white">Cancel</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">
                                    Awaiting payments
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">Paid awaiting shippment</td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">Paid & Shipped</td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">Sales Summary</td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">
                                    <h2 class="nunito p-0 m-0 text-white">Member Info</h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">Postal Address</td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">send messege</td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">view Profile</td>
                            </tr>
                            <tr>
                                <td class="ps-2 text-white">Search Purchases</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- @empty --}}
                {{-- <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No bids found</p>
                </div> --}}
            @endfor
        </div>

        <!--  modals section -->
        <!-- modal for postal address starts here -->
        <div class="modal fade" id="postal-address" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false" style="z-index: 99999">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                <div class="modal-content p-3">
                    <div class="modal-header w-100 position-relative" style="border: 0;--bs-modal-header-padding:0">
                        <h2 class="text-dark">Zaka Ullah Postal Address</h2>
                        <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                            aria-label="Close" style="right: 0!important;margin:0"></button>
                    </div>
                    <div class="modal-body p-0 m-0 w-100 d-flex align-items-start flex-column flex-md-row  m-0"
                        style="border: none">
                        <div class="postal-address-left w-50  mt-3">
                            <h4 class="nunito" style="font-size: 17px">Shangla Pakistan kpk</h4>
                            <p class="w-80" style="text-align: justify">Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Molestiae ad velit sunt laborum
                                culpa fuga earum illo? Illo maxime cupiditate officia eum? Eaque, officiis fugit?</p>
                        </div>
                        <div class="postal-address-right w-50  mt-3">
                            <div>
                                <h4 class="nunito" style="font-size: 17px">Gmail</h4>
                                <p class="">zakakahan12121@gmail.com</p>
                            </div>
                            <div>
                                <h4 class="nunito" style="font-size: 17px">Phone</h4>
                                <p>0312121214</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for postal address ends here -->

    </div>
@endsection
@section('js')
    <script>
        var updateSalesCancelButton = document.getElementById('update-cancel-sales-button');
        var updateSalesCancelTable = document.getElementById('update-cancel-sales-table');

        updateSalesCancelButton.addEventListener('click', function() {
            if (updateSalesCancelTable.style.display === 'none') {
                updateSalesCancelTable.style.display = 'block';
            } else {
                updateSalesCancelTable.style.display = 'none';
            }
        });
    </script>
@endsection
