@extends('layouts.buyer', ['title' => 'wishlist'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/wishlist.css') }}">
@endsection
@section('content')
    <h2 class="h-5">My Wishlist</h2>
    <div class="w-100 h-90 p-md-4 p-2" style="background-color:#FBFBFB;overflow:auto">
        @forelse ($items as $item)
            <div class="w-100 h-auto rounded-1 d-flex flex-column flex-md-row align-items-center justify-content-center my-3 position-relative shadow-sm"
                style="background: #FFF 0% 0% no-repeat padding-box">
                <div class="w-md-50 w-100 wishlist-row d-flex align-items-center justify-content-between"
                    style="height: 90px">
                    <img src="{{ asset($item->image ?? 'assets/home/stones.jpg') }}" alt="" class="img-fluid">
                    <p class="nunito-regular text-start pt-3 ps-3"><a
                            href="{{ route('products.show', $item->id) }}">{{ $item->weight }} (ct) {{ $item->name }}</a>
                    </p>
                </div>
                <div class="nunito w-md-30 w-100 d-flex align-items-center justify-content-center" style="height: 90px">
                    Price:
                    ${{ $item->productListing->item_type == 'auction' ? $item->productPricing->starting_price : $item->productPricing->buy_it_now_price }}
                </div>
                <div class="w-md-20 w-100 d-flex align-items-center justify-content-center" style="height: 90px">
                    @if ($item->productListing->item_type == 'auction')
                        @if ($item->productListing->sold)
                            <button type="button" class="cart-button" disabled
                                style="background-color: rgb(182, 19, 19) !important">Sold</button>
                        @elseif($item->productListing->closed)
                            <button type="button" class="cart-button" disabled
                                style="background-color: rgb(182, 19, 19) !important">Closed</button>
                        @else
                            <button type="button" class="cart-button" data-bs-toggle="modal"
                                data-bs-target="#bid-popup{{ $item->id }}">Place Bid</button>
                        @endif
                    @else
                        @if ($item->productListing->sold)
                            <button type="button" class="cart-button" disabled
                                style="background-color: rgb(182, 19, 19) !important">Sold</button>
                        @elseif($item->productListing->closed)
                            <button type="button" class="cart-button" disabled
                                style="background-color: rgb(182, 19, 19) !important">Closed</button>
                        @else
                            <button type="button" class="cart-button" onclick="addToCart({{ $item->id }})">
                                Add to Cart
                            </button>
                        @endif
                    @endif
                </div>
                <button type="button" class="btn-close position-absolute top-0 " style="right:0!important;margin:0"
                    onclick="document.getElementById('delete-form-{{ $item->id }}').submit()"></button>
                <form id="delete-form-{{ $item->id }}" action="{{ route('buyer.wishlist.destroy', $item->id) }}"
                    method="post">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @empty
            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt="" style="filter: invert(1)">
                <p class="nunito-regular">No items in wishlist</p>
            </div>
        @endforelse
    </div>
@endsection
<!-- Modals section -->
@section('modals')
    @foreach ($items as $product)
        <!-- Bid Modal -->
        @include('layouts.includes.bid-modal', ['product' => $product])

        <!-- Make Offer Modal -->
        @include('layouts.includes.offer-modal', ['product' => $product])
    @endforeach
@endsection

@section('js')
    <script>
        // function to submit the bid form
        function bidformsubmit(productId) {
            // function to validate the form
            function validateBidForm() {
                // reset error messages
                $(`#bid-value-error${productId}`).text('');

                // flag to track if the form is valid
                let isValid = true;

                // validate bid value
                const bidValue = $(`#bid-form${productId} [name="price"]`).val();
                if (!bidValue || isNaN(bidValue) || parseFloat(bidValue) <= 0) {
                    $(`#bid-value-error${productId}`).text('Please enter a valid amount.');
                    isValid = false;
                }

                return isValid;
            }

            // function to submit the form
            function submitBidForm() {
                // AJAX request to submit the form data
                $.ajax({
                    type: 'POST',
                    url: $(`#bid-form${productId}`).attr('action'),
                    data: $(`#bid-form${productId}`).serialize(),
                    success: function(response) {
                        // reload the page
                        // window.location.reload();
                        if (response.success) {
                            // handle success response
                            $('#bid-success-alert' + productId).html('');
                            $('#bid-success-alert' + productId).html(
                                `<small class="modal-text">${response.message}</small>`);
                            toastr.success(response.message); // display success message
                            $("#bid-form" + productId)[0].reset(); // reset the form
                            getBidHistory(productId); // update the bid history
                        } else {
                            // handle error response
                            toastr.error(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        // Handle Unauthorized error
                        if (xhr.status == 403) {
                            var errorMsg = 'Unauthorized: Seller is not authorized to make an offer.';
                            console.error(errorMsg);
                            toastr.error(errorMsg);
                        } else {
                            try {
                                // Handle validation errors
                                var errorResponse = JSON.parse(xhr.responseText);
                                console.error(errorResponse.message);

                                // Update the content of the corresponding divs with error messages
                                $("#bid-value-error" + productId).text(errorResponse.errors.price);
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                            }
                        }

                    }

                });
            }

            // update the bid history using AJAX
            function getBidHistory(productId) {
                $url = `{{ url('/buyer/item/${productId}/bids') }}`;
                // AJAX request to get the offer history
                $.ajax({
                    type: 'GET',
                    url: $url,
                    success: function(response) {
                        // update the bid count
                        $('#bid-count' + productId).text(response.length);
                        // clear the offer history
                        $(`#bid-history${productId}`).html('');

                        // update the offer history heading
                        if (response.length == 0) {
                            $(`#bid-history${productId}`).html(`
                                        <div class="w-100 text-center">
                                            <p>No history</p>
                                        </div>`);
                        } else {
                            $(`#bid-history${productId}`).html(`
                            <div class="bid-history-heading w-100 px-md-3">
                                        <p class="m-0 p-0"><b>Bids History
                                                (${response.length})
                                            </b></p>
                                    </div>`);
                        }

                        // update the offer history
                        response.forEach(bid => {
                            $(`#bid-history${productId}`).append(`
                                        <div class="bid-history-details w-100 ps-md-3 d-flex align-items-center">
                                            <div class="w-75 h-100">
                                                <p class="text-primary my-2">${bid.userName}</p>
                                                <p style="line-height: 0">${bid.created}</p>
                                            </div>
                                            <div class="w-25">
                                                <b> ${bid.bid_value}</b>
                                            </div>
                                        </div>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            // validate the form before submitting
            if (validateBidForm()) {
                // if the form is valid, submit the form
                submitBidForm();
            }
        }
    </script>
@endsection
