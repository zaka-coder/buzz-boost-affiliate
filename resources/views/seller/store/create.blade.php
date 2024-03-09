@extends('layouts.seller', ['title' => 'Create Store'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/seller-css/seller-profile.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection
@section('content')
    <form id="form" action="{{ route('seller.store.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="carousel slide carousel-fade" id="carouselExampleFade">
            {{-- welcome slide html --}}
            <div class="carousel-item welcome-slide {{ $errors->any() ? '' : 'active' }}">
                <div class="rock-center">
                    <img src="{{ asset('assets/buyer-assets/rock.png') }}" alt="">
                </div>
                <div class="welcome-user">
                    <h1 class="text-center">Welcome to Seller Account</h1>
                    <p class="text-center">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used
                        to demonstrate the visua or a typeface without relying on meaningful content. Lorem ipsum may be
                        used as a placeholder before final copy is available..</p>
                </div>
                <div class="welcome-footer">
                    <button class="started" type="button" class="next carousel-control-next"
                        data-bs-target="#carouselExampleFade" data-bs-slide="next">
                        Get Started
                    </button>
                </div>
            </div>
            {{-- store slide html --}}
            <div class="carousel-item store-slide @if ($errors->any() && !$errors->has('tax_rate')) active @endif">
                <div class="store-main">
                    <div class="store-header-part">
                        <h1>Store Details</h1>
                    </div>
                    <div class="store-body-part">
                        <div class="store-input-group">
                            <legend>Image <span>*</span></legend>
                            <input class="form-control" type="file" name="image" id=""
                                style="padding-left:4px">
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <legend>Store Name <span>*</span></legend>
                            <input class="" type="text" name="name" id="" value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <legend>Email <span>*</span></legend>
                            <input class="" type="email" name="email" id="" value="{{ old('email') }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <legend>Phone <span>*</span></legend>
                            <input class="" type="tel" name="phone" id="" value="{{ old('phone') }}">
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <legend>Country <span>*</span></legend>
                            <input class="" type="text" name="country" id=""
                                value="{{ old('country') }}">
                            @error('country')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <legend>Address <span>*</span></legend>
                            <input class="" type="text" name="address" id=""
                                value="{{ old('address') }}">
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <legend>City <span>*</span></legend>
                            <input class="" type="text" name="city" id="" value="{{ old('city') }}">
                            @error('city')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <legend>State <span>*</span></legend>
                            <input class="" type="text" name="state" id="" value="{{ old('state') }}">
                            @error('state')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group d-flex align-items-center justify-content-around">
                            <legend class="">Is Your Store Registered?<span>*</span></legend>
                            {{-- <select class="form-select w-50" name="registered"
                                style="outline: none;box-shadow: none;border-color:gray;font-size:12px; font-weight: 600;  color: #000000;">
                                <option value="">Please Select</option>
                                <option value="1" @if (old('registered') == '1') selected @endif>Yes</option>
                                <option value="0" @if (old('registered') == '0') selected @endif>No</option>
                            </select> --}}
                            <input class="" type="radio" name="registered" id="registered_yes" value="1"
                                {{ old('registered') == '1' ? 'checked' : '' }}>
                            <label for="registered_yes">Yes</label>
                            <input class="" type="radio" name="registered" id="registered_no" value="0"
                                {{ old('registered') == '1' ? '' : 'checked' }}>
                            <label for="registered_no">No</label>

                            @error('registered')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="store-input-group">
                            <div id="website_input" class="d-none">
                                <legend>Bussiness or Personal Website </legend>
                                <input class="" type="text" name="website" id="website"
                                    value="{{ old('website') }}">
                            </div>
                        </div>
                    </div>
                    <div class="store-footer-part">
                        <button type="button" class="previous me-auto carousel-control-prev ms-1"
                            data-bs-target="#carouselExampleFade" data-bs-slide="prev">Previous</button>
                        <button type="button" class="next ms-auto carousel-control-next">Next</button>
                    </div>
                </div>
            </div>
            {{-- shipping preferences slide html --}}
            <div class="carousel-item shipping-preferences-slide">
                <div class="shipping-preferences-main">
                    <div class="shipping-preferences-header-part">
                        <h1>Shipping Preferences</h1>
                    </div>
                    <div class="shipping-preferences-body-part">
                        <div class="shipping-terms">
                            <legend>Shipping Terms</legend>
                            <textarea class="shipping-terms-main" name="shipping_terms">
                                {{ old('shipping_terms') }}
                            </textarea>
                        </div>
                        <div class="input-group">
                            <legend>Insurance</legend>
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="insurance" value="{{ old('insurance') }}">
                        </div>
                        <div class="shipping-input-group">
                            <legend>Shipping Providers</legend>
                            @php
                                $shipping_providers = \App\Models\ShippingProvider::all();
                            @endphp
                            <select class="" name="shipping_provider" required>
                                @foreach ($shipping_providers as $shipping_provider)
                                    <option value="{{ $shipping_provider->id }}"
                                        {{ old('shipping_provider') == $shipping_provider->id ? 'selected' : '' }}>
                                        {{ $shipping_provider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <legend>Domestic Shipping Per Item</legend>
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="domestic_shipping_per_item"
                                value="{{ old('domestic_shipping_per_item') }}">
                        </div>
                        <div class="shipping-input-group">
                            <legend>Domestic Transit Time</legend>
                            <input class="" type="text" name="domestic_transit_time"
                                value="{{ old('domestic_transit_time') }}">
                            @error('domestic_transit_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="combine-shipping-decision">
                            <legend style="">Do you Offer Combine Shipping?</legend>
                            <div class="d-flex align-items-center" style="padding-top:-5px">
                                <input class="form-check-input" type="radio" name="combine_shipping" value="yes"
                                    id="combine-shipping-yes" @if (old('combine_shipping') == 'yes') checked @endif>
                                <label for="combine-shipping-yes" class="form-check-label"
                                    style="font-size: 12px;color:black;margin-right:5px">Yes</label>
                                <input class="form-check-input" type="radio" name="combine_shipping" value="no"
                                    id="combine-shipping-no"
                                    {{ old('combine_shipping') == 'yes' ? '' : 'checked' }}><label
                                    for="combine-shipping-no" style="font-size: 12px;color:black;"
                                    class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="combine-shipping" style="width: 100%" id="combine-shipping">
                            <div class="d-flex align-items-center combine-shipping-flex  m-auto">
                                <div class="input-group">
                                    <legend>Domestic Bulk Discount Rate</legend>
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="domestic_bulk_discount_rate"
                                        value="{{ old('domestic_bulk_discount_rate') }}">
                                    {{-- @error('domestic_bulk_discount_rate')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror --}}
                                </div>
                                <div class="shipping-input-group">
                                    <legend>Minimum Order Quantity</legend>
                                    <input class="" type="number" name="minimum_order_quantity"
                                        value="{{ old('minimum_order_quantity') }}">
                                    {{-- @error('minimum_order_quantity')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shipping-preferences-footer-part">
                        <button id="shipping_prev" type="button" class="previous me-auto carousel-control-prev"
                            data-bs-target="#carouselExampleFade" data-bs-slide="prev">Previous</button>
                        <div class="d-flex">
                            <button id="shipping_skip" type="button" class="next ms-auto" {{-- data-bs-target="#carouselExampleFade" data-bs-slide="next" --}}
                                style="opacity: 0.5">Skip</button>
                            <button type="button" class="next ms-auto carousel-control-next">Finish</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- payement methods slide html --}}
            {{-- <div class="carousel-item payement-slide @error('tax_rate') active @enderror">
                <div class="payement-main">
                    <div class="payement-header-part">
                        <h1>Payement Method</h1>
                    </div>
                    <div class="payement-body-part px-2">
                        <div class="payement-part">
                            <div class="tax-label">
                                <h2 class="">Tax</h2>
                                <div class="d-flex align-items-center" style="gap:10px">
                                    <input type="checkbox" name="tax" id="add-tax" value="yes"
                                        {{ old('tax') == 'yes' ? 'checked' : '' }}>
                                    <label for="add-tax">Add tax (ie. GST, VAT etc) to the checkout total to any buyer who
                                        is
                                        in
                                        Thailand.</label>
                                </div>
                            </div>
                            <div class="d-flex payement-inputs">
                                <div class="payement-input-group m-0">
                                    <legend>&nbsp</legend>
                                    <input class="" type="number" name="tax_rate" id="tax_rate"
                                        placeholder="30" disabled value="{{ old('tax_rate') }}">
                                    <legend>Enter the tax rate percentage to apply to buyer in Thailand</legend>
                                    @error('tax_rate')
                                        <small class="text-danger">Please Enter the tax rate</small>
                                    @enderror
                                </div>
                                <div class="payement-input-group p-0 mt-0">
                                    <legend>Payement Method</legend>
                                    <select class="payment-method" type="text" name="method_name" id="method_name">
                                        <option selected>Paypal</option>
                                        <option @if (old('method_name') == 'Stripe') selected @endif>Stripe</option>
                                        <option @if (old('method_name') == 'Payoneer') selected @endif>Payoneer</option>
                                        <option @if (old('method_name') == 'Visa') selected @endif>Visa</option>
                                        <option @if (old('method_name') == 'WorldPay') selected @endif>WorldPay</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="payement-update">
                            <div class="method-name m-auto" style="width:90%;height: 20%">
                                <h2 id="selectedMethod">PayPal</h2>
                            </div>
                            <div class="payement-update-details m-auto d-flex"
                                style="width:90%;height:80%;--bs-gutter-x:0!important;border:1px solid gray;border-radius:4px">
                                <div class="payement-email p-2">
                                    <div class="d-flex align-items-center" style="gap:10px">
                                        <input type="checkbox" name="account_payment" id="payement"
                                            @if (old('account_payment') == 'on') checked @endif>
                                        <label for="account_payment">Account Payment</label>
                                    </div>
                                    <div class="payement-input-group mt-4 m-0" style="width: 80%">
                                        <input type="email" placeholder="yourgmail@gmail.com" name="payment_email"
                                            id="payment_email" value="{{ old('payment_email') }}">
                                    </div>
                                    <legend class="mt-4">Enter the email that you use to accept the paypal
                                        Payements.Ensure this Email is enabled to receive payements in your payapal.com
                                        settings.</legend>
                                </div>
                                <div class="payement-image">
                                    <div style="width: 80%;height:80%">
                                        <img src="{{ asset('assets/buyer-assets/paypal.png') }}" alt=""
                                            id="paymentImg" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payement-footer-part ">
                        <button type="button" class="previous me-auto carousel-control-prev"
                            data-bs-target="#carouselExampleFade" data-bs-slide="prev">Previous</button>
                        <div class="d-flex">
                            <button id="payment_skip" type="button" class="next ms-auto carousel-control-next"
                                style="opacity: 0.5">Skip</button>
                            <button type="button" class="next ms-auto carousel-control-next">Finish</button>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </form>
@endsection
@section('js')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            const combineShipping = $("#combine-shipping");
            const combineShippingYes = $("#combine-shipping-yes");
            const combineShippingNo = $("#combine-shipping-no");

            combineShippingYes.on("click", function() {
                combineShipping.show("scale", {
                    direction: "vertical"
                }, 500, function() {
                    // Callback function to set display flex after the element is shown
                    combineShipping.css("display", "flex");
                });
            });
            combineShippingNo.on("click", function() {
                combineShipping.hide("scale", {
                    direction: "vertical"
                }, 500);
            });

            if ({{ old('combine_shipping') == 'yes' ? 1 : 0 }}) {
                combineShippingYes.trigger('click');
            } else {
                combineShippingNo.trigger('click');
            }
        });
        // updating the div of the payement method selected here
        // var paymentSelect = document.querySelector('.payment-method');
        // var h2Element = document.getElementById('selectedMethod');
        // var paymentMethodImage = document.getElementById('paymentImg');
        // paymentSelect.addEventListener('change',
        //     function() {
        //         var selectedOption = paymentSelect.options[paymentSelect.selectedIndex].text;
        //         h2Element.textContent = selectedOption;
        //         if (selectedOption === 'Paypal') {
        //             paymentMethodImage.src = '{{ asset('assets/buyer-assets/paypal.png') }}';
        //         } else if (selectedOption === 'Stripe') {
        //             paymentMethodImage.src = '{{ asset('assets/buyer-assets/stripe.png') }}';

        //         } else if (selectedOption === 'Payoneer') {
        //             paymentMethodImage.src = '{{ asset('assets/buyer-assets/payoneer.png') }}';

        //         } else if (selectedOption === 'Visa') {
        //             paymentMethodImage.src = '{{ asset('assets/buyer-assets/visa.png') }}';

        //         } else if (selectedOption === 'WorldPay') {
        //             paymentMethodImage.src = '{{ asset('assets/buyer-assets/worldpay.png') }}';

        //         } else {
        //             paymentMethodImage.src = '';
        //         }
        //     });
    </script>

    <script>
        // jQuery Document Ready
        $(document).ready(function() {
            var totalSlides = $(".carousel-item").length;

            // Add event listeners for each carousel item
            $(".carousel-item").each(function(index) {
                var currentSlide = $(this);
                var nextButton = currentSlide.find(".carousel-control-next");
                var previousButton = currentSlide.find(".carousel-control-prev");
                var shippingSkipButton = currentSlide.find(
                    "#shipping_skip"); // Gets the "Skip" button
                var paymentSkipButton = currentSlide.find(
                    "#payment_skip"); // Gets the "Skip" button

                // Handle skip button click
                shippingSkipButton.click(function() {
                    // Uncheck the "Add tax" checkbox
                    currentSlide.find("#combine-shipping-no").prop('checked', true);
                    $('#combine-shipping-no').on("click", function() {
                        $('#combine-shipping').hide();
                    });
                    $('#combine-shipping-no').trigger('click');
                    $("#form").submit();
                });

                // Handle skip button click
                paymentSkipButton.click(function() {
                    // Uncheck the "Add tax" checkbox
                    currentSlide.find("#add-tax").prop('checked', false);
                });

                // Handle next button click
                nextButton.click(function() {
                    if (index === totalSlides - 1) {
                        // If it's the last slide, submit the form
                        $("#form").submit();
                    } else {
                        // Move to the next slide
                        $("#carouselExampleFade").carousel('next');
                    }
                });

                // Handle previous button click
                previousButton.click(function() {
                    // Move to the previous slide
                    $("#carouselExampleFade").carousel('prev');
                });

            });


            // Function to validate inputs for a specific carousel item
            function validateInputs(slide) {
                // Your validation logic goes here
                var isValid = true;

                // Example validation (you can replace this with your own validation)
                slide.find("input").each(function() {
                    $(this).next(".text-danger").remove();
                    if ($(this).prop("required") && $(this).val().trim() === "") {
                        // Display error for required fields
                        $(this).next(".text-danger").remove(); // Remove existing error message
                        $(this).after("<small class='text-danger'>This field is required</small>");
                        isValid = false;
                    }
                    // Add more validation rules as needed
                });

                return isValid;
            }

            $('#add-tax').change(function() {
                if ($(this).is(':checked')) {
                    $('#tax_rate').prop('disabled', false);
                } else {
                    $('#tax_rate').prop('disabled', true);
                }
            });

            // Add change event listener to the radio buttons with name 'registered'
            $('input[name="registered"]').change(function() {
                if ($(this).val() == '1') {
                    // If 'Yes' is selected, remove d-none class
                    $('#website_input').removeClass('d-none');
                } else {
                    // If 'No' is selected, add d-none class
                    $('#website_input').addClass('d-none');
                    $('#website').val(''); // Clear the input
                }
            });
        });
    </script>
@endsection
