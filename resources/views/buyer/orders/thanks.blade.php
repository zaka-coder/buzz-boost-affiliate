@extends('layouts.buyer', ['title' => 'Cart'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/cart.css') }}">
@endsection
@section('content')
    <div class="thanks w-100 h-100" style="background-color:#FFF">
        <div class="thanks-icon">
            <img src="{{ asset('assets/buyer-assets/thanks.png') }}" alt="">
        </div>
        <div class="thanks-buyer">
            <h1 class="text-center "><q>Thank you for your purchase! 🌟</q></h1>
            <p class="text-center">We really appreciate your business and<br> the trust you've placed in us.</p>
        </div>
        <div class="thanks-footer">
            <a href="/"><button class="started" type="button" class="next">
                    Home
                </button></a>
        </div>
    </div>
@endsection

@section('js')
    <script>
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
                    // Make an AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);

                            // remove the response.product from cart where response.product.is_sold is true
                            if (response.product.is_sold) {
                                removeItem(item.id);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                });
            }
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
            }
        }
    </script>
@endsection
