@extends('layouts.seller', ['title' => 'Sales'])
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

        .action-hover:hover {
            background-color: #e1e1e12f;
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
    <!-- Container for the orders section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Header section within the orders container -->
        <div class="w-100 h-auto h-md-25">
            <!-- Filter options for order status -->
            <div class="w-100 h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                {{-- <a href="{{ route('seller.sales.index') }}"
                    class="anchor-button rounded-2 @if ($status == null) active @endif">All</a> --}}
                <a href="{{ route('seller.sales.index', 'pending') }}"
                    class="anchor-button rounded-2 @if ($status == 'pending') active @endif">Awaiting Payments</a>
                <a href="{{ route('seller.sales.index', 'paid awaiting shippment') }}"
                    class="anchor-button rounded-2 @if ($status == 'paid awaiting shippment') active @endif">Awaiting Shipments</a>
                <a href="{{ route('seller.sales.index', 'paid & shipped') }}"
                    class="anchor-button rounded-2 @if ($status == 'paid & shipped') active @endif">Paid & Shipped</a>
                <a href="{{ route('seller.sales.index', 'cancelled') }}"
                    class="anchor-button rounded-2 @if ($status == 'cancelled') active @endif">Cancelled</a>
            </div>
            <!-- Sorting options section -->
            <div class="w-100 h-50 d-flex rounded-2" style="background-color:#105082">
                <h3 class="p-0 m-0 w-60  h-100 d-flex align-items-center justify-content-start ms-3"
                    style="font-size:17px;color:white;"><span
                        class="text-capitalize">{{ $orders->count() . ' Sales ' }}{{ $status === 'pending' ? 'Awaiting Payments' : $status }}</span>
                </h3>
                <!-- Sorting dropdown with label -->
                <div class="w-40 h-100 " style="">
                    {{-- <div class="d-flex align-items-center justify-content-center ms-auto mt-2 me-2"
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
                        </select>
                    </div> --}}
                </div>
            </div>
        </div>


        <!-- Main content area for displaying individual orders -->
        <div id="orders-container" class="w-100  @if ($orders->count() == 0) h-75  @else h-auto @endif p-md-3 p-1"
            style="background-color: #FFF;">

            @forelse ($orders as $userId => $UserOrders)
                @php
                    $user = App\Models\User::find($userId);

                    $store = auth()->user()->store;
                    // check if customer is blocked by current store
                    $isBlocked = $store->blocked_users->contains($userId);

                    if ($isBlocked) {
                        // Customer is blocked by the current store
                        $user->is_blocked = true;
                    } else {
                        // Customer is not blocked by the current store
                        $user->is_blocked = false;
                    }

                @endphp
                <!-- Customer section -->
                <div class="w-100  d-flex rounded-2 " style="background-color:#E1E1E1;height:50px">
                    <h3 class="p-0 m-0 w-60  h-100 d-flex align-items-center justify-content-start ms-3 text-dark"
                        style="font-size:17px;color:white;"><i
                            class="bi bi-person-fill"></i>&nbsp;&nbsp;<span>{{ $user->name ?? 'N/A' }}</span>&nbsp;&nbsp;
                        @if ($user->is_blocked)
                            <span class="ms-2 badge bg-danger">Blocked</span>
                        @endif
                    </h3>
                    <!-- Sorting dropdown with label -->
                    <div class="w-40 h-100" style="">
                        <div class="ms-auto  me-2 py-1" style="width:fit-content">
                            <!-- Dropdown menu for sorting -->
                            <div style="width: 150px"><button class="anchor-button rounded-3"
                                    id="action-button{{ $user->id }}"
                                    style="background: #105082;color:white;width:120px;">Action</button></div>
                            <table class="rounded-2 position-relative py-2" id="action-table{{ $user->id }}"
                                style="width: 120px;height:auto;background-color:#105082;display:none;z-index:2">
                                <tr>
                                    <td class="py-1"></td>
                                </tr>
                                {{-- <tr class="">
                                    <td class="ps-2 text-white action-hover">
                                        <button type="button" class="bg-transparent border-0 text-white"
                                            data-bs-toggle="modal"
                                            data-bs-target="#postal-address{{ $user->id }}">Postal Address</button>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <td class="ps-2 text-white action-hover">
                                        {{-- <a href="/sales-profile" class="text-white">View Profile</a> --}}
                                        <a href="{{ route('seller.sales.customer-profile', $user->id) }}"
                                            class="text-white">View Profile</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-2 text-white action-hover">
                                        @if ($user->is_blocked)
                                            <a href="javascript:void(0)" class="text-white"
                                                onclick="document.getElementById('unblock-form-{{ $user->id }}').submit()">
                                                Unblock
                                            </a>
                                            <form id="unblock-form-{{ $user->id }}"
                                                action="{{ route('seller.users.unblock', $user->id) }}" method="POST">
                                                @csrf
                                            </form>
                                        @else
                                            <a href="javascript:void(0)" class="text-white"
                                                onclick="document.getElementById('block-form-{{ $user->id }}').submit()">
                                                Block
                                            </a>
                                            <form id="block-form-{{ $user->id }}"
                                                action="{{ route('seller.users.block', $user->id) }}" method="POST">
                                                @csrf
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td class="ps-2 text-white action-hover">
                                        View Purchases
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>
                </div>
                @foreach ($UserOrders as $order)
                    <!-- Orders section -->
                    @if ($status == 'paid awaiting shippment')
                        <div id="bids-container" class="w-100 h-50 p-1" style="background-color: #FFF; overflow:auto">
                            <!-- Bids card container with flex layout -->
                            <div class="bids-card w-100 h-auto m-auto d-flex align-items-center my-3 shadow-none">
                                <div class="w-md-60">
                                    @foreach ($order->products as $product)
                                        <div class="d-flex  align-items-center">
                                            <!-- Image section for the bid -->
                                            <div
                                                class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center">
                                                <img src="{{ asset($product->image ?? 'assets/home/stones.jpg') }}"
                                                    alt="" width="90%" height="90%" style="object-fit: cover">
                                            </div>
                                            <!-- Details section for the bid -->
                                            <div class="w-100 w-md-75 h-100 p-2">
                                                <!-- Title of the bid -->
                                                <h3 class="text-truncate"><a
                                                        href="{{ route('products.show', $product->id) }}">{{ $product->weight . ' ct ' . $product->name }}</a>
                                                </h3>
                                                <!-- Table for additional bid information -->
                                                <table class="w-100">
                                                    <!-- Row for Auction Id -->
                                                    <tr class="w-100">
                                                        <td class="w-40 nunito-regular">
                                                            {{ $product->productListing->item_type == 'auction' ? 'Auction' : 'Item' }}
                                                            Id #:</td>
                                                        <td class="w-50 nunito-regular">GM{{ $product->id }}
                                                        </td>
                                                    </tr>
                                                    <!-- Row for Bid Starting Date -->
                                                    <tr class="w-100">
                                                        <td class="w-40 nunito-regular">Won via </td>
                                                        <td class="w-50 nunito-regular">
                                                            @if ($order?->won_via == 'auction')
                                                                Bid
                                                            @elseif ($order?->won_via == 'offer')
                                                                Make an Offer
                                                            @else
                                                                Cart
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <!-- Status -->
                                                    <tr class="w-100">
                                                        <td class="w-40 nunito-regular">Status</td>
                                                        <td class="w-50 nunito-regular text-capitalize">
                                                            <span class="order-status"
                                                                style="background-color:@if ($order->status == 'pending') gray;
                                                @elseif($order->status == 'cancelled') red;
                                                @elseif($order->status == 'paid awaiting shippment') #105082;
                                                @elseif($order->status == 'paid & shipped') blue;
                                                @else transparent; @endif">
                                                                {{ $order->status === 'pending' ? 'Awaiting Payments' : $order->status }}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="vr d-none d-md-block"></span>
                                <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                                <!-- Bid amount section -->
                                <div class="w-100 w-md-20 h-100 d-flex align-items-center justify-content-center">
                                    <span class="nunito">${{ $order->total }}</span>
                                </div>
                                <span class="vr d-none d-md-block"></span>
                                <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>

                                {{-- Action button --}}
                                <div
                                    class="w-100 w-md-20 h-100 d-flex align-items-center justify-content-center position-relative">
                                    <button class="anchor-button rounded-3 m-auto d-block"
                                        id="update-sales-button{{ $order->id }}"
                                        style="background: #105082;color:white;width:120px!important">Action</button>
                                    <table class="rounded-2 position-absolute py-2"
                                        id="update-sales-table{{ $order->id }}"
                                        style="width: 160px;height:auto;background-color:#105082;display:none;top:40px;z-index:1">
                                        <tr>
                                            <td class="py-1"></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-2 text-white action-hover">
                                                <a href="javascript:void(0)" id="markShippedBtn-{{ $order->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#orderTrackingModal{{ $order->id }}"
                                                    class="bg-transparent border-0 text-white w-100 d-block">Mark as
                                                    Shipped</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-2 text-white action-hover">
                                                <a href="{{ route('seller.sales.update.status', ['id' => $order->id, 'status' => 'cancelled']) }}"
                                                    class="bg-transparent border-0 text-white w-100 d-block">Cancel</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-2 text-white action-hover">
                                                <button type="button" class="bg-transparent border-0 text-white"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#postal-address{{ $user->id }}">Shipping
                                                    Address</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-2 text-white action-hover">
                                                <a href="{{ route('seller.sales.summary', $order->id) }}"
                                                    class="bg-transparent border-0 text-white w-100 d-block">Sales
                                                    Summary</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="w-100">
                                <table class="table table-bordered w-100 p-0 m-0">
                                    <tr class="w-100">
                                        <td class="w-20">Payment Provider</td>
                                        <td class="w-80">{{ $order->payment_method ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20">Transiction ID</td>
                                        <td class="w-80">{{ $order->vendor_order_id ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                                <table class="table table-bordered w-100 m-0 p-0">
                                    <tr class="w-100">
                                        <td class="w-80 text-end">Sub Total</td>
                                        <td class="w-20">
                                            ${{ $order->total - $order->shipping_cost - $order->postal_insurance - $order->taxes }}
                                        </td>
                                    </tr>
                                    <tr class="w-100">
                                        <td class="w-80 text-end">Registered Shipping Postage</td>
                                        <td class="w-20">${{ $order->shipping_cost ?? '0' }}</td>
                                    </tr>
                                    <tr class="w-100">
                                        <td class="w-80 text-end">Insurance</td>
                                        <td class="w-20">${{ $order->postal_insurance ?? '0' }}</td>
                                    </tr>
                                    <tr class="w-100">
                                        <td class="w-80 text-end">Tax</td>
                                        <td class="w-20">${{ $order->taxes ?? '0' }}</td>
                                    </tr>
                                    <tr class="w-100">
                                        <td class="w-80 text-end">Total</td>
                                        <td class="w-20">${{ $order->total ?? '0' }}</td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    @else
                        <!-- orders card container with flex layout -->
                        <div class="bids-card w-100 h-auto m-auto d-flex align-items-center my-3 shadow-none">
                            <div class="w-md-60 ">
                                @foreach ($order->products as $product)
                                    <div class="d-flex  align-items-center">
                                        <!-- Image section for the bid -->
                                        <div class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center">
                                            <img src="{{ asset($product->image ?? 'assets/home/stones.jpg') }}"
                                                alt="" width="90%" height="90%" style="object-fit: cover">
                                        </div>
                                        <!-- Details section for the bid -->
                                        <div class="w-100 w-md-75 h-100 p-2">
                                            <!-- Title of the bid -->
                                            <h3 class="text-truncate"><a
                                                    href="{{ route('products.show', $product->id) }}">{{ $product->weight . ' ct ' . $product->name }}</a>
                                            </h3>
                                            <!-- Table for additional bid information -->
                                            <table class="w-100">
                                                <!-- Row for Auction Id -->
                                                <tr class="w-100">
                                                    <td class="w-40 nunito-regular">
                                                        {{ $product->productListing->item_type == 'auction' ? 'Auction' : 'Item' }}
                                                        Id #:</td>
                                                    <td class="w-50 nunito-regular">GM{{ $product->id }}
                                                    </td>
                                                </tr>
                                                <!-- Row for Bid Starting Date -->
                                                <tr class="w-100">
                                                    <td class="w-40 nunito-regular">Won via </td>
                                                    <td class="w-50 nunito-regular">
                                                        @if ($order?->won_via == 'auction')
                                                            Bid
                                                        @elseif ($order?->won_via == 'offer')
                                                            Make an Offer
                                                        @else
                                                            Cart
                                                        @endif
                                                    </td>
                                                </tr>
                                                <!-- Status -->
                                                <tr class="w-100">
                                                    <td class="w-40 nunito-regular">Status</td>
                                                    <td class="w-50 nunito-regular text-capitalize">
                                                        <span class="order-status"
                                                            style="background-color:@if ($order->status == 'pending') gray;
                                            @elseif($order->status == 'cancelled') red;
                                            @elseif($order->status == 'paid awaiting shippment') #105082;
                                            @elseif($order->status == 'paid & shipped') blue;
                                            @else transparent; @endif">
                                                            {{ $order->status === 'pending' ? 'Awaiting Payments' : $order->status }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <span class="vr d-none d-md-block"></span>
                            <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                            <!-- Bid amount section -->
                            <div class="w-100 w-md-20  h-100 d-flex align-items-center justify-content-center">
                                <span class="nunito">${{ $order->total }}</span>
                            </div>
                            <span class="vr d-none d-md-block"></span>
                            <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                            <!-- Action button -->
                            <div
                                class="w-100 w-md-20 h-100 d-flex align-items-center justify-content-center  position-relative">
                                <button class="anchor-button rounded-3 m-auto d-block"
                                    id="update-sales-button{{ $order->id }}"
                                    style="background: #105082;color:white;width:120px!important">Action</button>
                                <table class="rounded-2 position-absolute py-2"
                                    id="update-sales-table{{ $order->id }}"
                                    style="width: 160px;height:auto;background-color:#105082;display:none;top:40px;z-index:1">
                                    <tr>
                                        <td class="py-1"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-2 text-dark" style="background: #E1E1E1">
                                            <h2 class="nunito p-0 m-0 text-dark">Update Sales Status</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-2 text-white action-hover">
                                            <a href="{{ route('seller.sales.update.status', ['id' => $order->id, 'status' => 'pending']) }}"
                                                class="bg-transparent border-0 text-white w-100 d-block">Awaiting
                                                Payments</a>
                                        </td>
                                    </tr>
                                    @if ($order->status === 'paid & shipped')
                                    @else
                                        <tr>
                                            <td class="ps-2 text-white action-hover">
                                                <a href="{{ route('seller.sales.update.status', ['id' => $order->id, 'status' => 'paid & shipped']) }}"
                                                    class="bg-transparent border-0 text-white w-100 d-block">Paid &
                                                    Shipped</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-2 text-white action-hover">
                                                <a href="{{ route('seller.sales.update.status', ['id' => $order->id, 'status' => 'paid awaiting shippment']) }}"
                                                    class="bg-transparent border-0 text-white w-100 d-block">Paid
                                                    Awaiting
                                                    Shippment</a>
                                            </td>
                                        </tr>
                                    @endif
                                    {{-- @if (!$order->status == 'cancelled') --}}
                                    <tr>
                                        <td class="ps-2 text-white action-hover">
                                            <a href="{{ route('seller.sales.update.status', ['id' => $order->id, 'status' => 'cancelled']) }}"
                                                class="bg-transparent border-0 text-white w-100 d-block">Cancel</a>
                                        </td>
                                    </tr>
                                    {{-- @endif --}}
                                    <tr>
                                        <td class="ps-2 text-white action-hover">
                                            <button type="button" class="bg-transparent border-0 text-white"
                                                data-bs-toggle="modal"
                                                data-bs-target="#postal-address{{ $user->id }}">Shipping
                                                Address</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-2 text-white action-hover">
                                            <a href="{{ route('seller.sales.summary', $order->id) }}"
                                                class="bg-transparent border-0 text-white w-100 d-block">Sales
                                                Summary</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            @empty
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No orders found</p>
                </div>
            @endforelse
        </div>



    </div>
@endsection

@section('modals')
    <!--  modals section -->
    @foreach ($orders as $userId => $userOrders)
        @foreach ($userOrders as $order)
            <!-- modal for postal address starts here -->
            <div class="modal fade" id="postal-address{{ $userId }}" tabindex="-1" aria-hidden="true"
                data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 99999">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                    <div class="modal-content p-3  position-relative">
                        <div class="modal-header w-100 position-relative" style="border: 0;--bs-modal-header-padding:0">
                            <h2 class="text-dark">Postal Address</h2>
                            <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                                aria-label="Close" style="right: 0!important;margin:0"></button>
                        </div>
                        <div class="modal-body p-0 m-0 w-100 d-flex align-items-start flex-column flex-md-row  m-0"
                            style="border: none">
                            <div class="postal-address-left w-50  mt-3">
                                <h4 class="nunito" style="font-size: 17px">{{ $order->shipping_name }}</h4>
                                <p class="w-80" style="text-align: justify">
                                    {{ $order->shipping_address . ', ' . $order->shipping_city . ', ' . $order->shipping_state . ', ' . $order->shipping_country . ', ' . $order->shipping_postal_code }}
                                </p>
                            </div>
                            <div class="postal-address-right w-50  mt-3">
                                <div>
                                    <h4 class="nunito" style="font-size: 17px">email</h4>
                                    <p class="">{{ $order->shipping_email }}</p>
                                </div>
                                <div>
                                    <h4 class="nunito" style="font-size: 17px">Phone</h4>
                                    <p>{{ $order->shipping_phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="position-absolute" style="width: 120px;height:120px;right:-20px;bottom:-25px">
                            <img src="{{ asset('assets/buyer-assets/rock.png') }}" alt=""
                                style="height: 100%;width:100%;max-width:100%;object-fit:cover;transform:rotate(270deg)">
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal for postal address ends here -->

            <div id="orderTrackingModal{{ $order->id }}" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Tracking Number</h5>
                            <button type="button" class="close p-2 bg-transparent border-0" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('seller.sales.update.shipped', $order->id) }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-5 mt-2 w-75 mx-auto">
                                    <input type="hidden" name="status" id="status" value="paid & shipped">
                                    <label for="tracking_number" class="my-4">
                                        Tracking Number
                                    </label>
                                    <input type="text" name="tracking_number" id="tracking_number"
                                        class="form-control" required>
                                </div>

                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endsection
@section('js')
    <script>
        $(function() {
            // Reusable function to handle toggle behavior
            function toggleElement(selector, list) {
                const elementSelector = $(selector);
                const elementList = $(list);

                elementSelector.on("click", function(e) {
                    elementList.toggle("fade", {
                        direction: "horizontal"
                    }, 200);

                    // Prevent the click event from propagating to the document body
                    e.stopPropagation();
                });

                // Close the element when clicking outside
                $(document).on("click", function(e) {
                    if (!elementList.is(e.target) && !elementSelector.is(e.target) && elementList.has(e
                            .target).length === 0) {
                        elementList.hide("fade", {
                            direction: "horizontal"
                        }, 200);
                    }
                });
            }

            // Call the function for each element
            @foreach ($orders as $userId => $userOrders)
                toggleElement("#action-button{{ $userId }}", "#action-table{{ $userId }}");
                @foreach ($userOrders as $order)
                    toggleElement("#update-sales-button{{ $order->id }}",
                        "#update-sales-table{{ $order->id }}");
                @endforeach
            @endforeach

        });
    </script>
@endsection
