@extends('layouts.seller', ['title' => 'Store Details'])
@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/seller-css/product.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/home/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    {{-- products cards css --}}
    <style>
        /* store styles */
        .store_name {
            font: normal normal 600 24px segoe;
            color: #EA008B;
        }

        .nunito {
            font-family: nunito, sans-serif;
        }

        .nunito-regular {
            font-family: nunito-regular, sans-serif;
        }

        .item {
            width: 120px;
            height: 40px;
            border-radius: 4px;
            margin: 5px 0px;
            background-color: #EFF3F74D;
            border: 1px solid #9ea0a1;
            font-size: 14px;
        }
    </style>
@endsection
@section('content')
    <div class="w-100 h-100">
        <div class="w-100 h-auto h-md-40 d-flex flex-column flex-md-row align-items-center p-md-2"
            style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
            <div class="w-60 w-md-25 h-100 position-relative">
                <img src="{{ asset($store->image ?? 'assets/home/stones.jpg') }}" alt="" width="100%" height="100%"
                    style="max-width: 100%;object-fit: cover;border-radius:8px">
                <button
                    class="position-absolute bg-white d-flex align-items-center justify-content-center shadow-lg border-0"
                    data-bs-toggle="modal" data-bs-target="#storeImageModal"
                    style="width: 35px;height:35px;border-radius: 50%;bottom:0%;right:-15px">
                    <h4 class="bi bi-camera-fill p-0 m-0"></h4>
                </button>
            </div>
            <div class="w-100 w-md-40 h-100  d-flex flex-column justify-content-center align-items-center">
                <h2 class="store_name">{{ $store->name }} <i class="bi bi-patch-check-fill" style="color: #10B981"></i>
                </h2>
                <p class="nunito-regular text-center">{{ $store->address }}</p>
                <p class="nunito-regular text-center">Store Registered :
                    <span>{{ $store->registered ? 'Yes' : 'No' }}</span>
                </p>
                <p class="nunito-regular text-center">Member Since {{ $store->created_at->format('d M Y') }}</p>
            </div>
            <div class="w-100 w-md-35 h-100 d-flex flex-column justify-content-center align-items-center ">
                <div class="item d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box"></i>
                    <p class="p-0 m-0 nunito-regular">{{ $store->products->count() }} Item(s)</p>
                </div>
                <a href="{{ route('chats.index', ['role' => 'seller']) }}">
                    <div class="item  d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box" style="color: black"></i>
                        <p class="p-0 m-0 nunito-regular " style="color: black">Messeges</p>
                    </div>
                </a>
                <a href="{{ route('seller.store.edit', $store->id) }}" class="">
                    <div class="item d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-pen" style="color: black"></i>
                        <p class="p-0 m-0 nunito-regular" style="color: black">Settings</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="w-100 mt-3 h-auto  overflow-auto d-flex align-items-center justify-content-center">
            <div class="w-100">
                <div class="products-cards  grid_system_rectangle">
                    @foreach ($products as $product)
                        <div class="card">
                            <div class="card-head p-2">
                                <section id="splidePro{{ $product->id }}" class="splide w-100 h-100 " aria-label="">
                                    <div class="splide__track w-100 h-100">
                                        <ul class="splide__list w-100 h-100">
                                            <li class="splide__slide w-100 h-100 position-relative">
                                                <img src="{{ asset($product->image ?? 'assets/buyer-assets/coming-soon.avif') }}"
                                                    alt=""
                                                    style="width:100%;height:100%;max-width:100%;object-fit:cover"
                                                    class="rounded-3">
                                                <span class="featured-tag"
                                                    style="background-color:
                                    @if ($product->productListing->listing_type == 'Showcase') #F56565;
                                    @elseif ($product->productListing->listing_type == 'Premium') #105082;
                                    @elseif ($product->productListing->listing_type == 'Standard') #EA008B;
                                    @elseif ($product->productListing->listing_type == 'Boost') #4AAE43;
                                    @else #EA008B; @endif">
                                                    {{ $product->productListing->listing_type ?? '' }}
                                            </li>
                                            @foreach ($product->gallery as $gallery)
                                                <li class="splide__slide w-100 h-100 position-relative">
                                                    <img src="{{ asset($gallery->image ?? 'assets/buyer-assets/coming-soon.avif') }}"
                                                        alt=""
                                                        style="width:100%;height:100%;max-width:100%;object-fit:cover"
                                                        class="rounded-3">
                                                    <span class="featured-tag"
                                                        style="background-color:
                                        @if ($product->productListing->listing_type == 'Showcase') #F56565;
                                        @elseif ($product->productListing->listing_type == 'Premium') #105082;
                                        @elseif ($product->productListing->listing_type == 'Standard') #EA008B;
                                        @elseif ($product->productListing->listing_type == 'Boost') #4AAE43;
                                        @else #EA008B; @endif">
                                                        {{ $product->productListing->listing_type ?? '' }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </section>
                            </div>
                            <a {{-- href="{{ route('seller.items.show', $product->id) }}" --}} href="{{ route('products.show', $product->id) }}"
                                class="products-card-link">
                                <div class="card-body">
                                    <div class="card-body-first">
                                        <h5 class="m-0 p-2 text-center text-truncate text-capitalize">
                                            {{ $product->weight }} ct
                                            {{ $product->name }} </h5>
                                    </div>
                                </div>
                            </a>
                            <div class="time-part pt-2">
                                @if ($product->productListing->item_type == 'auction')
                                    @if (!$product->is_published)
                                        <p class="p-0 m-0 text-center text-white bg-primary mx-5 rounded-3">Awaiting Publish
                                        </p>
                                    @elseif (!$product->productListing->closed)
                                        @php
                                            $countdownTime = $product->countdown_time;
                                        @endphp
                                        <p class="p-0 m-0 text-center" id="footerTime{{ $product->id }}">
                                            {{ $countdownTime['days'] . 'd ' . $countdownTime['hours'] . 'h ' . $countdownTime['minutes'] . 'm ' ?? 'N/A' }}
                                        </p>
                                    @elseif ($product->productListing->closed)
                                        <p class="p-0 m-0 text-center text-white bg-danger mx-5 rounded-3">Closed</p>
                                    @elseif ($product->is_sold)
                                        <p class="p-0 m-0 text-center text-white bg-danger mx-5 rounded-3">Sold</p>
                                    @endif
                                @elseif ($product->is_sold)
                                    <p class="p-0 m-0 text-center text-white bg-danger mx-5 rounded-3">Sold</p>
                                @elseif ($product->productListing->closed)
                                    <p class="p-0 m-0 text-center text-white bg-danger mx-5 rounded-3">Closed</p>
                                @endif
                            </div>
                            <div class="card-footer ">
                                <div class="footer-icons-part d-flex align-items-center  justify-content-start ps-3  gap-1">
                                    {{-- edit item --}}
                                    @if (!$product->is_sold)
                                        <a href="{{ route('seller.items.edit', $product->id) }}"
                                            class="m-0 p-0  border-0 bg-transparent" type="button" title="Edit">
                                            <i class="bi bi-pencil-square" style="padding-top:2px!important"></i>
                                        </a>
                                        {{-- delete item --}}
                                        {{-- <button type="button" class="m-0 p-0 border-0 bg-transparent" data-bs-toggle="modal"
                                        data-bs-target="#deleteItem{{ $product->id }}Modal" type="button" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button> --}}
                                    @endif
                                    <a href="{{ route('seller.items.show', $product->id) }}"
                                        class="m-0 p-0 rounded-4 border-0 bg-transparent" style="background-color:#DAE1EB;"
                                        type="button" title="Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                <div class="footer-price-time-part d-flex align-items-center justify-content-center">
                                    <div class="footer-price-part">
                                        <p>${{ $product->productListing->item_type == 'auction' ? $product->productPricing->starting_price : $product->productPricing->buy_it_now_price }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if (count($products) == 0)
            <div class="w-100 h-50 d-flex flex-column align-items-center justify-content-center gap-2">
                <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt="" style="filter: invert(1)">
                <p class="nunito">No items found</p>
            </div>
        @endif
    </div>
@endsection

@section('modals')
    @foreach ($products as $product)
        {{-- delete item modal --}}
        <div class="modal fade" id="deleteItem{{ $product->id }}Modal" data-bs-backdrop="static"
            data-bs-keyboard="true" tabindex="-1" aria-labelledby="deleteItem{{ $product->id }}ModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteItem{{ $product->id }}ModalLabel">Attention!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('seller.items.destroy', $product->id) }}" method="post">
                        @csrf @method('DELETE')
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-sm btn-danger">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- store picture modal --}}
    <div class="modal fade" id="storeImageModal" tabindex="-1" aria-labelledby="storeImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="storeImageModalLabel">Upload Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0">
                    <form id="storeImageForm" method="post"
                        action="{{ route('seller.store.image.update', $store->id) }}" class="needs-validation"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <input class="form-control" type="file" id="store_image" name="store_image" required
                            accept=".png,.jpg,.jpeg">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary lookBetter btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="storeImageForm"
                        class="btn btn-primary text-white btn-sm changerPrev">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script>
        @foreach ($products as $product)
            new Splide('#splidePro{{ $product->id }}', {
                arrows: false,
                type: 'fade',
                heightRatio: 0.5,
            }).mount();
        @endforeach
    </script>
@endsection
