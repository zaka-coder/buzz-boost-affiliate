@extends('layouts.seller', ['title' => 'Add Product'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/seller-css/product.css') }}">
@endsection
@section('content')
    <div class="pre-product h-100 w-100 m-0 p-0">
        {{-- <div class="product-buttons p-2 p-md-4 w-100 h-25">
            <a href="{{ route('seller.items.index') }}" class="me-3 products">Products</a>
            <a href="{{ route('seller.items.create') }}" class="add-product">Add Product</a>
        </div> --}}
        <div class="item-types w-100 h-100 d-flex align-items-center justify-content-center flex-column ">
            <h2>Select Item Type</h2>
            <div class="little-cards-master d-flex align-items-center justify-content-center flex-wrap gap-3 mt-2">
                <a href="{{ route('seller.items.create.type', ['type' => 'auction']) }}">
                    <div class="little-card d-flex flex-column align-items-center justify-content-center text-dark">
                        <i class="bi bi-hammer mb-2"></i>
                        <p class="">Auction</p>
                    </div>
                </a>
                <a href="{{ route('seller.items.create.type', ['type' => 'buy-it-now']) }}">
                    <div class="little-card d-flex flex-column align-items-center justify-content-center text-dark">
                        <i class="bi bi-person-vcard-fill mb-2"></i>
                        <p class="">Buy it now</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
