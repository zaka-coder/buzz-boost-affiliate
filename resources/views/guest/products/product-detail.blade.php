@extends('layouts.guest', ['title' => 'Item Details'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/wishlist.css') }}">
    {{-- link to splide css library --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <style>
        .w-90 {
            width: 90%;
        }
    </style>
@endsection
@section('content')
    <div class="row h-auto">
        <div class="col-12">
            <h2 class="text-capitalize">{{ $product->category->name }} –
                {{ $product->weight }} ct
                {{ $product->name }}
            </h2>
        </div>
    </div>
    <div class="row">
        <!-- Container for a responsive layout with two sections-->
        <div class="col-md-7 d-flex flex-column gap-4 h-100" style="background-color: transparent;">
            <!-- Left column for carousel -->
            <div class="w-100">
                {{-- Main Slider Carousel --}}
                <div class="w-100 w-md-100  splide rounded-3" id="main-slider" style="background-color: #FFF">
                    <div class="w-100  splide__track">
                        <ul class="w-100  splide__list">
                            <!-- Main slider content goes here -->
                            <li class="splide__slide w-100 rounded-3 skeleton">
                                <img src="{{ asset($product->image ?? '') }}" alt="" width="100%" height="100%"
                                    class="rounded-3" style="object-fit: cover">
                            </li>
                            @foreach ($product->gallery as $gallery_image)
                                <li class="splide__slide w-100  rounded-3">
                                    <img src="{{ asset($gallery_image->image ?? '') }}" alt="" width="100%"
                                        height="100%" class="rounded-3" style="object-fit: cover">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Thumbnail Slider Carousel -->
                <div class="splide w-100" id="thumbnail-slider">
                    <div class="splide__track  w-80 pt-2 m-auto">
                        <ul class="splide__list w-100">
                            <!-- Thumbnail slider content goes here -->
                            <li class="splide__slide w-25 rounded-3">
                                <img src="{{ asset($product->image ?? '') }}" alt="" width="100px" height="80%"
                                    class="img-fluid">
                            </li>
                            @foreach ($product->gallery as $gallery_image)
                                <li class="splide__slide w-25 rounded-3">
                                    <img src="{{ asset($gallery_image->image ?? '') }}" alt="" width="100px"
                                        height="80%" class="img-fluid">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- End of Carousels --}}
            </div>
            <!-- Left column item info and description -->
            <div class="w-100 h-auto">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="pb-2">Item Information</h2>
                    <h2 class="pb-2">
                        ${{ $product->productListing->item_type == 'auction' ? $product->productPricing->starting_price : $product->productPricing->buy_it_now_price }}
                    </h2>
                </div>
                <!-- Item details table -->
                <div class="w-100 py-3">
                    <table class="w-100 table table-bordered">
                        <!-- Table rows for item details -->
                        <tr class="w-100">
                            <td class="w-65 nunito bg-transparent">Dimensions:</td>
                            <td class="w-35 nunito-regular bg-transparent">
                                {{ $product->dim_length }}*{{ $product->dim_width }}*{{ $product->dim_depth }}mm</td>
                        </tr>
                        <tr class="w-100">
                            <td class="w-65 nunito bg-transparent">Weight</td>
                            <td class="w-35 nunito-regular bg-transparent">{{ number_format($product->weight, 2) }} carats
                            </td>
                        </tr>
                        <tr class="w-100">
                            <td class="w-65 nunito bg-transparent">Listing Type</td>
                            <td class="w-35 nunito-regular bg-transparent">{{ $product->productListing->listing_type }}
                            </td>
                        </tr>
                        <tr class="w-100">
                            <td class="w-65 nunito bg-transparent">Treatment</td>
                            <td class="w-35 nunito-regular bg-transparent">{{ $product->treatment }}</td>
                        </tr>
                    </table>
                </div>
                <h2 class="my-4">Item Description</h2>
                <div class="w-100 border border-1 p-2">
                    <p class="nunito-regular" style="text-align:justify!important;font-size:16px">
                        <!-- Placeholder text for the description -->
                        {!! $product->description !!}
                    </p>
                </div>
            </div>
            <!-- Left column for shipping  -->
            {{-- <div class="w-100 h-auto">
                <h2 class="pb-2">Shipping</h2>
                <table class="w-100 table table-bordered">
                    <tr>
                        <td class="nunito">Shipping Providers</td>
                        <td class="nunito ">Shipping to Thiland</td>
                        <td class="nunito">Shipping to rest of the world</td>
                    </tr>
                    <!-- standard shipping starts here -->
                    <tr class="table-active">
                        <td class="nunito-regular all-side-borders">Standard Shipping - Tracked</td>
                        <td class="nunito-regular all-side-borders">$10.00 / 7 days</td>
                        <td class="nunito-regular">$18.00 / 21 days</td>
                    </tr>
                    <tr>
                        <td class="nunito-regular my-0 py-0 hiding-border" colspan="3">Thiland</td>
                    </tr>
                    <tr class="">
                        <td class="nunito-regular" colspan="3">
                            <div class="w-100 bg-warning bg-opacity-25 px-3">
                                Standard Shipping - Tracked is discounted to $20.00 on orders with 2 or more items</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nunito-regular my-0 py-0 hiding-border" colspan="3">Rest of world</td>
                    </tr>
                    <tr class="">
                        <td class="nunito-regular" colspan="3">
                            <div class="w-100 bg-warning bg-opacity-25 px-3">
                                Standard Shipping - Tracked is discounted to $20.00 on orders with 2 or more items</div>
                        </td>
                    </tr>
                    <!-- fedex shippping starts here -->
                    <tr class="table-active">
                        <td class="nunito-regular all-side-borders">Fedex</td>
                        <td class="nunito-regular all-side-borders">$20.00 / 5 days</td>
                        <td class="nunito-regular">$18.00 / 21 days</td>
                    </tr>
                    <tr>
                        <td class="nunito-regular my-0 py-0 hiding-border" colspan="3">Thiland</td>
                    </tr>
                    <tr class="">
                        <td class="nunito-regular" colspan="3">
                            <div class="w-100 bg-warning bg-opacity-25 px-3">
                                Standard Shipping - Tracked is discounted to $20.00 on orders with 2 or more items</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nunito-regular my-0 py-0 hiding-border" colspan="3">Rest of world</td>
                    </tr>
                    <tr class="">
                        <td class="nunito-regular " colspan="3">
                            <div class="w-100 bg-warning bg-opacity-25 px-3">
                                Standard Shipping - Tracked is discounted to $20.00 on orders with 2 or more items</div>
                        </td>
                    </tr>
                    <!-- Registered shippping starts here -->
                    <tr class="table-active">
                        <td class="nunito-regular all-side-borders">Registered shipping</td>
                        <td class="nunito-regular all-side-borders">$15.00 / 5 days</td>
                        <td class="nunito-regular">$18.00 / 21 days</td>
                    </tr>
                    <tr>
                        <td class="nunito-regular my-0 py-0 hiding-border" colspan="3">Thiland</td>
                    </tr>
                    <tr class="">
                        <td class="nunito-regular" colspan="3">
                            <div class="w-100 bg-warning bg-opacity-25 px-3">
                                Standard Shipping - Tracked is discounted to $20.00 on orders with 2 or more items</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nunito-regular my-0 py-0 hiding-border" colspan="3">Rest of world</td>
                    </tr>
                    <tr class="">
                        <td class="nunito-regular " colspan="3">
                            <div class="w-100 bg-warning bg-opacity-25 px-3">
                                Standard Shipping - Tracked is discounted to $20.00 on orders with 2 or more items</div>
                        </td>
                    </tr>
                </table>
            </div> --}}
            <!-- Left column for feedback  -->
            <div class="w-100 h-auto pb-5">
                <h2 class="pb-2">Recent Feedbacks</h2>
                @forelse ($feedbacks as $feedback)
                    <div class="p-2 bg-white" style="border-bottom: 1px solid rgba(128, 128, 128, 0.384)">
                        <div class="d-flex align-items-center justify-content-between my-3">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <div class="nunito pe-3">
                                    {{ $feedback->user->name }}
                                </div>
                                @if ($feedback->nature == 'positive')
                                    <div class="d-flex align-items-center gap-1 rounded-pill px-3 nunito-regular shadow-sm"
                                        style="background: #DCFCE7;color:rgba(0, 128, 0, 0.658)"><i
                                            class="bi bi-hand-thumbs-up"></i> Positive</div>
                                @elseif ($feedback->nature == 'negative')
                                    <div class="d-flex align-items-center gap-1 rounded-pill px-3 nunito-regular shadow-sm"
                                        style="background: #fcdcdc81;color:red"><i class="bi bi-hand-thumbs-down"></i>
                                        Negative</div>
                                @elseif ($feedback->nature == 'neutral')
                                    <div class="d-flex align-items-center gap-1 rounded-pill px-3 nunito-regular shadow-sm"
                                        style="background: #fcdcdc81;color:rgba(196, 196, 49, 0.733)"><i
                                            class="bi bi-dash"></i> Neutral</div>
                                @endif
                            </div>
                            <div class="nunito-regular">
                                {{ $feedback->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-start gap-2 rounded-2 border border-1 p-1"
                            style="background: #F8F8F9">
                            <div class="" style="width: 50px;height:50px">
                                <img src="{{ asset($feedback->product->image) }}" alt="" width="100%"
                                    height="100%" class="" style="max-width: 100%;object-fit:cover">
                            </div>
                            <div class="nunito-regular text-uppercase" style="font-size: 12px">
                                {{ $feedback->product->weight . ' ct ' . $feedback->product->name }}
                            </div>
                        </div>
                        <div class="w-95 m-auto" style="text-align: justify">
                            {{ $feedback->feedback }}
                        </div>
                    </div>
                @empty
                    <div class="w-100 p-3 h-20 d-flex align-items-center justify-content-center">
                        <p>No Feedbacks</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="col-md-5 d-flex flex-column  h-100 gap-4">
            @if ($product->is_sold)
                <div class="alert alert-danger w-90 m-auto text-center my-2" role="alert">Item Sold</div>
            @elseif ($product->productListing->closed)
                <div class="alert alert-danger w-90 m-auto text-center my-2" role="alert">Closed</div>
            @else
                <!-- Right Section: buttons -->
                <div class="w-100 text-center d-flex align-items-center justify-content-center gap-2 ms-auto">
                    <button type="button" class="anchor-button rounded-2 @if ($product->productListing->item_type != 'auction') w-90 @endif"
                        style="background-color: #105082;color:white;"
                        onclick="document.getElementById('wishlistForm').submit()">
                        {{-- <i class="bi bi-hammer"></i> --}}
                        Add to Wishlist
                    </button>
                    <form id="wishlistForm" action="{{ route('buyer.wishlist.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    </form>
                    @if ($product->productListing->item_type == 'auction')
                        <button class="anchor-button rounded-2" data-bs-toggle="modal"
                            data-bs-target="#bid-popup{{ $product->id }}" style="background-color: #105082;color:white">
                            {{-- <i class="bi bi-hammer"></i> --}}
                            Bid Now
                        </button>
                        {{-- @else
                    <button class="anchor-button rounded-2" data-bs-toggle="modal" data-bs-target="#make-offer-popup{{ $product->id }}"
                        style="background-color: #105082;color:white">
                        Make Offer
                    </button> --}}
                    @endif
                </div>
            @endif

            <!-- Right Section: Bids History -->
            @if ($product->productListing->item_type == 'auction')
                <div class="w-100">
                    <div class="w-90 m-auto bids pb-2"
                        style="background-color: #FFF; overflow:auto; box-shadow: 2px 7px 15px rgb(208, 207, 207);">
                        <!-- Bids History Header -->
                        <div class="bids-top w-100  d-flex align-items-center justify-content-center p-2"
                            style="background: #105082 0% 0% no-repeat padding-box;">
                            {{-- <h3 class="text-white">Bids History (15)</h3> --}}
                            <h3 class="text-white">
                                {{ $product->productListing->item_type == 'auction' ? 'Bids History' : 'Offers History' }}
                                {{ $product->productListing->item_type == 'auction' ? '(' . $product->bids->count() . ')' : '(' . $product->offers->count() . ')' }}
                            </h3>
                        </div>

                        @if ($product->productListing->item_type == 'auction')
                            @php
                                $productBids = $product->bids->sortByDesc('price');
                            @endphp
                            <!-- Bids History Items -->
                            @forelse ($productBids as $bid)
                                <div class="bids-bottom w-100 h-auto my-2">
                                    <div class="w-100 h-20 d-flex align-items-center">
                                        <!-- Bidder Information -->
                                        <div
                                            class="w-60 h-100 d-flex flex-column align-items-start justify-content-center ps-3">
                                            <p class="p-0 m-0">{{ $bid->user->name }}</p>
                                            <small class="text-muted">{{ $bid->created_at->diffForHumans() }}</small>
                                        </div>
                                        <!-- Bid Amount -->
                                        <div class="w-40 h-100 d-flex align-items-center justify-content-center">
                                            <h2 class="pt-2">${{ number_format($bid->price, 2) }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <hr class="pb-2" style="width: 90%;margin:auto;">
                            @empty
                                <div class="w-100 p-3 d-flex align-items-center justify-content-center">
                                    <p class="">No History</p>
                                </div>
                            @endforelse
                        @else
                            <!-- Offers History Items -->
                            @forelse ($product->offers as $offer)
                                <div class="bids-bottom w-100 h-auto my-2">
                                    <div class="w-100 h-20 d-flex align-items-center">
                                        <!-- User Information -->
                                        <div
                                            class="w-60 h-100 d-flex flex-column align-items-start justify-content-center ps-3">
                                            <p class="p-0 m-0">{{ $offer->user->name }}</p>
                                            <small class="text-muted">{{ $offer->created_at->diffForHumans() }}</small>
                                        </div>
                                        <!-- Offer Amount -->
                                        <div class="w-40 h-100 d-flex align-items-center justify-content-center">
                                            <h2 class="pt-2">${{ number_format($offer->offer_value, 2) }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <hr class="p-0" style="width: 90%;margin:auto;">
                            @empty
                                <div class="w-100 p-3 h-20 d-flex align-items-center justify-content-center">
                                    <p>No History</p>
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>
            @endif

            <!-- Right Section: Audit information -->
            <div class="w-100 h-md-100 h-100">
                <!-- Sub-container for audit information with white background -->
                <div class="w-90 h-100 m-auto bids"
                    style="background-color: #FFF;box-shadow: 2px 5px 15px rgb(208, 207, 207);">
                    <!-- Top section of the audit container with a blue background -->
                    <div class="bids-top w-100 p-2 d-flex align-items-center justify-content-center"
                        style="background: #105082 0% 0% no-repeat padding-box;">
                        <h3 class="text-white cards-heading pt-2">Audit</h3>
                    </div>
                    <!-- Body section of the audit container with text content -->
                    <div class="bids-body h-auto  w-100 p-3">
                        <p class="nunito-regular" style="text-align: justify">
                            @if ($product->audit)
                                {{ $product->audit->comment ?? 'An audit request has already been submitted. Please wait for the response. Thank you' }}
                            @else
                                The Gems Harbor Audit program allows our
                                members to request an audit on any product, which is completed by an independent Gemologist
                                who
                                assesses the accuracy of the item description and pictures.
                            @endif
                        </p>
                    </div>
                    <!-- Footer section of the audit container with verification details -->
                    <div class="bids-footer w-100  h-auto h-md-auto  px-3 pb-4">
                        {{-- Request an audit --}}
                        @if ($product->audit)
                            @if ($product->audit->status == 'completed')
                                @if ($product->audit->product_desc == 'verified')
                                    <!-- Verification section for description with checkmark and "Verified" label -->
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2 border-bottom-0">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill" style="color: #4AAD4A;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Description</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: #4AAD4A;">
                                                Accurate</p>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2 border-bottom-0">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-x-circle-fill" style="color: red;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Description</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: red;">
                                                Inaccurate</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($product->audit->product_image == 'verified')
                                    <!-- Verification section for picture with checkmark and "Verified" label -->
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill" style="color: #4AAD4A;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Picture</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: #4AAD4A;">
                                                Accurate</p>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-x-circle-fill" style="color: red;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Picture</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: red;">
                                                Inaccurate</p>
                                        </div>
                                    </div>
                                @endif
                                {{-- @if ($product->audit->cert_image)
                                    <!-- Certificate section with a link to view the certificate -->
                                    <div class="certificate d-flex align-items-center justify-content-start border border-2 border-dark p-2 mt-2"
                                        style="background-color: rgba(74, 173, 74, 0.3)">
                                        <a href="{{ url($product->audit->cert_image) }}" target="_blank">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-eye-fill" style="color: #4AAD4A;font-size: 20px"></i>
                                                <span class="ps-2 nunito-regular">View Certificate</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif  --}}
                            @elseif ($product->audit->status == 'pending')
                                @if ($product->audit->product_desc == 'verified')
                                    <!-- Verification section for description with checkmark and "Verified" label -->
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2 border-bottom-0">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill" style="color: #4AAD4A;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Description</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: #4AAD4A;">
                                                Accurate</p>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2 border-bottom-0">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-hourglass-bottom" style="color: orange;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Description</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: orange;">
                                                In Progress</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($product->audit->product_image == 'verified')
                                    <!-- Verification section for picture with checkmark and "Verified" label -->
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill" style="color: #4AAD4A;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Picture</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: #4AAD4A;">
                                                Accurate</p>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="d-flex align-items-center justify-content-between border border-2 border-dark p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-hourglass-bottom" style="color: orange;font-size: 20px"></i>
                                            <span class="ps-2 nunito-regular">Picture</span>
                                        </div>
                                        <div>
                                            <p class="px-2 m-0 text-white rounded-3 nunito-regular"
                                                style="background-color: orange;">
                                                In Progress</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            @auth
                                @if (auth()->user()->hasRole('buyer'))
                                    @if (!$product->is_sold && !$product->productListing->closed)
                                        <a href="javascript:void(0)" onclick="requestAudit({{ $product->id }})"
                                            class="">
                                            <div class="d-flex align-items-center justify-content-between p-2"
                                                style="width:fit-content;border:1px solid rgba(128, 128, 128, 0.473);">
                                                <div class="d-flex align-items-center">
                                                    <i id="check_icon" class="bi bi-check-circle-fill d-none"
                                                        style="color: #4AAD4A;font-size: 20px"></i>
                                                    <span id="audit_request_btn" class="ps-2 text-dark"
                                                        style="font-size: 17px;font-weight:500">Request Audit</span>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
            <!-- Right Section: store details -->
            <div class="w-100 h-md-100 h-auto ">
                <!-- Store details container -->
                <div class="w-90 h-auto m-auto bids pb-3"
                    style="background-color: #FFF;overflow:auto;box-shadow: 2px 5px 15px rgb(208, 207, 207);">

                    <!-- Store details header -->
                    <div class="bids-top w-100 p-2 d-flex align-items-center justify-content-center"
                        style="background: #105082 0% 0% no-repeat padding-box;">
                        <h3 class="text-white cards-heading pt-2">Store Details</h3>
                    </div>

                    <!-- Store details body -->
                    <div class="bids-body  h-auto w-100">
                        <!-- Store image -->
                        <div class="w-100 h-100 store-image p-2">
                            <div class="d-flex">
                                <div class="" style="height: 100px;width:100px">
                                    <img class="d-block m-auto"
                                        src="{{ asset($product->store->image ?? 'assets/buyer-assets/zaka.jpeg') }}"
                                        alt="store's image" width="100%" height="100%"
                                        style="max-width: 100%;object-fit: cover;border-radius:50%;border:1px solid black;">
                                </div>
                                <div class="d-flex  flex-column align-items-center justify-content-center gap-2"
                                    style="width: calc(100% - 100px)">
                                    <div class="w-100">
                                        <h2 class="p-0 m-0 text-center w-100">{{ $product->store->name }}</h2>
                                        <small class="m-0 p-0 m-auto d-block"
                                            style="width:fit-content">{{ $product->store->country }}</small>
                                    </div>
                                    <div class="rounded-2  text-white  py-1 d-flex align-items-center gap-1 justify-content-center m-auto"
                                        style="background-color:#4AAD4A;width:120px"><i
                                            class="bi bi-patch-check"></i>Verified</div>
                                    {{-- <div class="w-100 mt-2" style="height:50px">
                                        <table class="w-85  ms-auto h-100">
                                            <tr class="w-100 ">
                                                <td><span class="w-50 nunito">Feedback</span></td>
                                                <td><span class="w-50 nunito">Ratings</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="nunito-regular">{{ $product->feedbacks->count() }}</span></td>
                                                <td><span class="nunito-regular text-center">{{ $product->store->ratings ?? "0" }}%</span></td>
                                            </tr>
                                        </table>
                                    </div> --}}
                                    <div class="w-100   d-flex">
                                        <div class="w-50 text-center">Items</div>
                                        <div class="w-50"><span class="text-white ps-2 rounded-3 d-block m-auto"
                                                style="width:35px;height:25px;background-color:#105082">{{ $product->store->products->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Store details footer -->
                    @php
                        use Illuminate\Support\Str;
                    @endphp
                    <div class="w-100 h-auto pt-4 px-2">
                        <span class="w-90 d-block m-auto">
                            {{-- <p class="p-0 m-0" style="font-size: 14px;text-align:justify">{{ str::limit($product->description, 85) }}</p> --}}
                            <!--****** when making it dynamic please allow only 85 characters here thanks****** -->
                            <p class="p-0 m-0" style="font-size: 14px;text-align:justify">
                                {{ $product->store->description }}</p>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Right Section: shipping information -->
            <div class="w-100 h-md-100 h-100">
                <div class="w-90 h-100 m-auto bids"
                    style="background-color: #FFF;box-shadow: 2px 5px 15px rgb(208, 207, 207);">
                    <div class="bids-top w-100 p-2 d-flex align-items-center justify-content-center"
                        style="background: #105082 0% 0% no-repeat padding-box;">
                        <h3 class="text-white cards-heading pt-2">Shipping Details</h3>
                    </div>
                    <div class="bids-body h-auto  w-100 p-3">
                        <table class="w-100 m-auto">
                            <tr class="w-100">
                                <td class="nunito d-flex align-items-start w-30" style="font-size:16px">Shipping</td>
                                <td class="nunito-regular text-start w-70 ps-2" style="font-size:14px">
                                    @foreach ($product->store->shippings as $shipping)
                                        ${{ $shipping->domestic_shipping_fee_per_item . ' - ' . $shipping->domestic_transit_time }}
                                        via <span class="text-uppercase">{{ $shipping->shipping_provider }}</span>
                                    @endforeach
                                    {{-- $13.75 - 7 days via Standard Shipping --}}
                                </td>
                            </tr>
                            <tr>
                                <td class="nunito " style="font-size:16px">Ships from</td>
                                <td class="nunito-regular text-start ps-2" style="font-size:14px">
                                    {{ $product->store->country }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Right Section: payment information -->
            <div class="w-100 h-md-100 h-100">
                <div class="w-90 h-100 m-auto bids"
                    style="background-color: #FFF;box-shadow: 2px 5px 15px rgb(208, 207, 207);">
                    <div class="bids-top w-100 p-2 d-flex align-items-center justify-content-center"
                        style="background: #105082 0% 0% no-repeat padding-box;">
                        <h3 class="text-white cards-heading pt-2">Payment Options</h3>
                    </div>
                    <div class="bids-body h-auto w-100 p-3 d-flex align-items-center justify-content-evenly flex-wrap">
                        @if ($product->store->paymentMethods)
                            @foreach ($product->store->paymentMethods as $paymentMethod)
                                @if ($paymentMethod->name == 'paypal')
                                    <div class="" style="height: 50px;width:90px">
                                        <img src="{{ asset('assets/buyer-assets/paypal.png') }}" alt=""
                                            width="100%" height="100%" style="max-width: 100%;object-fit:contain">
                                    </div>
                                @endif
                                @if ($paymentMethod->name == 'stripe')
                                    <div class="" style="height: 50px;width:90px">
                                        <img src="{{ asset('assets/buyer-assets/visa.png') }}" alt=""
                                            width="100%" height="100%" style="max-width: 100%;object-fit:contain">
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center w-100">No payment options available.</div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- right section with product recommendations -->
            <div class="w-100 h-100">
                <h2 class="text-center">You may also like</h2>
                <!-- Container for product cards -->
                <div class="w-100 py-3 h-auto">
                    {{-- you may also like cards html --}}
                    <div class="w-100 h-auto d-flex align-items-center justify-content-between flex-wrap m-auto h-auto">
                        <div class="w-100 h-auto splide" id="secondary-slider">
                            <div class="splide__track w-100 h-auto">
                                <ul class="splide__list w-100 h-auto">
                                    <!-- Loop for creating product cards -->
                                    @foreach ($related_products as $related_product)
                                        <li class="splide__slide w-100 h-auto">
                                            <div class="card">
                                                <div class="card-head p-2">
                                                    <section id="splideDetail{{ $related_product->id }}"
                                                        class="splide w-100 h-100 " aria-label="">
                                                        <div class="splide__track w-100 h-100">
                                                            <ul class="splide__list w-100 h-100">
                                                                <li
                                                                    class="splide__slide w-100 h-100 position-relative skeleton">
                                                                    <img src="{{ asset($related_product->image ?? 'assets/buyer-assets/coming-soon.avif') }}"
                                                                        alt=""
                                                                        style="width:100%;height:100%!important;max-width:100%;object-fit:cover!important"
                                                                        class="rounded-3">
                                                                    <span class="featured-tag"
                                                                        style="background-color:
                                                                        @if ($related_product->productListing->listing_type == 'Showcase') #F56565;
                                                                        @elseif ($related_product->productListing->listing_type == 'Premium') #105082;
                                                                        @elseif ($related_product->productListing->listing_type == 'Standard') #EA008B;
                                                                        @elseif ($related_product->productListing->listing_type == 'Boost') #4AAE43;
                                                                        @else #EA008B; @endif">
                                                                        {{ $related_product->productListing->listing_type ?? '' }}
                                                                </li>
                                                                @foreach ($related_product->gallery as $gallery)
                                                                    <li
                                                                        class="splide__slide w-100 h-100 position-relative">
                                                                        <img src="{{ asset($gallery->image ?? 'assets/buyer-assets/coming-soon.avif') }}"
                                                                            alt=""
                                                                            style="width:100%;height:100%;max-width:100%;object-fit:cover"
                                                                            class="rounded-3">
                                                                        <span class="featured-tag"
                                                                            style="background-color:
                                                                            @if ($related_product->productListing->listing_type == 'Showcase') #F56565;
                                                                            @elseif ($related_product->productListing->listing_type == 'Premium') #105082;
                                                                            @elseif ($related_product->productListing->listing_type == 'Standard') #EA008B;
                                                                            @elseif ($related_product->productListing->listing_type == 'Boost') #4AAE43;
                                                                            @else #EA008B; @endif">
                                                                            {{ $related_product->productListing->listing_type ?? '' }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </section>
                                                </div>
                                                <a href="{{ route('products.show', $related_product->id) }}"
                                                    class="products-card-link">
                                                    <div class="card-body p-0 m-0">
                                                        <div class="card-body-first">
                                                            <h5
                                                                class="m-0 p-2 text-center text-truncate text-capitalize skeleton-text">
                                                                {{ $related_product->weight }} ct
                                                                {{ $related_product->name }} </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="time-part pt-1">
                                                    @if ($related_product->productListing->item_type == 'auction')
                                                        @php
                                                            $countdownTime = $related_product->countdown_time;
                                                        @endphp
                                                        <p class="p-0 m-0 text-center skeleton-text"
                                                            id="footerTime{{ $related_product->id }}">
                                                            {{ $countdownTime['days'] . 'd ' . $countdownTime['hours'] . 'h ' . $countdownTime['minutes'] . 'm ' ?? 'N/A' }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="card-footer border-0 p-0 m-0 d-flex bg-white">
                                                    <div
                                                        class="p-0 m-0 footer-icons-part d-flex align-items-start  justify-content-start ps-3  gap-1">
                                                        @if ($related_product->productListing->item_type == 'auction')
                                                            <button class="m-0 p-0 border-0 bg-transparent skeleton-icon"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#bid-popup{{ $related_product->id }}"
                                                                title="Bid Now">
                                                                <i class="bi bi-hammer"></i>
                                                            </button>
                                                        @else
                                                            {{-- <i class="bi bi-bag-check"></i> --}}
                                                            <button class="m-0 p-0 border-0 bg-transparent skeleton-icon"
                                                                type="button" title="Add to cart"
                                                                onclick="addToCart({{ $related_product->id }})">
                                                                <i id="add-to-cart-icon-{{ $related_product->id }}"
                                                                    class="bi bi-bag"></i>
                                                            </button>
                                                            <button class="m-0 p-0 border-0 bg-transparent skeleton-icon"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#make-offer-popup{{ $related_product->id }}"
                                                                title="Make an offer">
                                                                <i class="bi bi-arrow-left-right"></i>
                                                            </button>
                                                        @endif
                                                        <button
                                                            class="m-0 p-0 rounded-4 border-0 bg-transparent skeleton-icon"
                                                            title="Certified">
                                                            <i class="bi bi-shield-check" title="Certified"></i>
                                                        </button>
                                                        {{-- <i class="bi bi-shield-x"></i> --}}
                                                    </div>
                                                    <div
                                                        class="footer-price-time-part d-flex align-items-start justify-content-center pt-1">
                                                        <div class="footer-price-part">
                                                            <p class="skeleton-text">
                                                                ${{ $related_product->productListing->item_type == 'auction'
                                                                    ? $related_product->productPricing->starting_price
                                                                    : $related_product->productPricing->buy_it_now_price }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

<!-- Modals section -->
@section('modals')
    <!-- Bid Modal -->
    @include('layouts.includes.bid-modal', ['product' => $product])

    <!-- Make Offer Modal -->
    @include('layouts.includes.offer-modal', ['product' => $product])

    @foreach ($related_products as $rel_product)
        <!-- Bid Modal -->
        @include('layouts.includes.bid-modal', ['product' => $rel_product])

        <!-- Make Offer Modal -->
        @include('layouts.includes.offer-modal', ['product' => $rel_product])
    @endforeach
@endsection

@section('js')
    {{-- link to splide js library --}}
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main slider
            var main = new Splide('#main-slider', {
                heightRatio: 0.5,
                pagination: false,
                rewind: true,
                cover: true,
            });

            var thumbnails = new Splide('#thumbnail-slider', {
                rewind: true,
                fixedWidth: 104,
                fixedHeight: 58,
                isNavigation: true,
                gap: 10,
                focus: 'center',
                pagination: false,
                cover: true,
                dragMinThreshold: {
                    mouse: 4,
                    touch: 10,
                },
                breakpoints: {
                    640: {
                        fixedWidth: 66,
                        fixedHeight: 38,
                    },
                }
            });

            main.sync(thumbnails);
            main.mount();
            thumbnails.mount();

            // Secondary slider
            new Splide('#secondary-slider', {
                type: 'carousel',
                fixedWidth: 200,
                fixedHeight: 100,
                gap: 10,
                rewind: true,
                pagination: false,
            }).mount();
            // for related cards
            @foreach ($related_products as $related_product)
                new Splide('#splideDetail{{ $related_product->id }}', {
                    arrows: false,
                    type: 'fade',
                    heightRatio: 0.5,
                }).mount();
            @endforeach
        });

        function requestAudit(id) {
            // Add your logic for handling the audit request here
            var url = '{{ route('buyer.items.request.audit', ':id') }}';
            console.log(url.replace(':id', id));
            // send an AJAX request to the server
            $.ajax({
                type: 'GET',
                url: url.replace(':id', id),
                success: function(response) {
                    // handle success response
                    console.log(response.message);

                    // show the check icon after the request is sent
                    $('#check_icon').removeClass('d-none');
                    $('#audit_request_btn').text('Request Sent');

                    // show toastr message
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    // handle error response
                    if (xhr.status == 403) {
                        var errorMsg = 'Unauthorized: User is not authorized to request audit.';
                        console.error(errorMsg);
                        toastr.error(errorMsg);
                    } else if (xhr.status == 401) {
                        var errorMsg = 'Unauthenticated: User is not logged in.';
                        console.error(errorMsg);
                        toastr.error(errorMsg);

                    } else {
                        var errorResponse = JSON.parse(xhr.responseText);
                        console.error(errorResponse.message);
                        toastr.error(errorResponse.message);

                    }
                }
            });

        }
    </script>
    <script>
        // function to submit the offer form
        function formsubmit(productId) {
            // function to validate the form
            function validateForm() {
                // reset error messages
                $(`#offer-value-error${productId}, #validity-error${productId}`).text('');

                // flag to track if the form is valid
                let isValid = true;

                // validate offer value
                const offerValue = $(`#offer-form${productId} [name="offer_value"]`).val();
                if (!offerValue || isNaN(offerValue) || parseFloat(offerValue) <= 0) {
                    $(`#offer-value-error${productId}`).text('Please enter a valid offer amount.');
                    isValid = false;
                }

                // validate validity
                const validity = $(`#offer-form${productId} [name="validity"]`).val();
                if (!validity) {
                    $(`#validity-error${productId}`).text('Please select a valid duration.');
                    isValid = false;
                }

                return isValid;
            }

            // function to submit the form
            function submitForm() {
                // AJAX request to submit the form data
                $.ajax({
                    type: 'POST',
                    url: $(`#offer-form${productId}`).attr('action'),
                    data: $(`#offer-form${productId}`).serialize(),
                    beforeSend: function() {
                        $('.loaderSpin').show();
                    },
                    complete: function() {
                        $('.loaderSpin').hide();
                    },
                    success: function(response) {
                        // reload the page
                        // window.location.reload();
                        if (response.success) {
                            // handle success response
                            $('#offer-success-alert' + productId).html('');
                            $('#offer-success-alert' + productId).html(
                                `<small class="modal-text">Offer submitted successfully!</small>`);
                            toastr.success('Offer submitted successfully!'); // display success message
                            $("#offer-form" + productId)[0].reset(); // reset the form
                            getOfferHistory(productId); // update the offer history
                        } else {
                            // handle error response
                            toastr.error(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        // Handle Unauthorized error
                        if (xhr.status == 403) {
                            var errorMsg = 'Unauthorized: Seller is not authorized to make an offer.';
                            console.error(errorMsg);
                            toastr.error(errorMsg);
                        } else {
                            try {
                                // Handle validation errors
                                var errorResponse = JSON.parse(xhr.responseText);
                                console.error(errorResponse.message);

                                // Update the content of the corresponding divs with error messages
                                $("#offer-value-error" + productId).text(errorResponse.errors.offer_value);
                                $("#validity-error" + productId).text(errorResponse.errors.validity);
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                            }
                        }
                    }

                });
            }

            // update the offer history using AJAX
            function getOfferHistory(productId) {
                $url = `{{ url('/buyer/item/${productId}/offers') }}`;
                // AJAX request to get the offer history
                $.ajax({
                    type: 'GET',
                    url: $url,
                    success: function(response) {
                        // clear the offer history
                        $(`#offer-history${productId}`).html('');

                        // update the offer history heading
                        if (response.length == 0) {
                            $(`#offer-history${productId}`).html(`
                                        <div class="w-100 text-center">
                                            <p>No history</p>
                                        </div>`);
                        } else {
                            $(`#offer-history${productId}`).html(`
                            <div class="bid-history-heading w-100 px-md-3">
                                        <p class="m-0 p-0"><b>Offer History
                                                (${response.length})
                                            </b></p>
                                    </div>`);
                        }

                        // update the offer history
                        response.forEach(offer => {
                            $(`#offer-history${productId}`).append(`
                                        <div class="bid-history-details w-100 ps-md-3 d-flex align-items-center">
                                            <div class="w-75 h-100">
                                                <p class="text-primary my-2">${offer.userName}</p>
                                                <p style="line-height: 0">${offer.created}</p>
                                            </div>
                                            <div class="w-25">
                                                <b> ${offer.offer_value}</b>
                                            </div>
                                        </div>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            // validate the form before submitting
            if (validateForm()) {
                // if the form is valid, submit the form
                submitForm();
            }
        }

        // function to submit the bid form
        function bidformsubmit(productId) {
            // function to validate the form
            function validateBidForm() {
                // reset error messages
                $(`#bid-value-error${productId}`).text('');

                // flag to track if the form is valid
                let isValid = true;

                // validate bid value
                const bidValue = $(`#bid-form${productId} [name="price"]`).val();
                if (!bidValue || isNaN(bidValue) || parseFloat(bidValue) <= 0) {
                    $(`#bid-value-error${productId}`).text('Please enter a valid amount.');
                    isValid = false;
                }

                return isValid;
            }

            // function to submit the form
            function submitBidForm() {
                // AJAX request to submit the form data
                $('.loaderSpinBid').show();
                $.ajax({
                    type: 'POST',
                    url: $(`#bid-form${productId}`).attr('action'),
                    data: $(`#bid-form${productId}`).serialize(),
                    success: function(response) {
                        // reload the page
                        // window.location.reload();
                        if (response.success) {
                            // handle success response
                            $('#bid-success-alert' + productId).html('');
                            $('#bid-success-alert' + productId).html(
                                `<small class="modal-text">${response.message}</small>`);
                            toastr.success(response.message); // display success message
                            $("#bid-form" + productId)[0].reset(); // reset the form
                            getBidHistory(productId); // update the bid history
                        } else {
                            // handle error response
                            toastr.error(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        // Handle Unauthorized error
                        if (xhr.status == 403) {
                            var errorMsg = 'Unauthorized: Seller is not authorized to make an offer.';
                            console.error(errorMsg);
                            toastr.error(errorMsg);
                        } else {
                            try {
                                // Handle validation errors
                                var errorResponse = JSON.parse(xhr.responseText);
                                console.error(errorResponse.message);

                                // Update the content of the corresponding divs with error messages
                                $("#bid-value-error" + productId).text(errorResponse.errors.price);
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                            }
                        }

                    },
                    complete: function() {
                        $('.loaderSpinBid').hide();
                    },

                });
            }

            // update the bid history using AJAX
            function getBidHistory(productId) {
                $url = `{{ url('/buyer/item/${productId}/bids') }}`;
                // AJAX request to get the offer history
                $.ajax({
                    type: 'GET',
                    url: $url,
                    success: function(response) {
                        // update the bid count
                        $('#bid-count' + productId).text(response.length);
                        // clear the offer history
                        $(`#bid-history${productId}`).html('');

                        // update the offer history heading
                        if (response.length == 0) {
                            $(`#bid-history${productId}`).html(`
                                        <div class="w-100 text-center">
                                            <p>No history</p>
                                        </div>`);
                        } else {
                            $(`#bid-history${productId}`).html(`
                            <div class="bid-history-heading w-100 px-md-3">
                                        <p class="m-0 p-0"><b>Bids History
                                                (${response.length})
                                            </b></p>
                                    </div>`);
                        }

                        // update the offer history
                        response.forEach(bid => {
                            $(`#bid-history${productId}`).append(`
                                        <div class="bid-history-details w-100 ps-md-3 d-flex align-items-center">
                                            <div class="w-75 h-100">
                                                <p class="text-primary my-2">${bid.userName}</p>
                                                <p style="line-height: 0">${bid.created}</p>
                                            </div>
                                            <div class="w-25">
                                                <b> ${bid.bid_value}</b>
                                            </div>
                                        </div>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            // validate the form before submitting
            if (validateBidForm()) {
                // if the form is valid, submit the form
                submitBidForm();
            }
        }
    </script>
@endsection
