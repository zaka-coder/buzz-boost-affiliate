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
            $store = $product->store;
            // check if the user is blocked by the store
            if (auth()->user()) {
                $isBlocked = $store->blocked_users->contains(auth()->user()->id);
            } else {
                $isBlocked = false;
            }
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
                                <td class="shipping-info w-50 ">
                                    {{ $shippingAddress->customer_name ?? auth()->user()->name }}
                                </td>
                                <td class="shipping-info w-50">
                                    {{ $shippingAddress->address ?? auth()->user()->profile->address }}</td>
                            </tr>
                            <tr class="w-100" style="border-bottom:1px solid #FFF">
                                <td class="shipping-info w-50">{{ $shippingAddress->email ?? auth()->user()->email }}</td>
                                <td class="shipping-info w-50 "><a
                                        href="{{ route('buyer.shipping.address.create', $product->id) }}">Ship to Another
                                        Address</a></td>
                            </tr>
                            <tr class="w-100" style="border-bottom:1px solid #FFF">
                                <td class="shipping-info w-50">
                                    {{ $shippingAddress->phone ?? auth()->user()->profile->contact }}
                                </td>
                                <td class="shipping-info w-50 "></td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-100 p-md-2">
                        <h2>Delivery Method</h2>
                        <div class="w-100 mt-2">
                            <label class="mb-2">Shipping Providers</label>
                            <div class="w-100" style="height:35px">
                                <select class="w-100  d-block h-100" name="shipping_provider" id="shipping_provider"
                                    onchange="changeShippingProvider({{ $store->id }})">
                                    @if ($product->productShipping->shipping_type != 'normal')
                                        <option value="0" selected>Free Shipping</option>
                                    @else
                                        @foreach ($store->shippings as $shippingProvider)
                                            <option value="{{ $shippingProvider->id }}">
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
                                {{ $product->productShipping->shipping_type == 'normal' ? 'Normal Shipping - Tracked' : 'Free Shipping' }}
                                <i class="bi bi-check-circle-fill"></i>
                            </h2>
                        </div>
                        <div class="w-100 mt-2">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" id="insuranceCheck" name="insurance" class="form-check-input"
                                    value="{{ $store->insurance }}" onchange="addInsurance()">
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
                                        <input type="hidden" name="order_id" id="order_id"
                                            value="{{ $order->id ?? '' }}">
                                        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                                        <input type="hidden" name="name" id="card-name"
                                            value="{{ Auth::user()->name }}">
                                        <input type="hidden" name="email" id="email"
                                            value="{{ Auth::user()->email }}">
                                        <input type="hidden" name="product_id[]" id="product_id"
                                            value="{{ $product->id }}">
                                        <input type="hidden" name="store_id" id="store_id" value="{{ $store->id }}">
                                        <input type="hidden" name="shipping_name" id=""
                                            value="{{ $shippingAddress->customer_name ?? auth()->user()->name }}">
                                        <input type="hidden" name="shipping_email" id=""
                                            value="{{ $shippingAddress->email ?? auth()->user()->email }}">
                                        <input type="hidden" name="shipping_phone" id=""
                                            value="{{ $shippingAddress->phone ?? auth()->user()->profile->contact }}">
                                        <input type="hidden" name="shipping_address" id=""
                                            value="{{ $shippingAddress->address ?? auth()->user()->profile->address }}">
                                        <input type="hidden" name="shipping_city" id=""
                                            value="{{ $shippingAddress->city ?? auth()->user()->profile->city }}">
                                        <input type="hidden" name="shipping_country" id=""
                                            value="{{ $shippingAddress->country ?? auth()->user()->profile->country }}">
                                        <input type="hidden" name="shipping_state" id=""
                                            value="{{ $shippingAddress->state ?? auth()->user()->profile->state }}">
                                        <input type="hidden" name="shipping_postal_code" id=""
                                            value="{{ $shippingAddress->postal_code ?? auth()->user()->profile->zip }}">
                                        {{-- <input type="hidden" name="shipping_cost" id="shipping_cost" value="0"> --}}
                                        <input type="hidden" name="shipping_provider_id" id="shipping_provider_id"
                                            value="0">
                                        <input type="hidden" name="insurance" id="insurance_cost" value="0">

                                        <div class="my-5 w-100">
                                            <label for="card"
                                                class="mb-4 text-dark fw-bold">Card
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

                    <!-- Pay Later Button - should be removed later -->
                    {{-- <div class="w-90 mx-3 my-5 rounded-2 d-flex align-items-center justify-content-center gap-2">
                        <button type="button" class="btn btn-primary w-100" onclick="saveOrder()"
                            style="height: 45px">Place Order and Pay
                            Later</button>
                    </div> --}}

                </div>
                {{-- right side of the parent div --}}
                <div class="order-summary w-100 w-md-50 h-auto p-md-3 p-2 mt-2 me-2">
                    <h2>Orders Summary</h2>
                    {{-- first part --}}
                    <div class="w-100  d-flex align-items-center justify-content-center"
                        style="height: 116px;border-bottom:1px solid rgb(219, 214, 214)">
                        <div class="w-25 h-100  d-flex align-items-center justify-content-center ">
                            <img src="{{ asset($product->image ?? 'assets/home/stones.jpg') }}" alt=""
                                width="100%" height="60%" class="img-fluid rounded-2">
                        </div>
                        <div class="w-75 h-100  d-flex align-items-center justify-content-center">
                            <div class="w-70 h-100  p-2 d-flex flex-column justify-content-center">
                                <p class="nunito p-0 m-0 text-truncate">
                                    {{ $product?->weight . ' (ct)' . $product?->name }}
                                </p>
                                <p class="nunito-regular text-muted p-0 m-0">{{ $store->name ?? '' }}</p>
                            </div>
                            <div class="w-30 h-100  p-2 d-flex align-items-center justify-content-center">
                                <p class="nunito">
                                    ${{ $order->total ?? ($product->productPricing->buy_it_now_price ?? 'NaN') }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- second part --}}
                    <div class="w-100 d-flex justify-content-start align-items-center gap-2 mt-3 pb-3"
                        style="border-bottom:1px solid rgb(219, 214, 214)">
                        <i class="bi bi-shield-check" style="font-size: 21px"></i>
                        <span id="insurance-info">Postal insurance not included</span>
                    </div>
                    {{-- third part --}}
                    <div class="w-100">
                        <table class="table w-100">
                            <tr class="w-100">
                                <td class="ninito-regular key w-70">SubTotal</td>
                                <td class="nunito-regular value w-30 ">$<span
                                        id="subTotal">{{ $order->total ?? ($product->productPricing->buy_it_now_price ?? '0') }}</span>
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
                                <td class="nunito-regular value w-30">$<span id="insurance">0</span>
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
        // check for insurance in localStorage gems_harbor_cart
        let cartItems = JSON.parse(localStorage.getItem('gems_harbor_cart'));
        // Find the specific item in the cartItems array
        const specificItem = cartItems.find(item => item.id === {{ $product->id }});

        if (specificItem && specificItem.insurance) {
            $('#insuranceCheck').prop('checked', true);
            $('#insurance').text({{ $store->insurance }}); // Set insurance
            $('#insurance-info').text('Postal insurance included');
        }

        if (specificItem && specificItem.shipping_price) {
            $('#shipping').text(specificItem.shipping_price); // Set shipping price
        }

        if (specificItem && specificItem.selected_shipping_provider) {
            $('#shipping_provider').val(specificItem.selected_shipping_provider); // Set shipping provider
        }

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
                    $('#shipping').text(newShippingPrice);
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


        // Save order to database using ajax and remove item from cart
        function saveOrder() {
            var orderData = {
                _token: '{{ csrf_token() }}',
                product_id: {{ $product->id }},
                store_id: {{ $store->id }},
                insurance: $('#insuranceCheck').is(':checked'),
                shipping_address: '{{ $shippingAddress->address ?? auth()->user()->profile->address }}',
                shipping_city: '{{ $shippingAddress->city ?? auth()->user()->profile->city }}',
                shipping_country: '{{ $shippingAddress->country ?? auth()->user()->profile->country }}',
                shipping_state: '{{ $shippingAddress->state ?? auth()->user()->profile->state }}',
                shipping_postal_code: '{{ $shippingAddress->postal_code ?? auth()->user()->profile->zip }}',
                shipping_name: '{{ $shippingAddress->customer_name ?? auth()->user()->name }}',
                shipping_email: '{{ $shippingAddress->email ?? auth()->user()->email }}',
                shipping_phone: '{{ $shippingAddress->phone ?? auth()->user()->profile->contact }}',
                shipping_cost: parseFloat($('#shipping').text()),
            };

            $.ajax({
                type: "POST",
                url: "{{ route('buyer.orders.store') }}",
                data: orderData,
                success: function(response) {
                    console.log(response);
                    // Handle the success response from the server
                    if (response.success) {
                        // Remove item from cart
                        removeItem({{ $product->id }});

                        // Redirect to thank you page
                        window.location.href = "{{ route('buyer.orders.thanks') }}";

                        // show toastr message
                        toastr.success(response.message);
                        // console.log(response.message);

                    } else {
                        // Handle the error response from the server
                        if (response.message == "Order already exists for this product") {
                            // removeItem({{ $product->id }});
                            // window.location.href = "/";
                        }
                        toastr.error(response.message);
                    }

                },
                error: function(error) {
                    // Handle the error response from the server
                    console.error("Error saving order:", error.responseText);

                    var errorResponse = JSON.parse(error.responseText);
                    // show toastr message
                    toastr.error(errorResponse.message);
                }
            });
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
    </script>

    @if ($store->paymentMethods)
        <!-- Paypal Scripts -->
        @if ($store->paymentMethods->where('name', 'paypal')->where('status', 1)->first())
            @php
                $paymentMethod = $store->paymentMethods()->where('name', 'paypal')->where('status', 1)->first();
            @endphp
            <!-- Include the PayPal JavaScript SDK -->
            <script src="https://www.paypal.com/sdk/js?client-id={{ $paymentMethod->key }}&currency=USD"></script>
            {{-- <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_SANDBOX_CLIENT_ID') }}&currency=USD"></script> --}}
            <script>
                // Render the PayPal button into #paypal-button-container
                paypal.Buttons({

                    // Call your server to set up the transaction
                    createOrder: function(data, actions) {
                        return fetch('{{ url('/api/paypal/order/create') }}', {
                            method: 'post',
                            body: JSON.stringify({
                                'product_ids': '{{ $product->id }}',
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
                                    // window.location.href = "{{ route('cart') }}";
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
                                'product_ids': "{{ $product->id }}",
                                // 'product_id': "{{ $product->id }}",
                                'user_id': "{{ auth()->user()->id }}",
                                'store_id': "{{ $store->id }}",
                                'order_id': "{{ $order->id ?? '' }}",
                                'insurance': $('#insuranceCheck').is(':checked'),
                                'shipping_address': '{{ $shippingAddress->address ?? auth()->user()->profile->address }}',
                                'shipping_city': '{{ $shippingAddress->city ?? auth()->user()->profile->city }}',
                                'shipping_country': '{{ $shippingAddress->country ?? auth()->user()->profile->country }}',
                                'shipping_state': '{{ $shippingAddress->state ?? auth()->user()->profile->state }}',
                                'shipping_postal_code': '{{ $shippingAddress->postal_code ?? auth()->user()->profile->zip }}',
                                'shipping_name': '{{ $shippingAddress->customer_name ?? auth()->user()->name }}',
                                'shipping_email': '{{ $shippingAddress->email ?? auth()->user()->email }}',
                                'shipping_phone': '{{ $shippingAddress->phone ?? auth()->user()->profile->contact }}',
                                // 'shipping_cost': parseFloat($('#shipping').text()),
                                'shipping_provider_id': $('#shipping_provider').val(),

                            })
                        }).then(function(res) {
                            // Handle the response here
                            return res.json();
                        }).then(function(orderData) {
                            // Successful capture! For demo purposes:
                            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                            if (orderData.status === 'COMPLETED') {
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
