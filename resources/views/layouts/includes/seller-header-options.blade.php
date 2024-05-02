<!-- seller header options -->
<div class="col-md-8  login-register position-relative pe-2">
    <ul class="">
        @guest
            @if (Route::has('register'))
                <!-- Register link / Register Button -->
                <li>
                    <a href="{{ route('register') }}" class="register">Register</a>
                </li>
            @endif

            @if (Route::has('login'))
                <!-- login link / Login Button -->
                <li>
                    <a href="{{ route('login') }}" class="login">Login</a>
                </li>
            @endif
        @endguest
        <li>
            <div class="search-icon">
                <i class="bi bi-search special-icon scheme-text-color scheme-active"></i>
            </div>
        </li>
        @auth
            @if (auth()->user()->hasRole('buyer'))
                <!-- Cart Icon with Count -->
                <li style="position:relative">
                    <a href="{{ route('buyer.cart') }}">
                        <i class="bi bi-cart4 special-icon scheme-text-color scheme-active"></i>
                        <span class="circular-number" id="cart-count">{{ auth()->user()?->cart?->total_items ?? 0 }}
                        </span>
                    </a>
                </li>
                <!-- Heart Icon with Count -->
                <li style="position: relative">
                    <a href="{{ route('buyer.wishlist.index') }}">
                        <i class="bi bi-suit-heart special-icon scheme-text-color scheme-active"></i>
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
                    <i class="bi bi-bell special-icon scheme-text-color scheme-active"></i>
                    <span id="notificationCount" class="circular-number">{{ $notifications->count() }}</span>
                </button>
            </li>
            <li>
                <button class="border-0 bg-transparent">
                    <i class="bi bi-person-circle special-icon scheme-text-color scheme-active" data-bs-toggle="modal"
                        data-bs-target="#forUserDetails"></i>
                </button>
            </li>
            <li>
                <div class="">
                    <span class="icon-link user-options-selector scheme-text-color scheme-active"
                        style="cursor: pointer">{{ auth()->user()->name }} <i class="bi bi-chevron-down  scheme-text-color"
                            style="font-size: 12px"></i>
                    </span>
                </div>
            </li>
        @endauth
    </ul>

    <!-- search input for searching products -->

    <div class="search-wrapper  position-absolute bg-white  p-3 py-4 shadow-sm menu-control">
        <input id="search" type="search" placeholder="Search products" class="search">
    </div>


    <!-- user options list for dashboard ,swithing role and logout here -->

    <div class="user-options-list bg-white  all-flex-center position-absolute menu-control shadom-sm">
        <ul class=" all-flex-center flex-column py-4 m-0" style="width:100%;height:auto;">
            <li class="w-100">
                <a class="user-option-item" href="{{ route('dashboard') }}">Dashboard
                </a>
            </li>
            <!-- Switch Role based on User -->
            @auth
                @if (auth()->user()->hasRole('seller'))
                    <li class="my-2 w-100">
                        <a class="user-option-item" href="{{ route('switch.role') }}">Switch to Buyer</a>
                    </li>
                @elseif (auth()->user()->hasRole('buyer'))
                    @if (auth()->user()->store == null)
                        <li class="my-2 w-100">
                            <a class="user-option-item" href="{{ route('switch.role') }}">Become a
                                Seller</a>
                        </li>
                    @else
                        <li class="w-100">
                            <a class="user-option-item" href="{{ route('switch.role') }}">Switch to
                                Seller</a>
                        </li>
                    @endif
                @endif
            @endauth
            <li class="w-100">
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

    <!-- notifiaction body for notifications here -->

    @auth
        @include('layouts.includes.notification')
        <div id="notificationBody" class="py-4 position-absolute bg-white menu-control shadow-sm">
            <div class="px-3 ">
                <h4 style="font-size: 21px">Notifications</h4>
                @forelse ($notifications as $notification)
                    <div class="notification-item my-2">
                        <h4 class="notification-text d-flex align-items-center m-0">
                            {{ $notification->title ?? 'N/A' }}
                        </h4>
                        <!-- <p class="notification-time m-0">july 23,2023 at 9:15 PM</p> -->
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
</div>
<!-- seller header options -->
