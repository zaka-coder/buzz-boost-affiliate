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
    <!-- Container for the payment section -->
    <div class="settings-main-container w-100 h-100">

        <!-- Header section within the payment container -->
        @include('seller.store.store_details_header')

        <!-- payment starts from here -->
        <div class="w-100 h-auto px-2 px-md-5 py-3" style="background-color:#FFF">
            <div class="w-100 h-100">
                <form action="{{ route('seller.store.update-payment', $store->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <!-- row for the paypal payment -->
                    <div class="row mt-4">
                        <h5><b>Paypal</b></h5>
                        <div class="row border border-2 px-2 h-auto">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center gap-2 mt-1 mt-md-3">
                                    <input type="checkbox" name="paypal" id="paypal" value="paypal"
                                        @foreach ($paymentMethods as $method)
                                        @if ($method->name == 'paypal' && $method->status == 1)
                                            checked
                                        @endif @endforeach><label
                                        for="paypal" style="font-size: 14px">Accept Paypal Payment</label>
                                </div>

                                <div class="w-100 mt-2 ">
                                    <fieldset class="input-group w-95">
                                        @php
                                            $email = null;
                                            foreach ($paymentMethods as $method) {
                                                if ($method->name == 'paypal') {
                                                    $email = $method->email;
                                                }
                                            }
                                        @endphp
                                        <input class="form-control w-100" type="email" name="email"
                                            value="{{ old('email') ?? $email }}" placeholder="Enter email">
                                    </fieldset>
                                </div>
                                <div class="w-100">
                                    <p style="font-size: 14px">The email is optional. @if ($paymentMethods->where('name', 'paypal')->first())
                                            Keep enabled if you want to accept the payments using PayPal.
                                        @else
                                            Please provide the PayPal Client ID and Client Secret to accept the payments
                                            using PayPal.
                                        @endif
                                    </p>
                                </div>
                                <div id="paypal_credentials"
                                    class="w-100 mb-3 mt-2 @if ($paymentMethods->where('name', 'paypal')->first()) d-none @endif">
                                    <div class="w-100">
                                        <fieldset class="input-group w-95">
                                            <input class="form-control w-100" type="text" name="paypal_key"
                                                value="" placeholder="Enter Paypal Client ID">
                                        </fieldset>
                                    </div>
                                    <div class="w-100 mt-2">
                                        <fieldset class="input-group w-95">
                                            <input class="form-control w-100" type="text" name="paypal_secret"
                                                value="" placeholder="Enter Paypal Client Secret">
                                        </fieldset>
                                    </div>
                                </div>
                                <div
                                    class="w-100 mb-3 mt-2 {{ $paymentMethods->where('name', 'paypal')->first() ? '' : 'd-none' }}">
                                    <div class="w-100">
                                        <button type="button" class="anchor-button ms-2" onclick="resetCredentials('paypal')">Reset Credentials</button>
                                    </div>
                                </div>
                            </div>
                            <!-- image section-->
                            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                <div class="" style="height:70px;width:200px">
                                    <img src="{{ asset('assets/buyer-assets/paypal.png') }}" alt="payapal Img"
                                        width="100%" height="100%" style="object-fit: contain;max-width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row for the stripe payment -->
                    <div class="row mt-4">
                        <h5><b>Stripe</b></h5>
                        <div class="row border border-2 px-2 h-auto">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center gap-2 mt-1 mt-md-3">
                                    <input type="checkbox" name="stripe" id="stripe" value="stripe"
                                        @foreach ($paymentMethods as $method)
                                        @if ($method->name == 'stripe' && $method->status == 1)
                                            checked
                                        @endif @endforeach><label
                                        for="stripe" style="font-size: 14px">Accept Stripe Payments</label>
                                </div>
                                <div class="w-100 mt-1">
                                    <p style="font-size: 14px">
                                        @if ($paymentMethods->where('name', 'stripe')->first())
                                            Keep enabled if you want to accept the payments using Stripe.
                                        @else
                                            Please provide the Stripe key and secret to accept the payments using Stripe.
                                        @endif
                                    </p>
                                </div>
                                <div id="stripe_credentials"
                                    class="w-100 mb-3 mt-2 @if ($paymentMethods->where('name', 'stripe')->first()) d-none @endif">
                                    <div class="w-100">
                                        <fieldset class="input-group w-95">
                                            <input class="form-control w-100" type="text" name="stripe_key"
                                                value="" placeholder="Enter Stripe Key">
                                        </fieldset>
                                    </div>
                                    <div class="w-100 mt-2">
                                        <fieldset class="input-group w-95">
                                            <input class="form-control w-100" type="text" name="stripe_secret"
                                                value="" placeholder="Enter Stripe Secret">
                                        </fieldset>
                                    </div>
                                </div>
                                <div
                                    class="w-100 mb-3 mt-2 {{ $paymentMethods->where('name', 'stripe')->first() ? '' : 'd-none' }}">
                                    <div class="w-100">
                                        <button type="button" class="anchor-button ms-2" onclick="resetCredentials('stripe')">Reset Credentials</button>
                                    </div>
                                </div>
                            </div>
                            <!-- image section-->
                            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                <div class="" style="height:70px;width:200px">
                                    <img src="{{ asset('assets/buyer-assets/stripe.png') }}" alt="stripe" width="100%"
                                        height="100%" style="object-fit: contain;max-width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- save button -->
                    <div class="my-5">
                        <button class="anchor-button text-white rounded-2 border-0" style="background-color: #105082">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Reset Form -->
        <form id="resetForm" action="{{ route('seller.store.reset-payment-method', $store->id) }}"
            method="post">
            @csrf
            <input type="hidden" name="method">
        </form>
    </div>
@endsection

@section('js')
    <script>
        function resetCredentials(method) {
            $('#resetForm input[name="method"]').val(method);
            $('#resetForm').submit();
        }
    </script>
@endsection
