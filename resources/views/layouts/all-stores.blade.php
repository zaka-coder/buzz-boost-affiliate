<!DOCTYPE html>
<html lang="en">
{{-- include <head> part --}}
@include('layouts.includes.head')
<style>
    .menu-control {
        display: none;
    }
</style>

<body>
    <div class="container-fluid">
        <!-- Modals -->
        @yield('modals')
        @auth
            {{-- the modal for the user details starts here --}}
            <div class="modal fade" id="forUserDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 9999;">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                        <div class="modal-header position-relative">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel" style="font-family:nunito">
                                {{ auth()->user()->name }}'s Profile</h1>
                            <button type="button"
                                class="btn-close position-absolute top-0 end-0 m-0 outline-none shadow-none"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 m-0 border-0">
                            @php
                                $user = auth()->user();
                                $profile = $user->profile;
                                $notifications = [];
                                $notifications = \App\Models\Notification::orderBy('created_at', 'desc')
                                    ->where('user_id', auth()->user()->id)
                                    ->where('is_read', 0)
                                    ->get();
                            @endphp
                            {{-- general info here --}}
                            <div class="general p-0 m-0">
                                <div class="d-flex flex-column align-items-center justify-content-center p-2">
                                    <div class="rounded-3" style="width:120px;height:120px">
                                        <img src="{{ asset($profile->image ?? 'assets/buyer-assets/zaka.jpeg') }}"
                                            alt="" width="100%" height="100%"
                                            style="object-fit: cover;border-radius:inherit">
                                    </div>
                                </div>
                                <div class="text-center text-white"
                                    style="background-color: #105082;height:30px;line-height:30px;font-size:20px">
                                    General
                                </div>
                                <div class="">
                                    <div class="w-90 m-auto">
                                        <table class="table w-100">
                                            <tr class="w-100">
                                                <td class="w-50  text-center nunito" style="color: #EA008B">
                                                    Name</td>
                                                <td class="w-50  text-center nunito-regular">{{ auth()->user()->name }}
                                                </td>
                                            </tr>
                                            <tr class="w-100">
                                                <td class="w-50  text-center nunito" style="color: #EA008B">
                                                    Email</td>
                                                <td class="w-50  text-center nunito-regular">{{ auth()->user()->email }}
                                                </td>
                                            </tr>
                                            <tr class="w-100">
                                                <td class="w-50  text-center nunito" style="color: #EA008B">Contact-
                                                    Number</td>
                                                <td class="w-50  text-center nunito-regular">033344225124</td>
                                            </tr>
                                            <tr class="w-100">
                                                <td class="w-50  text-center nunito" style="color: #EA008B">Address</td>
                                                <td class="w-50  text-center nunito-regular">Peshawar Pakistan</td>
                                            </tr>
                                            <tr class="w-100">
                                                <td class="w-50  text-center nunito" style="color: #EA008B">Joining- Date
                                                </td>
                                                <td class="w-50  text-center nunito-regular">22/12/2022</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- the modal for the user details ends here --}}
        @endauth
        <!-- HEADER -->
        <div class="row header">
            <div class="col-md-4 logo ps-2">
                <!-- logo is here -->
                <a href="/">
                    <img src="{{ asset('assets/buzzboostassets/logo-no-hd.png') }}" alt="logo image">
                </a>
            </div>
            @include('layouts.includes.guest-buyer-header-options')
        </div>

        {{-- Hero Div Section --}}
        <div class="row hero-div position-relative">

            {{-- Left Sidebar for Larger Screens --}}
            <div class="d-none d-md-block col-md-3 hero-div-partition">
                <div class="row">
                    {{-- Categories List --}}
                    <div class="col-md-12 fill py-3" style="overflow: auto">
                        <div class="w-95 m-auto p-2" style="background: #FFF;">
                            <div class="">
                                <h5 class="fw-bold p-0 m-0 ">Keywords</h5>
                                <div class="mt-2 position-relative">
                                    <i class="bi bi-search position-absolute" style="left:6px;top:2px"></i>
                                    <input type="search" style="padding-left:28px;width:100%;height:30px"
                                        id="search-store" value="{{ $search ?? '' }}">
                                </div>
                            </div>
                            @if ($title == 'Stores')
                                <div class="mt-3">
                                    <h5 class="fw-bold p-0 m-0 ">Store Location</h5>
                                    @php
                                        // get all the countries from the stores->country
                                        $countries = \App\Models\Store::where('approved', 1)
                                            ->distinct('country')
                                            ->pluck('country');

                                        // count stores in each country
                                        $countStore = $countries->map(function ($country) {
                                            return \App\Models\Store::where('approved', 1)
                                                ->where('country', $country)
                                                ->count();
                                        });

                                    @endphp
                                    {{-- here the country starts  --}}
                                    @foreach ($countries as $key => $country)
                                        <form action="">
                                            <div class="mt-2 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="checkbox" name="{{ $country }}"
                                                        id="country{{ $key }}" value="{{ $country }}"
                                                        class="country-checkbox" style="height:16px;width:16px">
                                                    <label for="country"
                                                        style="font-size: 17px">{{ $country ?? '' }}</label>
                                                </div>
                                                <div>
                                                    <span class="rounded-3"
                                                        style="width: auto;height:27px;background-color:#F3F4F6;display:inline-block;padding:2px 5px;">{{ $countStore[$loop->index] ?? 0 }}</span>
                                                </div>
                                            </div>
                                        </form>
                                    @endforeach
                                    {{-- here the country ends --}}
                                </div>
                            @elseif ($title == 'Store Details')
                                <div class="mt-3">
                                    <h5 class="fw-bold p-0 m-0 ">All Stores</h5>
                                    @php
                                        // get all the stores from database
                                        $storeList = \App\Models\Store::where('approved', 1)->orderBy('name')->get();
                                    @endphp
                                    <ul class="" style="padding-left: 0!important">
                                        @foreach ($storeList as $key => $singleStore)
                                            <li class="w-100 mt-2 single_store_name">
                                                <a href="{{ route('stores.show', $singleStore->id) }}"
                                                    class="text-dark">
                                                    <i class="bi bi-shop me-2"></i>
                                                    {{ $singleStore->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </div>
                        {{-- verified seller start here --}}
                        <div class="w-95 m-auto">
                            <div class="w-100 mt-4 p-2" style="background: #FFF;">
                                <div class="w-100 d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('assets/buyer-assets/gold-transformed.jpeg') }}"
                                        alt="" width="60px" height="60px"
                                        style="object-fit: cover;max-width:60px">
                                </div>
                                <div class="w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                                    <p class="fw-bold p-0 m-0">Verified Seller</p><span class="bi bi-patch-check-fill"
                                        style="color: #10B981"></span>
                                </div>
                                <div class="w-100 my-2">
                                    <p class="text-center w-90 m-auto">All sellers on Gem Harbor Auctions must comply
                                        with our
                                        Verified Sellers program
                                        which ensures a high degree of industry knowledge, consistent refund policy and
                                        wholesale pricing.</p>
                                </div>
                            </div>
                        </div>
                        {{-- verified seller end here --}}
                        {{-- gems harbor sheriff starts here --}}
                        <div class="w-95 m-auto">
                            <div class="w-100 mt-4 p-2" style="background: #FFF;">
                                <div class="w-100 d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('assets/buyer-assets/silver-plate.jpg') }}" alt=""
                                        width="60px" height="60px" style="object-fit: cover;max-width:60px">
                                </div>
                                <div class="w-100 d-flex align-items-center justify-content-center  mt-2">
                                    <p class="fw-bold p-0 m-0">GemsHarbor Review</p>
                                </div>
                                <div class="w-100 my-2">
                                    <p class="text-center w-90 m-auto">The GemsHarbor Review program allows our members
                                        to request
                                        an audit on any product, which is completed by an independent Gemologist who
                                        assesses the accuracy of the item description and pictures.</p>
                                </div>
                            </div>
                        </div>
                        {{-- gems harbor sheriff starts here --}}
                    </div>
                </div>
            </div>
            {{-- Right Content Area --}}
            <div class="col-12 col-md-9 p-2 hero-div-partition">
                {{-- Dynamic Content Area --}}
                <div class="dynamic-part">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>

    <!-- JS CDN Links -->
    @include('layouts.includes.footer-scripts')

    <!-- JS Scripts -->
    <script>
        // Function to add product to cart
        function addToCart(productId) {
            @if (auth()->check())
                @if (auth()->user()->hasRole('buyer'))
                    // Add the product to the cart
                    $.ajax({
                        type: "Get",
                        url: "/buyer/cart/item/" + productId + "/add",
                        success: function(response) {
                            if (response.success) {
                                // Update the cart count
                                $('#cart-count').text(response.cart.total_items);
                                $('#cart-count-mobile').text(response.cart.total_items);

                                // Optionally, you can provide some feedback to the user, e.g., a toast or alert
                                toastr.success('Item added to cart!');
                            } else {
                                // Handle the error
                                if (response.message == "Already in cart") {
                                    toastr.info('Item already in cart!');

                                } else if (response.message == "Not found") {
                                    toastr.error('Item not found!');
                                } else if (response.message == "Unauthorized") {
                                    toastr.error('Please login to add to cart!');
                                }
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    })
                @else
                    toastr.error('Please login as a buyer to add items to cart!');
                @endif
            @else
                toastr.error('Please login to add items to the cart!');
            @endif
        }

        // set event for search inputs on pressing enter key
        document.addEventListener('DOMContentLoaded', function() {
            // Get the input field
            var searchInput = document.getElementById('search');
            var searchInput2 = document.getElementById('search2');
            // Attach keydown event listener
            searchInput.addEventListener('keydown', function(event) {
                // Check if the pressed key is Enter (key code 13)
                if (event.key === 'Enter') {
                    // redirect to search page
                    window.location.href = '/search/' + searchInput.value;
                    // alert('Enter key pressed!');

                }
            });
            searchInput2.addEventListener('keydown', function(event) {
                // Check if the pressed key is Enter (key code 13)
                if (event.key === 'Enter') {
                    // redirect to search page
                    window.location.href = '/search/' + searchInput2.value;
                    // alert('Enter key pressed!');

                }
            });
        });

        // set event for search store input on pressing enter key
        document.addEventListener('DOMContentLoaded', function() {
            // Get the input field
            var searchInput = document.getElementById('search-store');
            // Attach keydown event listener
            searchInput.addEventListener('keydown', function(event) {
                // Check if the pressed key is Enter (key code 13)
                if (event.key === 'Enter') {
                    // redirect to search page
                    window.location.href = '/stores/' + searchInput.value;
                    // alert('Enter key pressed!');

                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Add an event listener to each checkbox
            document.querySelectorAll('.country-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    // Get all selected countries
                    var selectedCountries = Array.from(document.querySelectorAll(
                            '.country-checkbox:checked'))
                        .map(function(checkbox) {
                            return checkbox.value;
                        });

                    // Make an AJAX request to update the store list based on selected countries
                    $.ajax({
                        url: 'api/country/stores',
                        method: 'POST',
                        data: {
                            countries: selectedCountries,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            // Update the store list with the returned data
                            let stores = data.stores;
                            // itterate over all the stores and append them to the container
                            $('#stores-container').empty();
                            stores.forEach(function(store) {
                                var items = store.products;
                                // var countItems = items.length;
                                // items.forEach(function(item) {

                                // })
                                // append the store to the container
                                $('#stores-container').append(
                                    `<div class="col-md-6" style="height:120px">
                                        <div class="h-95 rounded-2 border border-1 d-flex" style="background-color:#FFF;width:99%">
                                            <!-- for store image div starts here -->
                                            <div class="w-30 h-100  d-flex align-items-center justify-content-center">
                                                <img src="{{ asset('${store.image}' ?? 'assets/buyer-assets/diamond-3.jpg') }}" alt="store-image"
                                                    width="90%" height="90%" style="max-width:90%;object-fit:cover" class="rounded-2">
                                            </div>
                                            <div class="w-70 h-100 d-flex align-items-center justify-content-center">
                                                <div class="w-100 h-90">
                                                    <div class="w-100 h-50 px-2">
                                                        <div class="m-0 p-0">
                                                            <a href="/stores/${store.id}/show"
                                                                class="storenamelink">${store.name}</a>
                                                            <i class="bi bi-patch-check-fill" style="color: #10B981"></i>
                                                        </div>
                                                        <small class="p-0 m-0">${store.country}</small>
                                                    </div>
                                                    <!-- empty for alignments -->
                                                    <div class="w-100 h-20 px-2 text-muted text-truncate" style="font-size:15px">${store.description ? store.description : '-'}</div>
                                                    <div class="w-100 h-30 d-flex align-items-center justify-content-start gap-3 px-2">
                                                        <p class="p-0 m-0 text-muted afterLine">${items.length} Items</p>
                                                        <p class="p-0 m-0 text-muted">${store.ratingPercent}% Ratings</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`
                                );
                            })
                        },
                        error: function(error) {
                            console.error('Error getting stores:', error.responseText);
                        }
                    });
                });
            });
        });
    </script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        // menu controls here

        $(function() {
            function toggleElementClick(seSelector, seList) {
                const searchSelector = $(seSelector);
                const searchList = $(seList);

                let isInside = false;

                // Assuming searchSelector and searchList are jQuery objects
                var openSearchList = null;

                searchSelector.on("click", function(event) {
                    event.stopPropagation();

                    if (openSearchList && openSearchList.is(":visible")) {
                        // Close the currently open search list
                        openSearchList.hide("blind", {
                            direction: "vertical"
                        }, 300);
                        openSearchList = null;
                    }

                    isInside = !isInside;
                    if (isInside) {
                        searchList.show("blind", {
                            direction: "vertical"
                        }, 300);
                        openSearchList =
                            searchList; // Update the reference to the currently open search list
                    } else {
                        searchList.hide("blind", {
                            direction: "vertical"
                        }, 300);
                        openSearchList = null; // Reset the reference since the search list is closed
                    }
                });


                $(document).on("click", function(event) {
                    if (isInside && !searchSelector.is(event.target) && !searchList.is(event.target) &&
                        searchList.has(event.target).length === 0) {
                        isInside = false;
                        searchList.hide("blind", {
                            direction: "vertical"
                        }, 300);
                    }
                });
            }

            toggleElementClick(".search-icon", ".search-wrapper");
            toggleElementClick(".collapsedNavbar", ".navbarList");
            toggleElementClick(".user-options-selector", ".user-options-list");
            toggleElementClick(".stores-selector", ".stores-list");
            toggleElementClick(".no-reserve-selector", ".no-reserve-list");
            toggleElementClick("#notificationIcon", "#notificationBody");

            // notifications controls

            $("#notificationBody").mouseenter(function() {
                    @auth
                    @if ($notifications->count() > 0)
                        // change the status of all the notifications to read
                        $.ajax({
                            url: "{{ route('notifications.read') }}",
                            method: "GET",
                            success: function(response) {
                                if (response.success) {
                                    // $('#notificationCount').html(0);
                                } else {
                                    console.error('Error changing notification status:', response
                                        .error);
                                }
                            },
                            error: function(error) {
                                console.error('Error changing notification status:', error
                                    .responseText);
                            }
                        })
                    @endif
                @endauth
            })
        });
    </script>
    <script>
        var skeletons = document.querySelectorAll('.skeleton');
        var skeletonTexts = document.querySelectorAll('.skeleton-text');
        var skeletonIcons = document.querySelectorAll('.skeleton-icon');
        window.addEventListener('load', function() {
            skeletons.forEach((skeleton) => {
                skeleton.classList.remove('skeleton');
            })
            skeletonTexts.forEach((skeletonText) => {
                skeletonText.classList.remove('skeleton-text')
            })
            skeletonIcons.forEach((skeletonIcon) => {
                skeletonIcon.classList.remove('skeleton-icon')
            })
        })
    </script>
</body>

</html>
