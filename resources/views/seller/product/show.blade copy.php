@extends('layouts.seller', ['title' => 'Product Details'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/wishlist.css') }}">
    {{-- link to splide css library --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
@endsection
@section('content')
    <h2 class="h-lg-5 h-auto text-truncate">{{ $product->category->name }} – {{ $product->weight }} ct {{ $product->name }}
    </h2>
    <div class="w-100 h-90" style="overflow: auto">
        {{-- this is the first section --}}
        <!-- Container for a responsive layout with two sections: Carousel and Bids History -->
        <div class="w-100 h-100 d-flex flex-md-row flex-column align-items-center"
            style="background-color: transparent; overflow: auto">

            <!-- Left Section: Carousel -->
            <div class="w-100 w-md-60 h-md-100 h-100" style="overflow: auto">

                {{-- Main Slider Carousel --}}
                <div class="w-100 w-md-100 h-md-80 h-100 splide rounded-3" id="main-slider" style="background-color: #FFF">
                    <div class="w-100 h-100 splide__track">
                        <ul class="w-100 h-100 splide__list">
                            <!-- Main slider content goes here -->
                            <li class="splide__slide w-100 h-100 rounded-3">
                                <img src="{{ asset($product->image ?? '') }}" alt="" width="100%" height="100%"
                                    class="rounded-3" style="object-fit: cover">
                            </li>
                            @foreach ($product->gallery as $gallery_image)
                                <li class="splide__slide w-100 h-100 rounded-3">
                                    <img src="{{ asset($gallery_image->image ?? '') }}" alt="" width="100%"
                                        height="100%" class="rounded-3" style="object-fit: cover">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Thumbnail Slider Carousel -->
                <div class="splide w-100 h-md-20 h-20" id="thumbnail-slider">
                    <div class="splide__track h-100 h-md-90 w-80 pt-2 m-auto">
                        <ul class="splide__list h-100 w-100">
                            <!-- Thumbnail slider content goes here -->
                            <li class="splide__slide h-100 w-25 rounded-3">
                                <img src="{{ asset($product->image ?? '') }}" alt="" width="100px" height="80%"
                                    class="img-fluid">
                            </li>
                            @foreach ($product->gallery as $gallery_image)
                                <li class="splide__slide h-100 w-25 rounded-3">
                                    <img src="{{ asset($gallery_image->image ?? '') }}" alt="" width="100px"
                                        height="80%" class="img-fluid">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- End of Carousels --}}
            </div>

            <!-- Right Section: Bids History -->
            <div class="w-md-40 w-100 h-md-100 h-100 pb-3" style="overflow: auto">
                <div class="w-90 h-100 m-auto bids"
                    style="background-color: #FFF; overflow:auto; box-shadow: 2px 7px 15px rgb(208, 207, 207);">
                    <!-- Bids History Header -->
                    <div class="bids-top w-100 h-20 d-flex align-items-center justify-content-center"
                        style="background: #105082 0% 0% no-repeat padding-box;">
                        {{-- <h3 class="text-white">Bids History (15)</h3> --}}
                        <h3 class="text-white">
                            {{ $product->productListing->item_type == 'auction' ? 'Bids History' : 'Offers History' }}
                            {{ $product->productListing->item_type == 'auction' ? '(' . $product->bids->count() . ')' : '(' . $product->offers->count() . ')' }}
                        </h3>
                    </div>

                    @if ($product->productListing->item_type == 'auction')
                        <!-- Bids History Items -->
                        @forelse ($product->bids as $bid)
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
                            <hr class="p-0" style="width: 90%;margin:auto;">
                        @empty
                            <div class="w-100 h-20 d-flex align-items-center justify-content-center">
                                <p>No History</p>
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
                            <div class="w-100 h-20 d-flex align-items-center justify-content-center">
                                <p>No History</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
        {{-- this is the second section --}}
        <!-- Two-column layout with flexbox -->
        <div class="w-100 h-100 d-flex flex-md-row flex-column align-items-center mt-5"
            style="background-color:transparent;overflow:auto">

            <!-- Left column -->
            <div class="w-100 w-md-60  h-md-100 h-100" style="overflow: auto">
                <h2 style="border-bottom: 1px solid gray" class="pb-2">Item Information</h2>

                <!-- Item details table -->
                <div class="w-100 py-3" style="border-bottom:1px solid gray">
                    <table class="w-100">
                        <!-- Table rows for item details -->
                        <tr class="w-100">
                            <td class="w-65 nunito-regular">Dimensions:</td>
                            <td class="w-35 nunito">
                                {{ $product->dim_length }}*{{ $product->dim_width }}*{{ $product->dim_depth }}mm</td>
                        </tr>
                        <tr class="w-100">
                            <td class="w-65 nunito-regular">Weight</td>
                            <td class="w-35 nunito">{{ number_format($product->weight, 2) }} carats</td>
                        </tr>
                        <tr class="w-100">
                            <td class="w-65 nunito-regular">Listing Type</td>
                            <td class="w-35 nunito">{{ $product->productListing->listing_type }}</td>
                        </tr>
                        <tr class="w-100">
                            <td class="w-65 nunito-regular">Treatment</td>
                            <td class="w-35 nunito">{{ $product->treatment }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Item description -->
                <h2 class="py-2">{{ $product->category->name }} – {{ $product->weight }} ct {{ $product->name }}</h2>
                <div class="w-100" style="border-bottom:1px solid gray">
                    <p class="nunito-regular" style="text-align: justify!important">
                        <!-- Placeholder text for the description -->
                        {{ $product->description }}
                    </p>
                </div>
            </div>

            <!-- Right column -->
            <div class="w-md-40 w-100 h-md-100 h-100 pb-3" style="overflow: auto">
                <!-- Store details container -->
                <div class="w-90 h-100 m-auto bids"
                    style="background-color: #FFF;overflow:auto;box-shadow: 2px 5px 15px rgb(208, 207, 207);">

                    <!-- Store details header -->
                    <div class="bids-top w-100 h-20 d-flex align-items-center justify-content-center"
                        style="background: #105082 0% 0% no-repeat padding-box;">
                        <h3 class="text-white">Store Details</h3>
                    </div>

                    <!-- Store details body -->
                    <div class="bids-body h-50 w-100 ">
                        <!-- Store image -->
                        <div class="w-100 h-80 store-image">
                            <img src="{{ asset($product->store->image ?? 'assets/home/stones.jpg') }}" alt=""
                                width="100%" height="100%" style="max-width: 100%;object-fit: cover">
                        </div>
                        <!-- Store name -->
                        <div class="w-100 h-20 store-name d-flex align-items-center justify-content-center">
                            <h2>{{ $product->store->name }}</h2>
                        </div>
                    </div>

                    <!-- Store details footer -->
                    <div class="bids-footer w-100 h-auto  d-flex align-items-center">
                        <!-- Left part of the footer (e.g., "Products") -->
                        <div class="bids-footer-left  w-70 d-flex align-items-center justify-content-center"
                            style="height: 50px">
                            <h4 class="nunito mt-2" style="font-size: 18px">Products</h4>
                        </div>
                        <!-- Right part of the footer (e.g., count) -->
                        <div class="bids-footer-right w-30 d-flex align-items-center justify-content-center"
                            style="height: 50px">
                            <span class="text-white ps-2 rounded-3"
                                style="width:35px;height:25px;background-color:#105082">{{ $product->store->products->count() }}</span>
                        </div>
                    </div>
                    <hr style="width: 90%;margin:auto">
                    <div class="mt-3">
                        {{-- <p class="text-center text-capitalize"><a href="#">Visit Store</a></p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            // new Splide('#secondary-slider', {
            //     type: 'carousel',
            //     fixedWidth: 200,
            //     fixedHeight: 100,
            //     gap: 10,
            //     rewind: true,
            //     pagination: false,
            // }).mount();
        });

    </script>
@endsection
