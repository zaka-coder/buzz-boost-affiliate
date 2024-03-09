@extends('layouts.all-stores', ['title' => 'Store Details'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/home/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

    <style>
        .dynamic-part {
            background-color: transparent;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="category-name">Store Details</div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-end ">
            <a href="{{ route('stores') }}" class="anchor-button rounded-2 me-4" style="width:120px">Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex m-auto mt-2" style="height: 140px;width:95%;background-color: #FFF;">
                <div class="store-image w-30 h-100 d-flex align-items-center justify-content-center" style="z-index: 1">
                    <div class="skeleton w-90 h-90">
                        <img src="{{ asset($store->image ?? 'assets/buyer-assets/diamond-3.jpg') }}" alt=""
                            width="100%" height="100%" style="object-fit: cover;max-width: 100%;border-radius:5px">
                    </div>
                </div>
                <div class="w-70 h-100">
                    <div class="h-70 pt-2 position-relative">
                        <h4 class="skeleton" style="font-size: 19px;width:fit-content">{{ $store->name }} <i
                                class="bi bi-patch-check-fill" style="color: #10B981"></i> </h4>
                        <div class="d-flex align-items-center gap-1">
                            <p class="text-muted p-0 m-0  w-85 text-capitalize skeleton storeDescriptionText"
                                style="text-align: start;font-size: 14px" id="">
                                {{ $store->description ?? ' Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque, dolores aliquam eaque deserunt deleniti vero reprehenderit minima sequi id. Nulla beatae optio ipsam, soluta numquam itaque. Repellat tenetur magnam dignissimos. ' }}
                            </p>
                            <button class="bg-transparent border-0 storeDescriptionButton" type="button"
                                data-bs-toggle="modal" data-bs-target="#storeDescription" id="">see more</button>
                        </div>
                        <p class="p-0 mt-1 skeleton" style="font-size:15px;width:fit-content">{{ $store->country ?? '' }}
                        </p>
                        <div class="position-absolute px-2 text-white skeleton"
                            style="font-size:15px;top:0;right:0;font-family:nunito-regular;border-bottom-left-radius:5px;background-color:#00000073">
                            Member Since {{ $store->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <div class="h-30 d-flex align-items-center justify-content-start gap-5">
                        <div class="rounded-2   text-white  py-1 d-flex align-items-center gap-1 justify-content-center skeleton"
                            style="background-color:#10B981;width:120px"><i class="bi bi-patch-check"></i>Verified</div>
                        <div class="skeleton">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <p class="p-0 m-0 text-muted afterLine">{{ $store->products->count() }} Item(s)</p>
                                <p class="p-0 m-0 text-muted">
                                    {{ $store->ratings > 0 ? number_format($store->ratings, 1) . '%' : 'No' }} Ratings
                                </p>
                            </div>
                        </div>
                        @auth
                            <div class="skeleton" style="font-size:15px;font-family:nunito-regular">
                                <button type="button" class="py-1 rounded-2 text-dark"
                                    style="border: 1px solid  #105082;width:120px"
                                    onclick="document.getElementById('message-form').submit()">Message</button>
                                <form id="message-form" action="{{ route('chats.store', ['role' => 'buyer']) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="receiver_id" value="{{ $store->user->id }}">
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row px-2 py-3">
        <div class="category-name">All Items</div>
        <div class="products-cards grid_system_rectangle">
            @foreach ($products as $product)
                <div class="card">
                    <div class="card-head p-2">
                        <section id="splideStore{{ $product->id }}" class="splide w-100 h-100 " aria-label="">
                            <div class="splide__track w-100 h-100">
                                <ul class="splide__list w-100 h-100">
                                    <li class="splide__slide w-100 h-100 position-relative skeleton">
                                        <img src="{{ asset($product->image ?? 'assets/buyer-assets/coming-soon.avif') }}"
                                            alt="" style="width:100%;height:100%;max-width:100%;object-fit:cover"
                                            class="rounded-3">
                                        <span class="featured-tag">Premium</span>
                                    </li>
                                    @foreach ($product->gallery as $gallery)
                                        <li class="splide__slide w-100 h-100 position-relative">
                                            <img src="{{ asset($gallery->image ?? 'assets/buyer-assets/coming-soon.avif') }}"
                                                alt=""
                                                style="width:100%;height:100%;max-width:100%;object-fit:cover"
                                                class="rounded-3">
                                            <span class="featured-tag">Premium</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>
                    </div>
                    <a href="{{ route('products.show', $product->id) }}" class="products-card-link">
                        <div class="card-body">
                            <div class="card-body-first">
                                <h5 class="m-0 p-2 text-center text-truncate text-capitalize skeleton-text">
                                    {{ $product->weight }} ct
                                    {{ $product->name }} </h5>
                            </div>
                        </div>
                    </a>
                    <div class="time-part pt-1">
                        @if ($product->productListing->item_type == 'auction')
                            @php
                                $countdownTime = $product->countdown_time;
                            @endphp
                            <p class="p-0 m-0 text-center skeleton-text" id="footerTime{{ $product->id }}">
                                {{ $countdownTime['days'] . 'd ' . $countdownTime['hours'] . 'h ' . $countdownTime['minutes'] . 'm ' ?? 'N/A' }}
                            </p>
                        @endif
                    </div>
                    <div class="card-footer ">
                        <div class="footer-icons-part d-flex align-items-center  justify-content-start ps-3  gap-1">
                            @if ($product->productListing->item_type == 'auction')
                                <button class="m-0 p-0 border-0 bg-transparent skeleton-icon" type="button"
                                    data-bs-toggle="modal" data-bs-target="#bid-popup{{ $product->id }}" title="Bid Now">
                                    <i class="bi bi-hammer"></i>
                                </button>
                            @else
                                {{-- <i class="bi bi-bag-check"></i> --}}
                                <button class="m-0 p-0 border-0 bg-transparent skeleton-icon" type="button"
                                    title="Add to cart" onclick="addToCart({{ $product->id }})">
                                    <i id="add-to-cart-icon-{{ $product->id }}" class="bi bi-bag"></i>
                                </button>
                                <button class="m-0 p-0 border-0 bg-transparent skeleton-icon" type="button"
                                    data-bs-toggle="modal" data-bs-target="#make-offer-popup{{ $product->id }}"
                                    title="Make an offer">
                                    <i class="bi bi-arrow-left-right"></i>
                                </button>
                            @endif
                            <button class="m-0 p-0 rounded-4 border-0 bg-transparent skeleton-icon" title="Certified">
                                <i class="bi bi-shield-check" title="Certified"></i>
                            </button>
                            {{-- <i class="bi bi-shield-x"></i> --}}
                        </div>
                        <div class="footer-price-time-part d-flex align-items-center justify-content-center">
                            <div class="footer-price-part">
                                <p class="skeleton-text">
                                    ${{ $product->productListing->item_type == 'auction' ? $product->productPricing->starting_price : $product->productPricing->buy_it_now_price }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- incase if there is no data then this div must show to the user --}}
        @if ($products->count() == 0)
            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2  mt-5">
                <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                    style="filter: invert(1)">
                <p class="nunito">No items found</p>
            </div>
        @endif
    </div>
    {{-- store description modal --}}
    <div class="modal fade" id="storeDescription" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body position-relative">
                    <button type="button" class="close position-absolute end-0 bg-transparent border-0 text-dark"
                        data-bs-dismiss="modal" aria-label="Close" style="width:fit-content;top:-7px">
                        <i><i class="bi bi-x-lg"></i></i>
                    </button>
                    {{ $store->description }}
                </div>
                <div class="">
                    <button class="w-100 anchor-button termsConditionsButton" type="button" data-bs-toggle="modal"
                        data-bs-target="#termsConditions" id="">Terms
                        and conditions</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="termsConditions" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <h5 class="modal-title">Terms and conditions</h5>
                    <button type="button" class="close position-absolute end-0 bg-transparent border-0 m-1 text-dark"
                        data-bs-dismiss="modal" aria-label="Close" style="width:fit-content;top:-7px">
                        <i><i class="bi bi-x-lg"></i></i>
                    </button>
                </div>
                <div class="modal-body">

                    {{ $store->shipping_terms }}
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Modals section -->
@section('modals')
    @foreach ($products as $product)
        <!-- Bid Modal -->
        @include('layouts.includes.bid-modal', ['product' => $product])

        <!-- Offer Modal -->
        @include('layouts.includes.offer-modal', ['product' => $product])
    @endforeach
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script>
        var storeDescriptionText = '{{ $store->description }}';
        var storeDescriptionButtons = document.querySelectorAll('.storeDescriptionButton');
        var termsConditionsButtons = document.querySelectorAll('.termsConditionsButton');
        var termsConditionsText = '{{ $store->shipping_terms }}';
        if (termsConditionsText.length == '') {
            termsConditionsButtons.forEach((termsConditionsButton) => {
                termsConditionsButton.style.display = 'none';
            })
        } else {
            termsConditionsButtons.forEach((termsConditionsButton) => {
                termsConditionsButton.style.display = 'block';
            })
        }
        if (storeDescriptionText.length > 70) {
            storeDescriptionButtons.forEach((storeDescriptionButton) => {
                storeDescriptionButton.style.display = 'block';
            })
        } else {
            storeDescriptionButtons.forEach((storeDescriptionButton) => {
                storeDescriptionButton.style.display = 'none';
            })
        }

        function truncateStoreDescription(inputDescription, maxDescription) {
            if (inputDescription.length > maxDescription) {
                return inputDescription.substring(0, maxDescription) + '...';
            } else {
                return inputDescription;
            }
        }
        var originalDescription = storeDescriptionText;
        var truncatedDescription = truncateStoreDescription(originalDescription, 70);
        console.log(truncatedDescription)
        document.querySelector('.storeDescriptionText').innerHTML = truncatedDescription;
        // function to submit the offer form
        function formsubmit(productId) {
            // function to validate the form
            function validateForm() {
                // reset error messages
                $(`#offer-value-error${productId}, #validity-error${productId}`).text('');

                // flag to track if the form is valid
                let isValid = true;

                // validate offer value
                const offerValue = $(`#offer-form${productId} [name="offer_value"]`).val();
                if (!offerValue || isNaN(offerValue) || parseFloat(offerValue) <= 0) {
                    $(`#offer-value-error${productId}`).text('Please enter a valid offer amount.');
                    isValid = false;
                }

                // validate validity
                const validity = $(`#offer-form${productId} [name="validity"]`).val();
                if (!validity) {
                    $(`#validity-error${productId}`).text('Please select a valid duration.');
                    isValid = false;
                }

                return isValid;
            }

            // function to submit the form
            function submitForm() {
                // AJAX request to submit the form data
                $.ajax({
                    type: 'POST',
                    url: $(`#offer-form${productId}`).attr('action'),
                    data: $(`#offer-form${productId}`).serialize(),
                    beforeSend: function() {
                        $('.loaderSpin').show();
                    },
                    complete: function() {
                        $('.loaderSpin').hide();
                    },
                    success: function(response) {
                        // reload the page
                        // window.location.reload();
                        if (response.success) {
                            // handle success response
                            $('#offer-success-alert' + productId).html('');
                            $('#offer-success-alert' + productId).html(
                                `<small class="modal-text">Offer submitted successfully!</small>`);
                            toastr.success('Offer submitted successfully!'); // display success message
                            $("#offer-form" + productId)[0].reset(); // reset the form
                            getOfferHistory(productId); // update the offer history
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
                                $("#offer-value-error" + productId).text(errorResponse.errors.offer_value);
                                $("#validity-error" + productId).text(errorResponse.errors.validity);
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                            }
                        }
                    }

                });
            }

            // update the offer history using AJAX
            function getOfferHistory(productId) {
                $url = `{{ url('/buyer/item/${productId}/offers') }}`;
                // AJAX request to get the offer history
                $.ajax({
                    type: 'GET',
                    url: $url,
                    success: function(response) {
                        // clear the offer history
                        $(`#offer-history${productId}`).html('');

                        // update the offer history heading
                        if (response.length == 0) {
                            $(`#offer-history${productId}`).html(`
                                        <div class="w-100 text-center">
                                            <p>No history</p>
                                        </div>`);
                        } else {
                            $(`#offer-history${productId}`).html(`
                            <div class="bid-history-heading w-100 px-md-3">
                                        <p class="m-0 p-0"><b>Offer History
                                                (${response.length})
                                            </b></p>
                                    </div>`);
                        }

                        // update the offer history
                        response.forEach(offer => {
                            $(`#offer-history${productId}`).append(`
                                        <div class="bid-history-details w-100 ps-md-3 d-flex align-items-center">
                                            <div class="w-75 h-100">
                                                <p class="text-primary my-2">${offer.userName}</p>
                                                <p style="line-height: 0">${offer.created}</p>
                                            </div>
                                            <div class="w-25">
                                                <b> ${offer.offer_value}</b>
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
            if (validateForm()) {
                // if the form is valid, submit the form
                submitForm();
            }
        }

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
                $('.loaderSpinBid').show();
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

                    },
                    complete: function() {
                        $('.loaderSpinBid').hide();
                    },

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
    <script>
        // splide initiallization
        @foreach ($products as $product)
            new Splide('#splideStore{{ $product->id }}', {
                arrows: true,
                rewind: true,
                type: 'fade',
                heightRatio: 0.5,
            }).mount();
        @endforeach
    </script>
@endsection
