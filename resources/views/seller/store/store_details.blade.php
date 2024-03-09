@extends('layouts.seller', ['title' => 'Store Details'])
@section('css')
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .anchor-button {
            /* Common styles for both anchor and button */
            display: inline-block;
            text-decoration: none;
            font-size: 16px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            color: #333;
            cursor: pointer;
            width: 150px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            color: #105082;
            font-family: nunito;
            background-color: transparent;
        }

        .anchor-button.active {
            background-color: #105082;
            color: white;
        }

        input[type='text'],
        input[type='email'],
        input[type='file'],
        input[type='number'],
        input[type='tel'],
        select {
            border: none;
            border-radius: 6px;
            padding-left: 15px;
            padding-right: 15px;
            background-color: rgba(34, 34, 34, 0.132);
            height: 34px;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, sans-serif;
            margin: 0;
        }

        input[type='text']:focus,
        input[type='email']:focus,
        input[type='file']:focus,
        input[type='number']:focus,
        input[type='tel']:focus,
        select:focus {
            outline: none;
            box-shadow: none;
        }

        textarea {
            font-size: 14px;
            padding-left: 15px;
        }

        textarea:focus {
            outline: none;
            box-shadow: none;
        }

        legend {
            font-size: 14px;
            font-weight: 400;
            line-height: 0.5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: 0px;
            color: #000000;
            opacity: 1;
        }

        input[type='radio']:checked {
            background-color: #105082
        }

        input[type='checkbox'],
        input[type='radio'] {
            height: 15px;
            width: 15px;
        }

        .input-group-text {
            border-top-left-radius: 6px !important;
            border-bottom-left-radius: 6px !important;
        }

        .form-control {
            border-top-left-radius: 6px !important;
            border-bottom-left-radius: 6px !important;
        }

        .form-control:focus {
            background-color: rgba(34, 34, 34, 0.132);
        }
    </style>
@endsection
@section('content')
    <!-- Container for the shipping section -->
    <div class="settings-main-container w-100 h-100">

        <!-- Header section within the shipping container -->
        @include('seller.store.store_details_header')

        <!-- shipping starts from here -->
        <div class="w-100 h-auto px-2 px-md-5 py-3" style="background-color:#FFF">
            <div class="w-100 h-100">
                <form action="{{ route('seller.store.update', $store->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <!--   row containing two columns  -->
                    <div class="row">
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>Store Name</legend>
                                <input class="form-control" type="text" name="name" class="w-100"
                                    value="{{ old('name') ?? $store->name }}">
                            </fieldset>
                        </div>
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>Email</legend>
                                <input class="form-control" type="email" name="email" class="w-100"
                                    value="{{ old('email') ?? $store->email }}">
                            </fieldset>
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row">
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>Phone</legend>
                                <input class="form-control" type="tel" name="phone" class="w-100"
                                    value="{{ old('phone') ?? $store->phone }}">
                            </fieldset>
                        </div>
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>Country</legend>
                                <input class="form-control" type="text" class="w-100" name="country"
                                    value="{{ old('country') ?? $store->country }}">
                            </fieldset>
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row">
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>Address</legend>
                                <input class="form-control" type="text" class="w-100" name="address"
                                    value="{{ old('address') ?? $store->address }}">
                            </fieldset>
                        </div>
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>City</legend>
                                <input class="form-control" type="text" class="w-100" name="city"
                                    value="{{ old('city') ?? $store->city }}">
                            </fieldset>
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row">
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>State</legend>
                                <input class="form-control" type="text" class="w-100" name="state"
                                    value="{{ old('state') ?? $store->state }}">
                            </fieldset>
                        </div>
                        <div class="col-md-12 col-lg-6 mt-3">
                            <fieldset class="input-group w-95">
                                <legend>Bussiness Website</legend>
                                <input class="form-control" type="text" class="w-100" name="website"
                                    value="{{ old('website') ?? $store->website }}">
                            </fieldset>
                        </div>
                    </div>

                    <!--   row containing two columns  -->
                    <div class="row mt-4">
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <fieldset class="w-95">
                                <legend>Is your store Registered?</legend>
                                <select class="w-100" name="registered" id="">
                                    <option value="1" @if (old('registered') == '1' || $store->registered == 1) selected @endif>Yes</option>
                                    <option value="0" @if (old('registered') == '0' || $store->registered == 0) selected @endif>No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <fieldset class="input-group w-95">
                                <legend>Insurance</legend>
                                <span class="input-group-text" style="height: 34px;">$</span>
                                <input class="form-control" type="number" class="w-100" name="insurance"
                                    value="{{ old('insurance') ?? $store->insurance }}"
                                    style="border-top-left-radius: 0!important;border-bottom-left-radius: 0!important">
                            </fieldset>
                        </div>
                    </div>
                    <!-- row for the shipping terms -->
                    <div class="row mt-4">
                        <div class="col-md-12 col-lg-6 mt-5 mt-lg-0">
                            <legend>Description</legend>
                            <textarea class="w-95 me-auto d-block" name="description" id="description" cols="10" rows="10"
                                style="min-height:60px;height:auto">{{ old('description') ?? $store->description }}</textarea>
                        </div>
                        <div class="col-md-12 col-lg-6 mt-5 mt-lg-0">
                            <legend>Shipping Terms</legend>
                            <textarea class="w-95 me-auto d-block" name="shipping_terms" id="" cols="10" rows="10"
                                style="min-height:60px;height:auto ">{{ old('shipping_terms') ?? $store->shipping_terms }}</textarea>
                        </div>

                    </div>
                    <!-- row for minimum offer -->
                    <div class="row mt-4">
                        <div class="col-md-12 col-lg-6 mt-5 mt-lg-0">
                            <fieldset class="input-group w-95">
                                <legend>Minimum Offer</legend>
                                <input class="form-control" type="number" class="w-100" name="minimum_offer"
                                    value="{{ old('minimum_offer') ?? $store?->minimum_offer }}">
                                <span class="input-group-text"
                                    style="height: 34px; border-top-left-radius: 0!important;border-bottom-left-radius: 0!important">%</span>
                            </fieldset>
                            <div class="pe-2">
                                <small> Enter the minimum offer percentage relative to the Start Bid or Buy It Now price.
                                    Offers or Bids
                                    below this amount are automatically rejected.</small>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <fieldset class="input-group w-95">
                                <legend>Tax</legend>
                                <input class="form-control" type="number" class="w-100" name="tax"
                                    value="{{ old('tax') ?? $store->tax }}">
                                <span class="input-group-text"
                                    style="height: 34px;border-top-left-radius: 0!important;border-bottom-left-radius: 0!important">%</span>
                            </fieldset>
                            <div class="pe-2">
                                <small>Enter the tax rate percentage to apply to buyers in
                                    Thailand. Add tax (ie. GST, VAT etc) to the checkout total to any buyer
                                    who is in Thailand.</small>
                            </div>
                        </div>
                    </div>
                    {{-- save Button row --}}
                    <div class="mt-5">
                        <button type="submit" class="anchor-button text-white rounded-2 border-0"
                            style="background-color: #105082">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
