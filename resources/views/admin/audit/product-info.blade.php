@extends('layouts.admin')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Audit Response</h4>
    </div>
    <div class="row my-4 mx-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.audits.index') }}" class="anchor-button rounded-2" style="width: 100px">Back</a>
            </div>
        </div>
    </div>
    <div class="text-start m-5">
        <div class="col-md-12">
            <div class="row">
                <h3 class="mb-4">Item Details</h3>
                <div class="col-md-4">
                    <div>
                        <img src="{{ asset($product->image) ?? '' }}" alt="img" class="img-fluid" width="200">
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <strong>Item Name: </strong>
                        <p>{{ $product->name }}</p>
                        <strong>Description: </strong>
                        <p>{{ $product->description }}</p>
                        <strong>Category: </strong>
                        <p>{{ $product->category->name }}</p>
                        <strong>Weight: </strong>
                        <p>{{ $product->weight }} (ct)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <strong>Dimensions: </strong>
                        <p>{{ $product->dim_depth }} x {{ $product->dim_length }} x {{ $product->dim_width }}</p>
                        <strong>Listing Type: </strong>
                        <p>{{ $product->productListing->listing_type }}</p>
                        <strong>Item Type: </strong>
                        <p>{{ $product->productListing->item_type == 'auction' ? 'Auction' : 'Buy It Now' }}</p>
                        <strong>Price: </strong>
                        <p>${{ $product->productListing->item_type == 'auction' ? $product->productPricing->starting_price : $product->productPricing->buy_it_now_price }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
