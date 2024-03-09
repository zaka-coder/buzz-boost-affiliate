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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('seller.store.update-shipping', $store->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <!-- Fedex -->
                    @php
                        $fedex = 0;
                        $fedex_shipping_per_item = null;
                        $fedex_transit_time = null;
                        $fedex_combine = null;
                        $fedex_bulk_discount = null;
                        $fedex_min_quantity = null;

                        foreach ($store->shippings as $shipping) {
                            if ($shipping->shipping_provider == 'fedex') {
                                $fedex = 1;
                                $fedex_shipping_per_item = $shipping->domestic_shipping_fee_per_item;
                                $fedex_transit_time = $shipping->domestic_transit_time;
                                $fedex_combine = $shipping->combine_shipping;
                                $fedex_bulk_discount = $shipping->domestic_bulk_discount_rate;
                                $fedex_min_quantity = $shipping->minimum_order_quantity;
                            }
                        }
                    @endphp
                    <!--   row containing two columns****** Fedex starts here  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <h5><b>FedEx</b></h5>
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="fedex" id="fedex" value="fedex"
                                    @foreach ($store->shippings as $method)
                                    @if ($method->shipping_provider == 'fedex')
                                        checked
                                    @endif @endforeach>
                                <label for="fedex" style="font-size: 14px">Enable FedEx</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">

                                <fieldset class="input-group w-95">
                                    <legend>Domestic Shipping Per Item</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="fedex_shipping_per_item"
                                        value="{{ old('fedex_shipping_per_item') ?? $fedex_shipping_per_item }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Domestic transit time</legend>
                                    <input type="text" name="fedex_transit_time" class="w-100"
                                        value="{{ old('fedex_transit_time') ?? $fedex_transit_time }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="div-col-12">
                                <h6>Do you offer combine shipping?</h6>
                                <input type="radio" name="fedex_combine" id="fedex_combine_yes" value="yes"
                                    @if ($fedex_combine == 'yes') checked @endif> <label
                                    for="fedex_combine_yes">Yes</label>
                                <input type="radio" name="fedex_combine" id="fedex_combine_no" value="no"
                                    @if ($fedex_combine == 'no' || $fedex_combine === null) checked @endif> <label
                                    for="fedex_combine_no">No</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic bulk discount rate</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="fedex_bulk_discount"
                                        value="{{ old('fedex_bulk_discount') ?? $fedex_bulk_discount }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Minimum order quantity</legend>
                                    <input type="text" name="fedex_min_quantity" class="w-100"
                                        value="{{ old('fedex_min_quantity') ?? $fedex_min_quantity }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5" style="width: 95$;margin:auto">

                    <!-- DHL -->
                    @php
                        $dhl = 0;
                        $dhl_shipping_per_item = null;
                        $dhl_transit_time = null;
                        $dhl_combine = null;
                        $dhl_bulk_discount = null;
                        $dhl_min_quantity = null;

                        foreach ($store->shippings as $shipping) {
                            if ($shipping->shipping_provider == 'dhl') {
                                $dhl = 1;
                                $dhl_shipping_per_item = $shipping->domestic_shipping_fee_per_item;
                                $dhl_transit_time = $shipping->domestic_transit_time;
                                $dhl_combine = $shipping->combine_shipping;
                                $dhl_bulk_discount = $shipping->domestic_bulk_discount_rate;
                                $dhl_min_quantity = $shipping->minimum_order_quantity;
                            }
                        }
                    @endphp
                    <!--   row containing two columns****** DHL starts here  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <h5><b>DHL</b></h5>
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="dhl" id="dhl" value="dhl"
                                    @if ($dhl == 1) checked @endif><label for="dhl"
                                    style="font-size: 14px">Enable DHL</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic Shipping Per Item</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="dhl_shipping_per_item"
                                        value="{{ old('dhl_shipping_per_item') ?? $dhl_shipping_per_item }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Domestic transit time</legend>
                                    <input type="text" name="dhl_transit_time" class="w-100"
                                        value="{{ old('dhl_transit_time') ?? $dhl_transit_time }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="div-col-12">
                                <h6>Do you offer combine shipping?</h6>
                                <input type="radio" name="dhl_combine" id="dhl_yes" value="yes"
                                    @if ($dhl_combine == 'yes') checked @endif> <label for="dhl_yes">Yes</label>
                                <input type="radio" name="dhl_combine" id="dhl_no" value="no"
                                    @if ($dhl_combine == 'no' || $dhl_combine == null) checked @endif> <label for="dhl_no">No</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic bulk discount rate</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="dhl_bulk_discount"
                                        value="{{ old('dhl_bulk_discount') ?? $dhl_bulk_discount }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Minimum order quantity</legend>
                                    <input type="text" name="dhl_min_quantity" class="w-100"
                                        value="{{ old('dhl_min_quantity') ?? $dhl_min_quantity }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5" style="width: 95$;margin:auto">

                    <!-- Express Shipping -->
                    @php
                        $express = 0;
                        $express_shipping_per_item = null;
                        $express_transit_time = null;
                        $express_combine = null;
                        $express_bulk_discount = null;
                        $express_min_quantity = null;

                        foreach ($store->shippings as $shipping) {
                            if ($shipping->shipping_provider == 'express') {
                                $express = 1;
                                $express_shipping_per_item = $shipping->domestic_shipping_fee_per_item;
                                $express_transit_time = $shipping->domestic_transit_time;
                                $express_combine = $shipping->combine_shipping;
                                $express_bulk_discount = $shipping->domestic_bulk_discount_rate;
                                $express_min_quantity = $shipping->minimum_order_quantity;
                            }
                        }
                    @endphp
                    <!--   row containing two columns****** Express shipping  starts here  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <h5><b>Express Shipping</b></h5>
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="express" id="express" value="express"
                                    @if ($express == 1) checked @endif><label for="express"
                                    style="font-size: 14px">Express Shipping</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic Shipping Per Item</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="express_shipping_per_item"
                                        value="{{ old('express_shipping_per_item') ?? $express_shipping_per_item }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Domestic transit time</legend>
                                    <input type="text" name="express_transit_time" class="w-100"
                                        value="{{ old('express_transit_time') ?? $express_transit_time }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="div-col-12">
                                <h6>Do you offer combine shipping?</h6>
                                <input type="radio" name="express_combine" id="express_yes" value="yes"
                                    @if ($express_combine == 'yes') checked @endif> <label for="express_yes">Yes</label>
                                <input type="radio" name="express_combine" id="express_no" value="no"
                                    @if ($express_combine == 'no' || $express_combine == null) checked @endif>
                                <label for="express_no">No</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic bulk discount rate</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="express_bulk_discount"
                                        value="{{ old('express_bulk_discount') ?? $express_bulk_discount }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Minimum order quantity</legend>
                                    <input type="text" name="express_min_quantity" class="w-100"
                                        value="{{ old('express_min_quantity') ?? $express_min_quantity }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5" style="width: 95$;margin:auto">

                    <!-- Registered Shipping -->
                    @php
                        $registered = 0;
                        $reg_shipping_per_item = null;
                        $reg_transit_time = null;
                        $reg_combine = null;
                        $reg_bulk_discount = null;
                        $reg_min_quantity = null;

                        foreach ($store->shippings as $shipping) {
                            if ($shipping->shipping_provider == 'registered') {
                                $registered = 1;
                                $reg_shipping_per_item = $shipping->domestic_shipping_fee_per_item;
                                $reg_transit_time = $shipping->domestic_transit_time;
                                $reg_combine = $shipping->combine_shipping;
                                $reg_bulk_discount = $shipping->domestic_bulk_discount_rate;
                                $reg_min_quantity = $shipping->minimum_order_quantity;
                            }
                        }
                    @endphp
                    <!--   row containing two columns****** Registered shipping  starts here  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <h5><b>Registered Shipping</b></h5>
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="registered" id="registered" value="registered"
                                    @if ($registered == 1) checked @endif><label for="registered"
                                    style="font-size: 14px">Registered Shipping</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic Shipping Per Item</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="reg_shipping_per_item"
                                        value="{{ old('reg_shipping_per_item') ?? $reg_shipping_per_item }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Domestic transit time</legend>
                                    <input type="text" name="reg_transit_time" class="w-100"
                                        value="{{ old('reg_transit_time') ?? $reg_transit_time }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="div-col-12">
                                <h6>Do you offer combine shipping?</h6>
                                <input type="radio" name="reg_combine" id="reg_yes" value="yes"
                                    @if ($reg_combine == 'yes') checked @endif> <label for="reg_yes">Yes</label>
                                <input type="radio" name="reg_combine" id="reg_no" value="no"
                                    @if ($reg_combine == 'no' || $reg_combine == null) checked @endif> <label for="reg_no">No</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic bulk discount rate</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="reg_bulk_discount" value="{{ old('reg_bulk_discount') ?? $reg_bulk_discount }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Minimum order quantity</legend>
                                    <input type="text" name="reg_min_quantity" class="w-100" value="{{ old('reg_min_quantity') ?? $reg_min_quantity }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5" style="width: 95$;margin:auto">

                    <!-- Standard Shipping -->
                    @php
                        $standard = 0;
                        $standard_shipping_per_item = null;
                        $standard_transit_time = null;

                        foreach ($store->shippings as $shipping) {
                            if ($shipping->shipping_provider == 'standard') {
                                $standard = 1;
                                $standard_shipping_per_item = $shipping->domestic_shipping_fee_per_item;
                                $standard_transit_time = $shipping->domestic_transit_time;
                            }
                        }
                    @endphp
                    <!--   row containing two columns****** standard shipping  starts here  -->
                    <div class="row mt-4">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <h5><b>Standard Shipping</b></h5>
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="standard" id="standard" value="standard" @if ($standard == 1) checked @endif><label
                                    for="standard" style="font-size: 14px">Standard Shipping</label>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="d-none d-lg-block col-lg-6 mt-3 mt-lg-0">
                        </div>
                    </div>
                    <!--   row containing two columns  -->
                    <div class="row mt-2">
                        {{-- first column start here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="input-group w-95">
                                    <legend>Domestic Shipping Per Item</legend>
                                    <span class="input-group-text" style="height: 34px;">$</span>
                                    <input class="form-control" type="number" name="standard_shipping_per_item" value="{{ old('standard_shipping_per_item') ?? $standard_shipping_per_item }}">
                                </fieldset>
                            </div>
                        </div>
                        {{-- 2nd column starts here --}}
                        <div class="col-md-12 col-lg-6 mt-3 mt-lg-0">
                            <div class="mt-3">
                                <fieldset class="w-95">
                                    <legend>Domestic transit time</legend>
                                    <input type="text" name="standard_transit_time" class="w-100" value="{{ old('standard_transit_time') ?? $standard_transit_time }}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5" style="width: 95$;margin:auto">
                    {{-- save Button row --}}
                    <div class="mt-5">
                        <button type="submit" class="anchor-button text-white rounded-2 border-0"
                            style="background-color: #105082">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
