<!DOCTYPE html>
<html lang="en">
{{-- include <head> part --}}
@include('layouts.includes.head')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
    .main-item.active {
        background-color: #DAE1EB !important;
    }
</style>

<body>
    <div class="container-fluid">
        <!-- Modals -->
        @yield('modals')
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

        <!-- HEADER -->
        <div class="row header">
            <div class="col-md-4 all-flex-center">
                <!--Store and no reserve -->
                <div class="all-flex-center gap-5 d-none d-md-flex" style="width:fit-content;height:100%;">
                    <!--for all stores start here -->
                    <div>
                        <div class="d-flex align-items-center gap-1" style="cursor: pointer" id="store-selector">
                            <i class="bi bi-tag"></i>
                            <h2 class="p-0 m-0 text-dark" style="font-size: 16px!important;font-weight:400!important">
                                Stores
                            </h2>
                            <i class="bi bi-chevron-down mt-2"></i>
                        </div>
                        <ul class="position-absolute rounded-2 p-2"
                            style="width: 100px;height:auto;background-color:#FFF;display:none;z-index:9"
                            id="store-list">
                            <li class="" style="font-size: 16px">
                                <a href="{{ route('stores') }}" class="d-block w-100 text-dark"
                                    style="color: black!important">All Stores</a>
                            </li>
                        </ul>
                    </div>
                    <!-- for all store ends here -->
                    <!-- for reserve store starts here -->
                    <div>
                        <div class="d-flex align-items-center gap-1" style="cursor: pointer" id="no-reserve-selector">
                            <i class="bi bi-currency-dollar"></i>
                            <h2 class="p-0 m-0 text-dark" style="font-size: 16px;font-weight:400!important">No Reserve
                            </h2>
                            <i class="bi bi-chevron-down mt-2"></i>
                        </div>
                        <ul class="position-absolute rounded-2 p-2"
                            style="width: 150px;height:auto;background-color:#FFF;display:none;z-index:9"
                            id="no-reserve-list">
                            <li class="" style="font-size: 16px">
                                <a href="{{ route('auctions.no-reserve') }}" class="d-block w-100 text-dark">No Reserve
                                    Gemstones</a>
                            </li>
                        </ul>
                    </div>
                    <!-- for reserve store ends here -->
                </div>
            </div>
            <div class="col-md-4  logo all-flex-center">
                <!-- logo is here -->
                <a href="/">
                    <img src="{{ asset('assets/buzzboostassets/logo-no-hd.png') }}" alt="logo image"
                        style="filter: invert(1)">
                </a>
            </div>
            <div class="col-md-4  login-register all-flex-center  position-relative ">
                <ul class="m-auto ms-md-auto ">
                    @guest
                        @if (Route::has('register'))
                            <!-- Register link -->
                            <li>
                                <a href="{{ route('register') }}" class="register">Register</a>
                            </li>
                        @endif

                        @if (Route::has('login'))
                            <!-- login link -->
                            <li>
                                <a href="{{ route('login') }}" class="login">Login</a>
                            </li>
                        @endif
                    @endguest
                    <!-- menu icon -->
                    <li>
                        <div class="collapsedNavbar">
                            <i class="bi bi-list" style="font-size: 20px"></i>
                        </div>
                    </li>
                </ul>
                <div class="navbarList position-absolute bg-white rounded-3 p-3">
                    <ul class="m-0 d-flex align-items-center justify-content-start">
                        <li>
                            <div class="search-icon">
                                <i class="bi bi-search special-icon"></i>
                            </div>
                        </li>
                        @auth
                            @if (auth()->user()->hasRole('buyer'))
                                <!-- Cart Icon with Count -->
                                <li style="position:relative">
                                    <a href="{{ route('buyer.cart') }}">
                                        <i class="bi bi-cart4 special-icon"></i>
                                        <span class="circular-number"
                                            id="cart-count">{{ auth()->user()?->cart?->total_items ?? 0 }}
                                        </span>
                                    </a>
                                </li>
                                <!-- Heart Icon with Count -->
                                <li style="position: relative">
                                    <a href="{{ route('buyer.wishlist.index') }}">
                                        <i class="bi bi-suit-heart special-icon"></i>
                                        <span class="circular-number">{{ auth()->user()->wishlist->count() }}
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @php
                                $notifications = [];
                                $notifications = \App\Models\Notification::orderBy('created_at', 'desc')
                                    ->where('user_id', auth()->user()->id)
                                    ->where('is_read', 0)
                                    ->get();
                            @endphp
                            <li style="position: relative">
                                <button id="notificationIcon" type="button" class="bg-transparent border-0 text-white">
                                    <i class="bi bi-bell special-icon"></i>
                                    <span id="notificationCount"
                                        class="circular-number">{{ $notifications->count() }}</span>
                                </button>
                            </li>
                            <li>
                                <button class="border-0 bg-transparent">
                                    <i class="bi bi-person-circle special-icon" data-bs-toggle="modal"
                                        data-bs-target="#forUserDetails"></i>
                                </button>
                            </li>
                            <li>
                                <div class="">
                                    <span class="icon-link user-options-selector"
                                        style="cursor: pointer">{{ auth()->user()->name }} <i class="bi bi-chevron-down"
                                            style="font-size: 12px"></i>
                                    </span>
                                </div>
                            </li>
                        @endauth
                    </ul>
                    <!-- search wrapper starts here -->
                    <div class="search-wrapper mt-3" style="display:none;">
                        <input id="search" type="search" placeholder="Search products" class="search">
                    </div>
                    <!-- search wrapper ends here -->
                    <!-- user options list starts here -->
                    <div class="user-options-list  all-flex-center" style="display:none;width:100%">
                        <ul class="bg-white all-flex-center flex-column py-4 m-0" style="width:100%;height:auto;">
                            <li>
                                <a class="user-option-item" href="{{ route('dashboard') }}">Dashboard
                                </a>
                            </li>
                            <!-- Switch Role based on User -->
                            @auth
                                @if (auth()->user()->hasRole('seller'))
                                    <li>
                                        <a class="user-option-item" href="{{ route('switch.role') }}">Switch to Buyer</a>
                                    </li>
                                @elseif (auth()->user()->hasRole('buyer'))
                                    @if (auth()->user()->store == null)
                                        <li>
                                            <a class="user-option-item" href="{{ route('switch.role') }}">Become a
                                                Seller</a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="user-option-item" href="{{ route('switch.role') }}">Switch to
                                                Seller</a>
                                        </li>
                                    @endif
                                @endif
                            @endauth
                            <li>
                                <a class="user-option-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                            <!-- Logout Form -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </ul>
                    </div>
                    <!-- user options list ends here -->
                    <!-- notification starts  here -->
                    @auth
                        {{--  @include('layouts.includes.notification') --}}
                        <div id="notificationBody" class="rounded-2 py-3" style="display:none;width:100%;">
                            <div class="px-2">
                                <h4 style="font-size: 21px">Notifications</h4>
                                @forelse ($notifications as $notification)
                                    <div class="notification-item my-2">
                                        <h4 class="notification-text d-flex align-items-center m-0">
                                            {{ $notification->title ?? 'N/A' }}
                                        </h4>
                                        {{-- <p class="notification-time m-0">july 23,2023 at 9:15 PM</p> --}}
                                        <p class="notification-time m-0">
                                            {{ $notification->created_at->format('F j, Y, g:i a') }}</p>
                                    </div>
                                @empty
                                    <div class="notification-item my-2">
                                        <p class="notification-time m-0">No new notifications.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endauth
                    <!-- notification ends  here -->
                </div>
            </div>
        </div>

        {{-- Hero Div Section --}}
        <div class="row hero-div position-relative">

            {{-- Left Column for Navigation Links (visible on larger screens) --}}
            <div class="d-none d-md-block col-md-2 hero-div-partition"
                style="border-right:  1px solid rgb(208, 207, 207);">
                <div class="row">
                    <div class="col-md-12 fill  p-3">
                        {{-- Navigation Links --}}
                        @if (auth()->user()->profile)
                            <ul class="categories-list m-0 p-0">
                                <li class="categories">Menu</li>
                                <a href="{{ route('dashboard') }}">
                                    <li class="main-item d-flex @if ($title == 'Buyer Dashboard') active @endif align-items-center"
                                        style="gap: 10px"><i class="bi bi-speedometer2"></i>Dashboard</li>
                                </a>
                                <a href="{{ route('buyer.bids.index') }}">
                                    <li class="main-item d-flex @if ($title == 'Bids') active @endif align-items-center"
                                        style="gap: 10px"><i class="bi bi-hammer"></i>Bids</li>
                                </a>
                                <a href="{{ route('buyer.offers.index') }}">
                                    <li class="main-item d-flex @if ($title == 'Offers') active @endif align-items-center"
                                        style="gap: 10px"><i class="bi bi-receipt"></i>Offers</li>
                                </a>
                                <a href="{{ route('buyer.orders.index') }}">
                                    <li class="main-item d-flex @if ($title == 'Win History') active @endif align-items-center"
                                        style="gap: 10px"><i class="bi bi-trophy"></i>Win History</li>
                                </a>
                                <a href="{{ route('chats.index', ['role' => 'buyer']) }}">
                                    <li class="main-item d-flex @if ($title == 'Messages') active @endif align-items-center"
                                        style="gap: 10px"><i class="bi bi-chat-left"></i>Messages</li>
                                </a>
                                <a href="{{ route('buyer.feedbacks.index') }}">
                                    <li class="main-item d-flex  @if ($title == 'Feedback' || $title == 'Awaiting Feedbacks') active @endif  align-items-center"
                                        style="gap: 10px"><i class="bi bi-hand-thumbs-up"></i>Feedback History
                                    </li>
                                </a>
                                <a href="{{ route('support.index', ['role' => 'buyer']) }}">
                                    <li class="main-item d-flex  @if ($title == 'Support') active @endif  align-items-center"
                                        style="gap: 10px"><i class="bi bi-megaphone"></i>Support</li>
                                </a>
                                <a href="{{ route('buyer.settings.index') }}">
                                    <li class="main-item d-flex @if ($title == 'Buyer Settings') active @endif align-items-center"
                                        style="gap: 10px"><i class="bi bi-gear"></i>Settings</li>
                                </a>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column for Main Content and Offcanvas --}}
            <div class="col-12 col-md-10 p-2 hero-div-partition">
                {{-- Dynamic Content Section --}}
                <div class="dynamic-part">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
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
    </script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            // Reusable function to handle toggle behavior
            function toggleElement(selector, list) {
                const elementSelector = $(selector);
                const elementList = $(list);

                let isInside = false;

                elementSelector.on("mouseenter", function() {
                    isInside = true;
                    elementList.show("fade", {
                        direction: "vertical"
                    }, 100);
                });

                elementSelector.on("mouseleave", function() {
                    isInside = false;
                    setTimeout(function() {
                        if (!isInside) {
                            elementList.hide("fade", {
                                direction: "vertical"
                            }, 100);
                        }
                    }, 200);
                });

                elementList.on("mouseenter", function() {
                    isInside = true;
                });

                elementList.on("mouseleave", function() {
                    isInside = false;
                    elementList.hide("fade", {
                        direction: "vertical"
                    }, 100);
                });

                // Close the element when clicking outside
                $(document).on("click", function() {
                    if (!isInside) {
                        elementList.hide("fade", {
                            direction: "vertical"
                        }, 100);
                    }
                });
            }
            // Call the function for each element
            toggleElement("#store-selector", "#store-list");
            toggleElement("#small-store-selector", "#small-store-list");
            toggleElement("#no-reserve-selector", "#no-reserve-list");
            toggleElement("#small-no-reserve-selector", "#small-no-reserve-list");

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
            toggleElementClick("#notificationIcon", "#notificationBody");

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
        var supportLinks = document.querySelectorAll('.continue-ticket');
        setInterval(() => {
            supportLinks.forEach(link => {
                link.classList.add('animate__zoomIn');
                setTimeout(() => {
                    link.classList.remove('animate__zoomIn');
                }, 1000); // Remove the class after 1 second to create the pulsating effect
            });
        }, 4000);
    </script>
</body>

</html>
