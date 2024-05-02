<!DOCTYPE html>
<html lang="en">
{{-- include <head> part --}}
@include('layouts.includes.head')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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
                                                <td class="w-50  text-center nunito" style="color: #EA008B">Phone</td>
                                                <td class="w-50  text-center nunito-regular">{{ $profile->phone ?? '' }}
                                                </td>
                                            </tr>
                                            <tr class="w-100">
                                                <td class="w-50  text-center nunito" style="color: #EA008B">Address</td>
                                                <td class="w-50  text-center nunito-regular">{{ $profile->address ?? '' }}
                                                </td>
                                            </tr>
                                            <tr class="w-100">
                                                <td class="w-50  text-center nunito" style="color: #EA008B">Joining- Date
                                                </td>
                                                <td class="w-50  text-center nunito-regular">
                                                    {{ $user->created_at->format('d/m/Y') }}</td>
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
            <div class="col-md-4 logo  ps-2">
                <!-- logo is here -->
                <a href="/">
                    <img src="{{ asset('assets/buzzboostassets/logo-no-hd.png') }}" alt="logo image">
                </a>
            </div>
            @include('layouts.includes.guest-buyer-header-options')
        </div>

        <!-- HERO DIV SECTION -->
        <div class="row hero-div position-relative">
            <!-- sidebar for categories / menu etc -->
            <div class="d-none d-md-block col-md-2 hero-div-partition"
                style="border-right:  1px solid rgb(208, 207, 207);">
                <div class="row">
                    <!-- categories list -->
                    <div class="col-md-12 fill p-2">
                        <ul class="categories-list  m-0 p-0">
                            <li class="categories ps-2 my-2 text-white">Categories</li>
                            <!-- All Categories Link -->
                            <li class="">
                                <a href="/categories"
                                    class="main-item">
                                    View All Categories
                                </a>
                            </li>
                            @php
                                // Get All Categories with Products and products count greater than 0
                                $categories = \App\Models\Category::withCount('products')
                                    ->having('products_count', '>', 0)
                                    ->get();
                            @endphp
                            @foreach ($categories as $category)
                                <li>
                                    <a href="/categories/{{ $category->id }}/products" class="main-item">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Right Content Area -->
            <div class="col-12 col-md-10 hero-div-partition">
                <!-- Dynamic Content Area -->
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
            // var searchIcon = document.querySelector('.search-icon');
            // var searchWrapper = document.querySelector('.search-wrapper');
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
    </script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {

            // menu-controls

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

            // notifiacations controls

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
</body>

</html>
