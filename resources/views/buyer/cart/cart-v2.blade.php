@extends('layouts.buyer', ['title' => 'Cart'])

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/cart.css') }}">
@endsection

@section('content')
    <h2 class="h-5">Shopping Cart</h2>
    <div id="cart" class="w-100  d-flex flex-column gap-5" style="background-color:#FFF">
        @forelse ($productsByStore as $storeId => $items)
            @php
                $store = App\Models\Store::find($storeId);
            @endphp

            <div class="w-100 d-flex flex-column flex-md-row align-items-start cart-item" data-item-id="{{ $store->id }}">
                <div class="w-100  w-md-50 h-auto p-3">
                    <div class="w-100 p-2 d-flex align-items-center gap-2" style="height: 30px;background-color:#FBFBFB">
                        <img src="{{ asset($store->image ?? 'assets/home/stones.jpg') }}" alt="" width="40px"
                            height="25px" style="border-radius:4px">
                        <p class="p-0 m-0 store-name">{{ $store->name }}</p>
                    </div>
                    {{-- the card start from here --}}
                    @foreach ($items as $item)
                        <div class="w-100 mt-3 d-flex align-items-center shadow-sm rounded-3 item-detail-card"
                            style="height: 116px" data-item-id="{{ $item->id }}">
                            <div class="h-100" style="width: 5%;">
                                <input type="checkbox" class="form-check-input" id="cartItem{{ $item->id }}" checked>
                            </div>
                            <img src="{{ asset($item->image ?? 'assets/buyer-assets/coming-soon.avif') }}" alt=""
                                width="30%" height="100%" style="object-fit:cover;">
                            <div class="h-100" style="width: 60%;">
                                <div class="h-50 w-100 d-flex align-items-center justify-content-start pt-3">
                                    <p class="text-truncate item-name ps-1">{{ $item->weight }}(ct) {{ $item->name }}</p>
                                </div>
                                <div class="h-50 w-100 bg d-flex align-items-center">
                                    <div class="w-65 h-100 ps-1 pt-2">
                                        <p class="p-0 m-0 fw-bold">{{ $item->status === 'sold' ? 'SOLD' : 'In Stock' }}</p>
                                        <p class="p-0 m-0 auction-id">Item ID: GM{{ $item->id }}</p>
                                    </div>
                                    <div class="w-35 h-100  d-flex flex-column align-items-center justify-content-center">
                                        <p class="p-0 m-0 auction-price">
                                            ${{ $item->productPricing->buy_it_now_price ?? 'NaN' }}</p>
                                        <small>
                                            {{-- <button class="m-0 p-0 border-0 bg-transparent text-primary" type="button"
                                                onclick="removeItem({{ $item->id }})">Remove</button> --}}
                                            <a href="{{ route('buyer.cart.item.delete', $item->id) }}"
                                                class="border-0 bg-transparent text-primary">Remove</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- the card ends here --}}
                </div>

                <div class="w-100 w-md-50 h-auto px-3">
                    <form id="checkoutForm{{ $store->id }}" action="{{ route('buyer.checkout.create') }}"
                        method="post">
                        @csrf
                        <div class="w-100 mt-2">
                            <label class="mb-2">Shipping Providers</label>
                            <div class="w-100" style="height:35px">
                                <select class="w-100 text-uppercase d-block h-100" name="shipping_provider"
                                    id="shipping_provider{{ $store->id }}"
                                    onchange="changeShippingProvider({{ $store->id }})">
                                    @foreach ($store->shippings as $shippingProvider)
                                        <option value="{{ $shippingProvider->id }}">
                                            {{ strtoupper($shippingProvider->shipping_provider) . ' - $' . $shippingProvider->domestic_shipping_fee_per_item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <small class="text-danger">this is for error</small> --}}
                        </div>
                        <div class="w-100 mt-2">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input" id="insurance{{ $store->id }}"
                                    name="insurance" onclick="addInsurance({{ $store->id }})"
                                    value="{{ $store->insurance ?? 0 }}">
                                <label for="insurance{{ $store->id }}">Add Postal Insurance
                                    (${{ $store->insurance ?? 0 }})
                                </label>
                            </div>
                        </div>
                    </form>
                    {{-- payement methods here --}}
                    <div class="payments w-100  rounded-3 mt-3 p-md-4 p-2 shadow-sm">
                        <div class="w-100">
                            <p class="fw-bold">Payment Methods</p>
                        </div>
                        <div class="w-100 d-flex flex-wrap align-items-center my-4">
                            {{-- if these images are dynamic then this div has to be dynamic which has payment-method-image class --}}
                            @if ($store->paymentMethods->count() > 0)
                                @foreach ($store->paymentMethods as $paymentMethod)
                                    @if ($paymentMethod->name == 'paypal' && $paymentMethod->status == 1)
                                        <div class="payment-method-image" style="height: 40px;width:50%">
                                            <img src="{{ asset('assets/buyer-assets/paypal.png') }}" alt=""
                                                width="100%" height="100%">
                                        </div>
                                    @endif
                                    @if ($paymentMethod->name == 'stripe' && $paymentMethod->status == 1)
                                        <div class="payment-method-image" style="height: 40px;width:50%">
                                            <img src="{{ asset('assets/buyer-assets/visa.png') }}" alt=""
                                                width="100%" height="100%">
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                No payment methods available for this store.
                            @endif
                        </div>
                        <div class="w-100 mt-3">
                            <table class="table w-100">
                                <tr class="w-100">
                                    <td class="key w-70">Item(s) Total</td>
                                    <td class="value w-30 fw-bold" id="itemsTotal{{ $store->id }}">$0</td>
                                </tr>
                                <tr class="w-100">
                                    <td class="key w-70">Shipping</td>
                                    <td class="value w-30  fw-bold" id="shipping_price{{ $store->id }}">$0</td>
                                </tr>
                                <tr class="w-100" style="border-bottom: 3px solid rgb(179, 169, 169)">
                                    <td class="key w-70">Postal Insurance</td>
                                    <td class="value w-30 fw-bold" id="insurance_value{{ $store->id }}">
                                        $0</td>
                                </tr>

                                <tr class="w-100">
                                    <td class="key w-70 fw-bold">Total</td>
                                    <td id="total{{ $store->id }}" class="value w-30  fw-bold">$0</td>
                                </tr>
                                <tr class="w-100">
                                    <td class="w-100 bg-transparent py-4" colspan="2"
                                        id="checkoutContainer{{ $store->id }}">
                                        <button type="button" class="w-100"
                                            onclick="checkoutFormSubmit({{ $store->id }})">Checkout</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="w-100 p-3">
                <div class="w-100 position-relative rounded-3"
                    style="background: #FBFBFB 0% 0% no-repeat padding-box;height:350px">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <img src="{{ asset('assets/home/folder.png') }}" alt="" width="70px" height="70px"
                            class="img-fluid" style="filter: invert(1)">
                        <p class="nunito mt-3">No Items</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@section('js')
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            // Calculate item total and shipping on page load
            calculateTotals();

            // Attach event listeners to checkboxes
            document.querySelectorAll('.item-detail-card input[type="checkbox"]').forEach((checkbox) => {
                checkbox.addEventListener('change', updateTotals);
            });
        });

        // update items total on checkbox check uncheck
        function updateTotals() {
            // Flag to check if at least one checkbox is checked
            let atLeastOneChecked = false;

            // Iterate over each store's items
            document.querySelectorAll('.cart-item').forEach((cartItem) => {
                let storeId = cartItem.getAttribute('data-item-id');
                let itemTotal = 0;

                // Calculate item total for checked items
                cartItem.querySelectorAll('.item-detail-card input[type="checkbox"]:checked').forEach((
                    checkbox) => {
                    let itemPrice = parseFloat(checkbox.closest('.item-detail-card').querySelector(
                        '.auction-price').textContent.replace('$', ''));
                    itemTotal += itemPrice;
                    atLeastOneChecked = true; // Set the flag to true if at least one checkbox is checked
                });

                // Update item total for the current store
                document.querySelector(`#itemsTotal${storeId}`).textContent = `$${itemTotal.toFixed(2)}`;
                document.querySelector(`#total${storeId}`).textContent = `$${itemTotal.toFixed(2)}`;

                // Update total if at least one checkbox is checked
                if (atLeastOneChecked) {

                    let shippingPrice = parseFloat($('#shipping_price' + storeId).text().replace('$', ''));
                    let insurance = parseFloat($('#insurance_value' + storeId).text().replace('$', ''));
                    let totalElement = $('#total' + storeId);
                    let total = itemTotal + shippingPrice + insurance;
                    totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places

                    let checkoutContainer = $('#checkoutContainer' + storeId);
                    // check if checkoutContainer has a p tag
                    if (checkoutContainer.find('.alert').length > 0) {
                        $('#checkoutContainer' + storeId).text('');
                        $('#checkoutContainer' + storeId).append(
                            `<button type="button" class="w-100"
                                            onclick="checkoutFormSubmit(${storeId})">Checkout</button>`);
                    }
                } else {
                    // toastr.error('Please select at least one item.');
                    $('#checkoutContainer' + storeId).text('');
                    $('#checkoutContainer' + storeId).append(
                        `<div class="alert alert-danger"><small>Please select at least one item for checkout.</small></div>`
                    );
                }
            });
        }


        function calculateTotals() {
            // Iterate over each store's items
            document.querySelectorAll('.cart-item').forEach((cartItem) => {
                let storeId = cartItem.getAttribute('data-item-id');
                let itemTotal = 0;

                // Calculate item total for checked items
                cartItem.querySelectorAll('.item-detail-card input[type="checkbox"]:checked').forEach((
                    checkbox) => {
                    let itemPrice = parseFloat(checkbox.closest('.item-detail-card').querySelector(
                        '.auction-price').textContent.replace('$', ''));
                    itemTotal += itemPrice;
                });

                document.querySelector(`#itemsTotal${storeId}`).textContent = `$${itemTotal.toFixed(2)}`;
                document.querySelector(`#total${storeId}`).textContent = `$${itemTotal.toFixed(2)}`;

                getShippingPrice(storeId, function(error, shippingPrice) {
                    if (error) {
                        // Handle error
                        console.error("Error occurred while fetching shipping price:", error);
                    } else {
                        // Use the shippingPrice value here
                        var oldShippingPrice = parseFloat($('#shipping_price' + storeId).text().replace('$',
                            ''));
                        var totalElement = $('#total' + storeId);
                        var total = parseFloat(totalElement.text().replace('$', '')) + shippingPrice;
                        total = total - oldShippingPrice;
                        totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
                        $('#shipping_price' + storeId).text('$' + shippingPrice.toFixed(2));
                    }
                });
            });
        }

        // Change shipping provider
        function changeShippingProvider(storeId) {
            // let spId = $('#shipping_provider' + id).val();
            // // console.log(spId);
            // const url2 =
            //     `{{ url('api/store/${id}/shipping-providers/${spId}') }}`;
            // $.ajax({
            //     url: url2,
            //     type: 'GET',
            //     success: function(response) {
            //         // Handle the success response
            //         // console.log(response);

            //         var oldShippingPrice = parseFloat($('#shipping_price' + id).text().replace('$', ''));
            //         var newShippingPrice = parseFloat(response.shippingPreference
            //             .domestic_shipping_fee_per_item);
            //         var totalElement = $('#total' + id);
            //         var total = parseFloat(totalElement.text().replace('$', '')) + newShippingPrice;
            //         total = total - oldShippingPrice;
            //         totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
            //         $('#shipping_price' + id).text('$' + newShippingPrice.toFixed(2));
            //     },
            //     error: function(xhr, status, error) {
            //         // Handle errors
            //         console.error(xhr.responseText);
            //     }
            // })

            getShippingPrice(storeId, function(error, shippingPrice) {
                if (error) {
                    // Handle error
                    console.error("Error occurred while fetching shipping price:", error);
                } else {
                    // Use the shippingPrice value here
                    var oldShippingPrice = parseFloat($('#shipping_price' + storeId).text().replace('$', ''));
                    var totalElement = $('#total' + storeId);
                    var total = parseFloat(totalElement.text().replace('$', '')) + shippingPrice;
                    total = total - oldShippingPrice;
                    totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
                    $('#shipping_price' + storeId).text('$' + shippingPrice.toFixed(2));
                }
            });
        }

        // Add insurance
        function addInsurance(storeId) {
            var insurance = $('#insurance' + storeId).val();
            var totalElement = $('#total' + storeId);
            var total = parseFloat(totalElement.text().replace('$', '')); // Get and convert total

            // Check if insurance is checked
            if ($('#insurance' + storeId).is(':checked')) {
                total += parseFloat(insurance); // Add insurance
                totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
                $('#insurance_value' + storeId).text('$' + insurance);
            } else {
                total -= parseFloat(insurance); // Remove insurance
                totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
                $('#insurance_value' + storeId).text('$' + 0);
            }
        }

        function getShippingPrice(storeId, callback) {
            let spId = $('#shipping_provider' + storeId).val();
            const url2 = `{{ url('api/store/${storeId}/shipping-providers/${spId}') }}`;
            let newShippingPrice = 0;

            $.ajax({
                url: url2,
                type: 'GET',
                success: function(response) {
                    // Handle the success response
                    // console.log(response);
                    shippingPrice = parseFloat(response.shippingPreference.domestic_shipping_fee_per_item);
                    callback(null, shippingPrice); // Call the callback with the shipping price
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                    callback(error, null); // Call the callback with an error
                }
            })
        }

        function checkoutFormSubmit(storeId) {
            // Array to store the IDs of checked items
            let checkedItemIds = [];

            // Find all checked checkboxes for the given storeId
            let checkedCheckboxes = document.querySelectorAll(
                `.cart-item[data-item-id="${storeId}"] .item-detail-card input[type="checkbox"]:checked`);

            // Iterate over checked checkboxes and collect their corresponding item IDs
            checkedCheckboxes.forEach((checkbox) => {
                let itemId = checkbox.closest('.item-detail-card').getAttribute('data-item-id');
                checkedItemIds.push(itemId);
            });

            // Get the checkout form
            let checkoutForm = document.getElementById('checkoutForm' + storeId);

            // Loop through the checked item IDs and append them to hidden fields in the form
            checkedItemIds.forEach((itemId) => {
                let hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = `checkedItems[]`; // Use array notation to group item IDs by store
                hiddenField.value = itemId;
                checkoutForm.appendChild(hiddenField);
            });

            // Submit the form
            checkoutForm.submit();
        }
    </script>
@endsection
