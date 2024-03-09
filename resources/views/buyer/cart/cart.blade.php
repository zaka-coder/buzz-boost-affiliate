@extends('layouts.guest', ['title' => 'Cart'])

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/cart.css') }}">
@endsection

@section('content')
    <h2 class="h-5">Shopping Cart</h2>
    <div id="cart" class="w-100  d-flex flex-column gap-5" style="background-color:#FFF">
        {{-- Javascript produced code goes here --}}
    </div>
@endsection

@section('js')
    <script>
        const emptyCartHtml = `<div class="w-100 p-3">
                <div class="w-100 position-relative rounded-3"
                    style="background: #FBFBFB 0% 0% no-repeat padding-box;height:350px">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <img src="{{ asset('assets/home/folder.png') }}" alt="" width="70px" height="70px"
                            class="img-fluid" style="filter: invert(1)">
                        <p class="nunito mt-3">No Items</p>
                    </div>
                </div>
            </div>`;

        // fetch data from local storage
        let cartItems = JSON.parse(localStorage.getItem('gems_harbor_cart'))
        // console.log(cartItems)

        if (cartItems) {
            if (cartItems.length > 0) {
                // loop through cart items
                cartItems.forEach(item => {
                    let sum = 0;
                    // get products from db using cart array and ajax
                    const url = '{{ url('api/cart/products') }}/' + item.id;
                    let part = '/buyer/cart/product/' + item.id + '/checkout';
                    const checkoutUrl = '{{ url('/') }}' + part;
                    // Make an AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);

                            // remove the response.product from cart where response.product.is_sold is true
                            // if (response.product.is_sold) {
                            //     removeItem(item.id);
                            // } else {

                            // }

                            // get sum
                            sum = (sum + parseFloat(response.pricing.buy_it_now_price)).toFixed(2);
                            // populate cart items
                            $('#cart').append(
                                `<div class="w-100 d-flex flex-column flex-md-row align-items-start cart-item" data-item-id="` +
                                response.product.id + `">
                                    <div class="w-100  w-md-50 h-auto p-3">
                                        <div class="w-100 p-2 d-flex align-items-center gap-2" style="height: 30px;background-color:#FBFBFB">
                                            <img src="{{ asset('` + response.store.image + `' ?? 'assets/home/stones.jpg') }}" alt="" width="40px" height="25px"
                                                style="border-radius:4px">
                                            <p class="p-0 m-0 store-name">` + response.store.name + `</p>
                                        </div>
                                        {{-- the card start from here --}}
                                        <div class="w-100 mt-3 d-flex align-items-center shadow-sm rounded-3 item-detail-card"
                                            style="height: 116px">
                                            <img src="{{ asset('`+ response.product.image +`') }}" alt="" width="30%" height="100%"
                                                style="object-fit:cover;">
                                            <div class="h-100 w-70">
                                                <div class="h-50 w-100 d-flex align-items-center justify-content-start pt-3">
                                                    <p class="text-truncate item-name ps-1">` + response.product
                                .weight +
                                `(ct) ` + response.product.name + `</p>
                                                </div>
                                                <div class="h-50 w-100 bg d-flex align-items-center">
                                                    <div class="w-65 h-100 ps-1 pt-2">
                                                        <p class="p-0 m-0 fw-bold">${response.product.status === 'sold' ? 'SOLD' : 'In Stock'}</p>
                                                        <p class="p-0 m-0 auction-id">Item ID: GM` + response.product.id + `</p>
                                                    </div>
                                                    <div class="w-35 h-100  d-flex flex-column align-items-center justify-content-center">
                                                        <p class="p-0 m-0 auction-price">$` + response.pricing
                                .buy_it_now_price + `</p>
                                                        <small><button class="m-0 p-0 border-0 bg-transparent text-primary"
                                                                type="button" onclick="removeItem(` + item.id + `)">Remove</button></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- the card ends here --}}
                                    </div>

                                    <div class="w-100 w-md-50 h-auto px-3">
                                        <div class="w-100 mt-2">
                                            <label class="mb-2">Shipping Providers</label>
                                            <div class="w-100" style="height:35px">
                                                <select class="w-100 text-uppercase d-block h-100" name="shipping_provider${item.id}" id="shipping_provider${item.id}" onchange="changeShippingProvider(${item.id}, ${response.store.id})">
                                                </select>
                                            </div>
                                            {{-- <small class="text-danger">this is for error</small> --}}
                                        </div>
                                        <div class="w-100 mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="checkbox" class="form-check-input" id="insurance` +
                                response
                                .product.id + `" onclick="addInsurance(` + response.product.id +
                                `)" value="` + response.store.insurance + `">
                                                <label for="insurance">Add Postal Insurance ($` + response.store
                                .insurance + `)</label>
                                            </div>
                                        </div>
                                        {{-- payement methods here --}}
                                        <div class="payments w-100  rounded-3 mt-3 p-md-4 p-2 shadow-sm">
                                            <div class="w-100">
                                                <p class="fw-bold">Payment Methods</p>
                                            </div>
                                            <div class="w-100 d-flex flex-wrap align-items-center my-4">
                                                {{-- if these images are dynamic then this div has to be dynamic which has payment-method-image class --}}
                                                ${response.store.payment_methods.length > 0 ?
        response.store.payment_methods.map(payment_method => {
            if(payment_method.name == 'paypal') {
                return `<div class="payment-method-image" style="height: 40px;width:50%">
                            <img src="{{ asset('assets/buyer-assets/paypal.png') }}" alt="" width="100%" height="100%">
                        </div>`
            }
            if(payment_method.name == 'stripe') {
                return `<div class="payment-method-image" style="height: 40px;width:50%">
                            <img src="{{ asset('assets/buyer-assets/visa.png') }}" alt="" width="100%" height="100%">
                        </div>`
            }
        }).join(``) :  `No payment methods available for this store.`}
                                            </div>
                                            <div class="w-100 mt-3">
                                                <table class="table w-100">
                                                    <tr class="w-100">
                                                        <td class="key w-70">Item Total</td>
                                                        <td class="value w-30  fw-bold">$` + response.pricing
                                .buy_it_now_price +
                                `</td>
                                                    </tr>
                                                    <tr class="w-100">
                                                        <td class="key w-70">Shipping</td>
                                                        <td class="value w-30  fw-bold" id="shipping_price${item.id}">$0</td>
                                                    </tr>
                                                    <tr class="w-100" style="border-bottom: 3px solid rgb(179, 169, 169)">
                                                        <td class="key w-70">Postal Insurance</td>
                                                        <td class="value w-30 fw-bold" id="insurance_value` + response
                                .product.id + `">$0</td>
                                                    </tr>

                                                    <tr class="w-100">
                                                        <td class="key w-70 fw-bold">Total</td>
                                                        <td id="total` + response.product.id +
                                `" class="value w-30  fw-bold">$` + sum + `</td>
                                                    </tr>
                                                    <tr class="w-100">
                                                        <td class="w-100 bg-transparent py-4" colspan="2">
                                                            <a href="` + checkoutUrl + `"><button class="w-100">Checkout</button></a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>`
                            );
                            // populate shipping providers dropdown
                            if (response.shippingType === 'normal') {
                                var sh_url =
                                    `{{ url('api/store/${response.store.id}/shipping-providers') }}`;
                                $.ajax({
                                    url: sh_url,
                                    type: 'GET',
                                    success: function(response) {
                                        // Handle the success response
                                        console.log(response.data);
                                        // Clear previous shipping providers
                                        $('#shipping_provider' + item.id).empty();
                                        // Populate shipping providers
                                        $.each(response.shippingPreferences, function(key,
                                            shippingPreference) {
                                            var option = $(
                                                '<option class="text-uppercase" value="' +
                                                shippingPreference.id +
                                                '">' +
                                                shippingPreference
                                                .shipping_provider +
                                                '</option>');

                                            // Add the 'selected' attribute to the first option
                                            if (key === 0) {
                                                option.attr('selected',
                                                    'selected');
                                                // update shipping price
                                                $('#shipping_price' + item.id)
                                                    .text(
                                                        '$' + shippingPreference
                                                        .domestic_shipping_fee_per_item
                                                    );

                                                // update total
                                                var totalElement = $('#total' +
                                                    item
                                                    .id);
                                                var total = parseFloat(
                                                    totalElement
                                                    .text().replace('$', '')
                                                ) + parseFloat(
                                                    shippingPreference
                                                    .domestic_shipping_fee_per_item
                                                ); // Get and convert total to double
                                                totalElement.text('$' + total
                                                    .toFixed(
                                                        2));

                                                // Update cartItems in local storage
                                                updateCartItems(item.id, {
                                                    shipping_price: shippingPreference
                                                        .domestic_shipping_fee_per_item,
                                                    insurance: false,
                                                    total: total
                                                });
                                            }

                                            $('#shipping_provider' + item.id)
                                                .append(
                                                    option);
                                        });
                                        // $.each(response.data, function(key, value) {
                                        //     var option = $('<option value="' +
                                        //         value.shipping_provider.id +
                                        //         '">' +
                                        //         value.shipping_provider
                                        //         .name +
                                        //         '</option>');

                                        //     // Add the 'selected' attribute to the first option
                                        //     if (key === 0) {
                                        //         option.attr('selected',
                                        //             'selected');
                                        //         // update shipping price
                                        //         $('#shipping_price' + item.id)
                                        //             .text(
                                        //                 '$' + value
                                        //                 .shipping_price
                                        //             );

                                        //         // update total
                                        //         var totalElement = $('#total' +
                                        //             item
                                        //             .id);
                                        //         var total = parseFloat(
                                        //             totalElement
                                        //             .text().replace('$', '')
                                        //         ) + parseFloat(value
                                        //             .shipping_price
                                        //         ); // Get and convert total to double
                                        //         totalElement.text('$' + total
                                        //             .toFixed(
                                        //                 2));

                                        //         // Update cartItems in local storage
                                        //         updateCartItems(item.id, {
                                        //             shipping_price: value
                                        //                 .shipping_price,
                                        //             insurance: false,
                                        //             total: total
                                        //         });
                                        //     }

                                        //     $('#shipping_provider' + item.id)
                                        //         .append(
                                        //             option);
                                        // });

                                    },
                                    error: function(xhr, status, error) {
                                        // Handle errors
                                        console.error(xhr.responseText);
                                    }
                                })
                            } else {
                                $('#shipping_provider' + item.id).append(
                                    `<option value = "0" selected>Free Shipping</option>`);
                            }

                            // Update cartItems in local storage
                            updateCartItems(item.id, {
                                shipping_price: '0',
                                insurance: false,
                            });

                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                });
            } else {
                $('#cart').html(emptyCartHtml);
            }
        } else {
            $('#cart').html(emptyCartHtml);
        }

        // Change shipping provider
        function changeShippingProvider(id, store_id) {
            let spId = $('#shipping_provider' + id).val();
            console.log(spId);
            const url2 =
                `{{ url('api/store/${store_id}/shipping-providers/${spId}') }}`;
            $.ajax({
                url: url2,
                type: 'GET',
                success: function(response) {
                    // Handle the success response
                    console.log(response);

                    var oldShippingPrice = parseFloat($('#shipping_price' + id).text().replace('$', ''));
                    var newShippingPrice = parseFloat(response.shippingPreference
                        .domestic_shipping_fee_per_item);
                    var totalElement = $('#total' + id);
                    var total = parseFloat(totalElement.text().replace('$', '')) + newShippingPrice;
                    total = total - oldShippingPrice;
                    totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
                    $('#shipping_price' + id).text('$' + newShippingPrice);
                    // Update cartItems in local storage
                    updateCartItems(id, {
                        shipping_price: newShippingPrice,
                        selected_shipping_provider: spId,
                        total: total
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            })

        }

        // Add insurance
        function addInsurance(id) {
            var insurance = $('#insurance' + id).val();
            var totalElement = $('#total' + id);
            var total = parseFloat(totalElement.text().replace('$', '')); // Get and convert total

            // Check if insurance is checked
            if ($('#insurance' + id).is(':checked')) {
                total += parseFloat(insurance); // Add insurance
                totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
                $('#insurance_value' + id).text('$' + insurance);
            } else {
                total -= parseFloat(insurance); // Remove insurance
                totalElement.text('$' + total.toFixed(2)); // Update total with two decimal places
                $('#insurance_value' + id).text('$' + 0);
            }

            // Update cartItems in local storage
            updateCartItems(id, {
                insurance: $('#insurance' + id).is(':checked'),
                total: total
            });
        }

        // Update cartItems in local storage
        function updateCartItems(id, updatedData) {
            let cartItems = JSON.parse(localStorage.getItem('gems_harbor_cart')) || [];

            // Find the item in the array and update it
            const updatedCartItems = cartItems.map(item => {
                if (item.id === id) {
                    return {
                        ...item,
                        ...updatedData
                    };
                }
                return item;
            });

            // Save the updated array back to local storage
            localStorage.setItem('gems_harbor_cart', JSON.stringify(updatedCartItems));
        }

        // Remove item from cart
        function removeItem(id) {
            var cartItems = JSON.parse(localStorage.getItem('gems_harbor_cart'));

            // Find the index of the item to be removed
            var indexToRemove = -1;
            for (var i = 0; i < cartItems.length; i++) {
                if (cartItems[i].id === id) {
                    indexToRemove = i;
                    break;
                }
            }

            if (indexToRemove !== -1) {
                // Remove the item from the cart array
                cartItems.splice(indexToRemove, 1);

                // Update cart
                localStorage.setItem('gems_harbor_cart', JSON.stringify(cartItems));

                // Remove the corresponding HTML code from the DOM
                $('#cart').find('.cart-item[data-item-id="' + id + '"]').remove();

                // Update cart count
                $('#cart-count').text(cartItems.length);

                // Check if cart is empty
                if (cartItems.length === 0) {
                    $('#cart').html(emptyCartHtml);
                }

                // Show success message
                // toastr.success('Item removed from cart');
            } else {
                toastr.error('Item not found in cart');
            }
        }
    </script>
@endsection
