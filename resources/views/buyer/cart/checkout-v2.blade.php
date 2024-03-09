@extends('layouts.buyer', ['title' => 'Checkout'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/cart.css') }}">
    <style>
        /* CSS for loader */
        .loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 1000;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
@section('content')
    <div class="w-100 h-100 d-flex flex-column gap-5" style="background-color:#FFF;overflow:auto">

        @php
            $store = $products->first()->store;
            // check if the user is blocked by the store
            if (auth()->user()) {
                $isBlocked = $store->blocked_users->contains(auth()->user()->id);
            } else {
                $isBlocked = false;
            }

            // get all products ids in an array
            $ids = $products->pluck('id')->toArray();
        @endphp

        @if (!$isBlocked)
            <div class="w-100 d-flex flex-column flex-md-row align-items-start">
                {{-- left side of the parent div --}}
                <div class="w-100 w-md-50  h-auto p-md-3 p-2">
                    <div class="w-100 p-md-2">
                        <h2>Shipping Information</h2>
                        <table class="w-100">
                            <tr class="w-100" style="border-bottom:1px solid #FFF">
                                <td class="shipping-info-header w-50">Contact Detail</td>
                                <td class="shipping-info-header w-50">Shipping Address</td>
                            </tr>
                            <tr class="w-100" style="border-bottom:1px solid #FFF">
                                <td class="shipping-info w-50" id="shipping_name_display">
                                    {{ auth()->user()->name }}
                                </td>
                                <td class="shipping-info w-50" id="shipping_address_display">
                                    {{ auth()->user()?->profile->address }}</td>
                            </tr>
                            <tr class="w-100" style="border-bottom:1px solid #FFF">
                                <td class="shipping-info w-50" id="shipping_email_display">
                                    {{ auth()->user()->email }}
                                </td>
                                <td class="shipping-info w-50 ">
                                    <a href="javascript:void(0)" id="shipToAnotherBtn">
                                        Ship to Another Address
                                    </a>
                                </td>
                            </tr>
                            <tr class="w-100" style="border-bottom:1px solid #FFF">
                                <td class="shipping-info w-50">
                                    {{ auth()->user()?->profile->contact }}
                                </td>
                                <td class="shipping-info w-50 "></td>
                            </tr>
                        </table>


                        <!-- Shipping Information Inputs -->
                        <div id="shipping-details-inputs" class="w-100 h-auto d-none"
                            style="background-color:#FFF;overflow:auto">
                            {{-- <h2 class="text-center text-black mt-2">Update Shipping Details</h2> --}}
                            <h2 class="mt-3">Contact Details</h2>
                            <div class="w-100 mt-3">
                                <label class="contact-input-label">Name</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="text" name="name" id="name" value="{{ auth()->user()->name }}"
                                        class="form-control w-90 ms-auto d-block h-100">
                                </div>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="w-100 mt-3">
                                <label class="contact-input-label">Email</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="email" id="email" name="email"
                                        class="form-control w-90 ms-auto d-block h-100" value="{{ auth()->user()->email }}">
                                </div>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <h2 class="mt-4">Shipping Address</h2>
                            <div class="w-100 mt-3">
                                <label class="contact-input-label">Address</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="text" id="address" name="address"
                                        class="form-control w-90 ms-auto d-block h-100"
                                        value="{{ auth()->user()?->profile->address }}">
                                </div>
                                <small id="address-error" class="text-danger"></small>
                            </div>
                            <div class="w-100 mt-3">
                                <label class="contact-input-label">City</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="text" id="city" name="city"
                                        value="{{ auth()->user()?->profile->city }}"
                                        class="form-control w-90 ms-auto d-block h-100">
                                </div>
                            </div>
                            <div class="w-100 mt-3">
                                <label class="contact-input-label">State</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="text" id="state" name="state"
                                        value="{{ auth()->user()?->profile->state }}"
                                        class="form-control w-90 ms-auto d-block h-100">
                                </div>
                            </div>
                            <div class="w-100 mt-3">
                                <label class="contact-input-label">Country</label>

                                <div class="input-group w-100 " style="height:35px">
                                    <input type="text" id="country" name="country"
                                        class="form-control w-90 ms-auto d-block h-100"
                                        value="{{ auth()->user()?->profile->country }}">
                                </div>
                            </div>
                            <div class="w-100 mt-3">
                                <label class="contact-input-label">Postal Code</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="number" id="postal_code" name="postal_code"
                                        class="form-control w-90 ms-auto d-block h-100"
                                        value="{{ auth()->user()?->profile->zip }}">
                                </div>
                            </div>
                            <div class="contact-info my-3">
                                <button id="update_shipping_btn" type="button" class="px-4 d-block">Update</button>
                            </div>

                        </div>
                        <!-- End Shipping Information Inputs -->





                    </div>
                    <div class="w-100 p-md-2">
                        <h2>Delivery Method</h2>
                        <div class="w-100 mt-2">
                            <label class="mb-2">Shipping Providers</label>
                            <div class="w-100" style="height:35px">
                                <select class="w-100  d-block h-100" name="shipping_provider" id="shipping_provider"
                                    onchange="changeShippingProvider({{ $store->id }})">
                                    @if ($products->count() == 1 && $products[0]->productShipping->shipping_type != 'normal')
                                        <option value="0" selected>Free Shipping</option>
                                    @else
                                        @foreach ($store->shippings as $shippingProvider)
                                            <option value="{{ $shippingProvider->id }}"
                                                @if ($shippingProvider->id == $selectedShipping) selected @endif>
                                                {{ strtoupper($shippingProvider->shipping_provider) . ' - $' . $shippingProvider->domestic_shipping_fee_per_item }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            {{-- <small class="text-danger">this is for error</small> --}}
                        </div>
                        <p>Please note that items with custom shipping might affect on the total shipping Price.</p>
                        <div class="standard-shipping rounded-1 py-4 d-flex align-items-center justify-content-center">
                            <h2 class="text-center">
                                {{ $products[0]->productShipping->shipping_type == 'normal' ? 'Normal Shipping - Tracked' : 'Free Shipping' }}
                                <i class="bi bi-check-circle-fill"></i>
                            </h2>
                        </div>
                        <div class="w-100 mt-2">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" id="insuranceCheck" name="insurance" class="form-check-input"
                                    @if ($insurance) checked @endif value="{{ $store->insurance }}"
                                    onchange="addInsurance()">
                                <label for="insurance" class="text-dark fw-normal"><span class="fw-bold">Postal
                                        Insurance</span>
                                    to insure your order during transit</label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="w-100 p-md-2">
                        <h2>Payment Methods</h2>
                        @if ($store->paymentMethods && $store->paymentMethods->where('status', 1)->count() > 0)
                            {{-- create two radio buttons for payment methods --}}
                            <div>
                                <input type="radio" id="paypalMethod" name="selected_payment" value="paypal"
                                    @if ($store->paymentMethods->where('name', 'paypal')->where('status', 1)->first()) checked @endif>
                                <label for="paypalMethod">PayPal</label>
                                <input type="radio" id="creditCardMethod" name="selected_payment" value="credit_card"
                                    @if (!$store->paymentMethods->where('name', 'paypal')->where('status', 1)->first()) checked @endif>
                                <label for="creditCardMethod">Credit Card</label>
                            </div>

                            <!-- Paypal -->
                            @if ($store->paymentMethods->where('name', 'paypal')->where('status', 1)->first())
                                <!-- Set up a container element for the paypal buttons -->
                                <div id="paypal-button-container" class="my-5"></div>
                            @else
                                <p id="paypal-button-container" class="my-3">This payment method is not
                                    supported by this store.</p>
                            @endif

                            <!-- Stripe -->
                            @if ($store->paymentMethods->where('name', 'stripe')->where('status', 1)->first())
                                <!-- Stripe form -->
                                <div id="stripeForm" class="d-none">
                                    <form action="{{ route('buyer.stripe.store') }}" method="POST" id="card-form">
                                        @csrf
                                        @foreach ($products as $product)
                                            <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                        @endforeach
                                        <input type="hidden" name="order_id" id="order_id"
                                            value="{{ $order->id ?? '' }}">
                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ Auth::user()->id }}">
                                        <input type="hidden" name="name" id="card-name"
                                            value="{{ Auth::user()->name }}">
                                        <input type="hidden" name="email" id="card-email"
                                            value="{{ Auth::user()->email }}">
                                        <input type="hidden" name="store_id" id="store_id"
                                            value="{{ $store->id }}">
                                        <input type="hidden" name="shipping_name" id="shipping_name"
                                            value="{{ auth()->user()->name }}">
                                        <input type="hidden" name="shipping_email" id="shipping_email"
                                            value="{{ auth()->user()->email }}">
                                        <input type="hidden" name="shipping_phone" id="shipping_phone"
                                            value="{{ auth()->user()->profile->contact }}">
                                        <input type="hidden" name="shipping_address" id="shipping_address"
                                            value="{{ auth()->user()->profile->address }}">
                                        <input type="hidden" name="shipping_city" id="shipping_city"
                                            value="{{ auth()->user()->profile->city }}">
                                        <input type="hidden" name="shipping_country" id="shipping_country"
                                            value="{{ auth()->user()->profile->country }}">
                                        <input type="hidden" name="shipping_state" id="shipping_state"
                                            value="{{ auth()->user()->profile->state }}">
                                        <input type="hidden" name="shipping_postal_code" id="shipping_postal_code"
                                            value="{{ auth()->user()->profile->zip }}">
                                        {{-- <input type="hidden" name="shipping_cost" id="shipping_cost" value="0"> --}}
                                        <input type="hidden" name="shipping_provider_id" id="shipping_provider_id"
                                            value="0">
                                        <input type="hidden" name="insurance" id="insurance_cost" value="0">

                                        <div class="my-5 w-100">
                                            <label for="card" class="mb-4 text-dark fw-bold">Card
                                                details</label>

                                            <div class="rounded w-100">
                                                <div id="card"></div>
                                            </div>
                                        </div>
                                        <div
                                            class="w-100 my-4 rounded-2 d-flex align-items-center justify-content-center gap-2">
                                            <button type="submit" class="btn btn-primary w-100" style="height: 45px">Pay
                                                Now</button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <p id="stripeForm" class="my-3 d-none">This payment method is not supported by
                                    this store.</p>
                            @endif
                        @else
                            <p class="text-center">No payment methods available for this store</p>
                        @endif
                    </div>

                </div>
                {{-- right side of the parent div --}}
                <div class="order-summary w-100 w-md-50 h-auto p-md-3 p-2 mt-2 me-2">
                    <h2>Order Summary</h2>
                    {{-- items part --}}
                    @foreach ($products as $item)
                        <div class="w-100  d-flex align-items-center justify-content-center"
                            style="height: 116px;border-bottom:1px solid rgb(219, 214, 214)">
                            <div class="w-25 h-100  d-flex align-items-center justify-content-center ">
                                <img src="{{ asset($item->image ?? 'assets/home/stones.jpg') }}" alt=""
                                    width="100%" height="60%" class="img-fluid rounded-2">
                            </div>
                            <div class="w-75 h-100  d-flex align-items-center justify-content-center">
                                <div class="w-70 h-100  p-2 d-flex flex-column justify-content-center">
                                    <p class="nunito p-0 m-0 text-truncate">
                                        {{ $item?->weight . ' (ct) ' . $item?->name }}
                                    </p>
                                    <p class="nunito-regular text-muted p-0 m-0">{{ $store->name ?? '' }}</p>
                                </div>
                                <div class="w-30 h-100  p-2 d-flex align-items-center justify-content-center">
                                    <p class="nunito">
                                        ${{ $item->productPricing->buy_it_now_price ?? 'NaN' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- second part --}}
                    <div class="w-100 d-flex justify-content-start align-items-center gap-2 mt-3 pb-3"
                        style="border-bottom:1px solid rgb(219, 214, 214)">
                        <i class="bi bi-shield-check" style="font-size: 21px"></i>
                        <span id="insurance-info">
                            @if ($insurance)
                                Postal insurance included
                            @else
                                Postal insurance not included
                            @endif
                        </span>
                    </div>
                    {{-- third part --}}
                    <div class="w-100">
                        <table class="table w-100">
                            <tr class="w-100">
                                <td class="ninito-regular key w-70">SubTotal</td>
                                <td class="nunito-regular value w-30 ">$<span
                                        id="subTotal">{{ $order->total ?? (number_format($total, 2) ?? 'NaN') }}</span>
                                </td>
                            </tr>
                            {{-- <tr class="w-100">
                                <td class="nunito-regular key w-70">Discount</td>
                                <td class="nunito-regular value w-30 ">$0</td>
                            </tr> --}}
                            <tr class="w-100">
                                <td class="nunito-regular key w-70">Shipping</td>
                                <td class="nunito-regular value w-30 ">$<span
                                        id="shipping">{{ $order->shipping_cost ?? '0' }}</span>
                                </td>
                            </tr>
                            <tr class="w-100">
                                <td class="nunito-regular key w-70"> Insurance</td>
                                <td class="nunito-regular value w-30">$<span
                                        id="insurance">{{ $insurance ?? '0' }}</span>
                                </td>
                            </tr>
                            <tr class="w-100" style="border-bottom: 3px solid rgb(207, 200, 200)">
                                <td class="nunito-regular key w-70">Taxes</td>
                                <td class="nunito-regular value w-30 ">$<span id="taxes">0</span></td>
                            </tr>
                            <tr class="w-100">
                                <td class="key w-70 fw-bold">Total</td>
                                <td class="value w-30  fw-bold">$<span id="total"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Loader element -->
            <div id="loader" class="loader" style="display: none;">
                <!-- You can add a loading spinner or any other visual indicator here -->
                <div class="spinner"></div>
                <p>Please wait while your transaction is processing...</p>
            </div>
    </div>
@else
    <p class="text-center my-auto"><small><i class="bi bi-info-circle"></i></small> Sorry! You can't buy this item,
        because you have been blocked by this seller.</p>
    @endif
    </div>
@endsection

@section('js')
    <script>
        getTotal();

        setShippingPrice();

        showPaymentMethod();

        // Get total
        function getTotal() {
            var subTotal = $('#subTotal').text();
            subTotal = parseFloat(subTotal);
            var shipping = $('#shipping').text();
            shipping = parseFloat(shipping);
            var insurance = $('#insurance').text();
            insurance = parseFloat(insurance);
            // var taxes = $('#taxes').text();
            // taxes = taxes.replace('$', '');
            // taxes = parseFloat(taxes);
            var total = subTotal + shipping + insurance;
            total = total.toFixed(2);
            $('#total').text(total);
        }

        // Add insurance
        function addInsurance() {
            var insurance = $('#insuranceCheck').val();
            var total = $('#total').text(); // Get total
            total = parseFloat(total); // Convert to double
            // Check if insurance is checked
            if ($('#insuranceCheck').is(':checked')) {
                total = total + parseFloat(insurance); // Add insurance
                total = total.toFixed(2);
                $('#total').text(total); // Update total
                $('#insurance-info').text('Postal insurance included');
                $('#insurance').text(insurance);
            } else {
                total = total - parseFloat(insurance); // Remove insurance
                total = total.toFixed(2);
                $('#total').text(total); // Update total
                $('#insurance-info').text('Postal insurance not included');
                $('#insurance').text(0);
            }

        }

        // Change shipping provider
        function changeShippingProvider(store_id) {
            let spId = $('#shipping_provider').val();

            const url2 =
                `{{ url('api/store/${store_id}/shipping-providers/${spId}') }}`;
            $.ajax({
                url: url2,
                type: 'GET',
                success: function(response) {
                    // Handle the success response
                    console.log(response);

                    var oldShippingPrice = parseFloat($('#shipping').text());
                    var newShippingPrice = parseFloat(response.shippingPreference
                        .domestic_shipping_fee_per_item);
                    var totalElement = $('#total');
                    var total = parseFloat(totalElement.text()) + newShippingPrice;
                    total = total - oldShippingPrice;
                    totalElement.text(total.toFixed(2)); // Update total with two decimal places
                    $('#shipping').text(newShippingPrice.toFixed(2));
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            })
        }

        function setShippingPrice() {
            changeShippingProvider({{ $store->id }});
        }


        // Display payment method based on selected_payment value
        $('input[name=selected_payment').change(function() {
            if ($(this).val() === 'credit_card') {
                // Hide PayPal button
                $('#paypal-button-container').addClass('d-none');
                // Display credit card form
                $('#stripeForm').removeClass('d-none');
            } else if ($(this).val() === 'paypal') {
                // Display PayPal button
                $('#paypal-button-container').removeClass('d-none');
                // Hide credit card form
                $('#stripeForm').addClass('d-none');
            }
        });

        function showPaymentMethod() {
            const selected = $('input:radio[name=selected_payment]:checked').val();
            if (selected === 'credit_card') {
                $('#stripeForm').removeClass('d-none');
                $('#paypal-button-container').addClass('d-none');
            } else if (selected === 'paypal') {
                $('#paypal-button-container').removeClass('d-none');
            }
        }

        $(document).ready(function() {
            $('#shipToAnotherBtn').click(function() {
                $('#shipping-details-inputs').toggleClass('d-none');
            });

            $('#update_shipping_btn').click(function() {
                $('#shipping-details-inputs').toggleClass('d-none');

                // Update shipping inputs in stripe form
                const shippingInputs = $('#shipping-details-inputs').find('input');
                shippingInputs.each(function() {
                    const name = 'shipping_' + $(this).attr('name');
                    const value = $(this).val();

                    $('#stripeForm').find(`[name="${name}"]`).val(value);
                })

                $('#shipping_name_display').text($('#shipping_name').val());
                $('#shipping_email_display').text($('#shipping_email').val());
                $('#shipping_address_display').text($('#shipping_address').val());
            })
        })
    </script>

    @if ($store->paymentMethods)
        <!-- Paypal Scripts -->
        @if ($store->paymentMethods->where('name', 'paypal')->where('status', 1)->first())
        @php
            $paymentMethod = $store->paymentMethods()->where('name', 'paypal')->where('status', 1)->first();
        @endphp
            <!-- Include the PayPal JavaScript SDK -->
            {{-- <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_SANDBOX_CLIENT_ID') }}&currency=USD"></script> --}}
            <script src="https://www.paypal.com/sdk/js?client-id={{ $paymentMethod->key }}&currency=USD"></script>
            <script>
                var productIds = "{{ $products->pluck('id')->implode(',') }}";
                // Render the PayPal button into #paypal-button-container
                paypal.Buttons({

                    // Call your server to set up the transaction
                    createOrder: function(data, actions) {
                        console.log(productIds);
                        return fetch('{{ url('/api/paypal/order/create') }}', {
                            method: 'post',
                            body: JSON.stringify({
                                'product_ids': productIds,
                                // 'product_id': '{{ $product->id }}',
                                'user_id': "{{ auth()->user()->id }}",
                                'store_id': "{{ $store->id }}",
                                'insurance': $('#insuranceCheck').is(':checked'),
                                'shipping_provider_id': $('#shipping_provider').val(),
                                'order_id': "{{ $order->id ?? '' }}",
                            })
                        }).then(function(res) {
                            //res.json();
                            return res.json();
                        }).then(function(orderData) {
                            console.log(orderData);
                            if (orderData.message) {
                                if (orderData.message === 'The product is already sold.') {
                                    toastr.error('Sorry! the item is already sold.')
                                    // removeItem({{ $product->id }});
                                    window.location.href = "{{ route('cart') }}";
                                }
                            }
                            return orderData.id;
                        });
                    },

                    // Call your server to finalize the transaction
                    onApprove: function(data, actions) {
                        return fetch('{{ url('/api/paypal/order/capture') }}', {
                            method: 'post',
                            body: JSON.stringify({
                                'orderId': data.orderID,
                                'product_ids': productIds,
                                // 'product_id': "{{ $product->id }}",
                                'user_id': "{{ auth()->user()->id }}",
                                'store_id': "{{ $store->id }}",
                                'order_id': "{{ $order->id ?? '' }}",
                                'insurance': $('#insuranceCheck').is(':checked'),
                                'shipping_address': $('#address').val(),
                                'shipping_city': $('#city').val(),
                                'shipping_country': $('#country').val(),
                                'shipping_state': $('#state').val(),
                                'shipping_postal_code': $('#postal_code').val(),
                                'shipping_name': $('#name').val(),
                                'shipping_email': $('#email').val(),
                                'shipping_phone': '',
                                'shipping_provider_id': $('#shipping_provider').val(),

                            })
                        }).then(function(res) {
                            // Handle the response here
                            return res.json();
                        }).then(function(orderData) {
                            // Successful capture! For demo purposes:
                            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                            if (orderData.status === 'COMPLETED') {
                                // remove item from cart
                                // ......

                                // return to thank you page
                                window.location.href = "{{ route('buyer.orders.thanks') }}";
                            }
                        }).catch(function(error) {
                            // Handle errors here
                            console.error('Error capturing payment:', error);
                        });
                    },


                    onCancel: function(data, actions) {
                        console.log('Payment cancelled...');
                    },

                    // Error handler for when there is an error
                    onError: function(err) {
                        console.log(err);
                    }

                }).render('#paypal-button-container');
            </script>
        @endif

        <!-- Stripe Scripts -->
        @if ($store->paymentMethods->where('name', 'stripe')->where('status', 1)->first())
            @php
                $paymentMethod = $store->paymentMethods()->where('name', 'stripe')->where('status', 1)->first();
            @endphp
            <!-- Include Stripe.js library -->
            <script src="https://js.stripe.com/v3/"></script>
            <script>
                // let stripe = Stripe('{{ env('STRIPE_KEY') }}')
                let stripe = Stripe('{{ $paymentMethod->key }}')

                const elements = stripe.elements()
                const style = {
                    base: {
                        fontSize: '16px',
                        color: '#000000',
                        lineHeight: '40px',
                        backgroundColor: '#f3f3f3',
                        // '::placeholder': {
                        //     color: '#000000'
                        // },
                        // iconColor: '#f3f3f3'
                    }
                };

                const cardElement = elements.create('card', {
                    style: style,
                    hidePostalCode: true,
                });

                // const cardElement = stripe.elements().create('card');
                const cardForm = document.getElementById('card-form')
                const cardName = document.getElementById('card-name')
                cardElement.mount('#card')
                cardForm.addEventListener('submit', async (e) => {
                    e.preventDefault()

                    // display loader
                    $('.loader').css('display', 'flex');

                    const {
                        paymentMethod,
                        error
                    } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                        billing_details: {
                            name: cardName.value
                        }
                    })
                    if (error) {
                        console.log(error)
                        $('.loader').css('display', 'none');
                        toastr.error(error.message);
                    } else {
                        // set values of shipping_cost and insurance cost
                        // $('#shipping_cost').val(parseFloat($('#shipping').text()));
                        $('#shipping_provider_id').val($('#shipping_provider').val());
                        $('#insurance_cost').val(parseFloat($('#insurance').text()));

                        let input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'payment_method');
                        input.setAttribute('value', paymentMethod.id);
                        cardForm.appendChild(input);
                        cardForm.submit()
                    }
                })
            </script>
        @endif
    @endif
@endsection
