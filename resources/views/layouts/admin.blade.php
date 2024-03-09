<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- remove this meta this is only for ngrok --}}
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="shortcut icon" href="{{ asset('assets/buyer-assets/favicon.ico') }}" type="image/x-icon">
    <title>Gems Harbor - Administration</title>
    {{-- bootstrap cdn link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    {{-- bootstrap icon cdn link --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
        integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    {{-- jquery dataTable css --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    {{-- toastr css cdn --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    {{-- external css links --}}
    <link rel="stylesheet" href="{{ asset('assets/css/layout-css/index.css') }}">
    @yield('css')
    <style>
        .main-item {
            padding: 8px;
        }

        .active {
            padding: 8px !important;
            background-color: red
        }

        .hero-div-partition {
            height: auto!important;
        }
    </style>
</head>

<body>
    {{-- Modals overlay --}}
    @yield('overlay')
    <div class="" style="--bs-gutter-x:0!important;overflow:hidden">
        {{-- top navigation bar with icons html --}}
        <div class="row navigation py-1 justify-content-between w-100" style="height: 50px !important">
            <div class="col-md-2 logo d-flex align-items-center justify-content-center">
                <img src="{{ asset('assets/buyer-assets/logo-no-bg.png') }}" alt="" class="ms-5"
                    style="width: 100px !important;">
            </div>
            <ul class="col-md-3 navigation-links d-flex align-items-center px-5">
                <li><button id="sidebarController" class="btn btn-lg border-0"><i class="bi bi-list"></i></button></li>
                <li><a href="/" class="home">Home</a></li>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapsed" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>
            </ul>
            <ul class="col-md-7 user-related d-flex align-items-center justify-content-end px-3">
                <li><i class="bi bi-search"></i></li>
                <li class="me-5"><input type="search" placeholder="Search..." class="search"></li>
                <li><button><i class="bi bi-bell"></i></button></li>
                <li><button><i class="bi bi-person-circle"></i></button></li>
                <li><span class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                        role="button">{{ auth()->user()->name }}</span>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a></li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </li>
            </ul>
            <div class="collapse navbar-collapse" id="navbarCollapsed">
                <ul>
                    <span class="d-flex align-items-center" style="gap: 20px">
                        <li><i class="bi bi-search"
                                style=" color: var(--scheme-color);
                            font-size: 20px !important;"></i>
                        </li>
                        <li><input type="search" placeholder="Search..." class="search"
                                style="border: none;background-color: transparent;    font-family: 'Nunito', sans-serif;font-size:13px;  padding-left: 7px;">
                        </li>
                    </span>
                    <li><button style="border: none;background-color: transparent;"><i class="bi bi-bell"
                                style="color: var(--scheme-color);
                        font-size: 20px !important;"></i></button>
                    </li>
                    <span class="d-flex align-items-center" style="gap: 20px">
                        <li><button style=" border: none;background-color: transparent;"><i class="bi bi-person-circle"
                                    style=" color: var(--scheme-color);
                                    font-size: 20px !important;"></i></button>
                        </li>
                        <li><span class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button"
                                style="font-family: 'Nunito', sans-serif;font-size:14px">{{ auth()->user()->name }}</span>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a></li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    </span>
                </ul>
            </div>
        </div>
        {{-- hero div html --}}
        <div class="row hero-div w-100 position-relative" style="height: 92vh">
            {{-- side bar --}}
            <div id="sidebar" class="d-none d-md-block hero-div-partition" style="width: 20%">
                <div class="row">
                    <div class="col-md-12 empty" style="height: 30vh !important;">
                        <img src="{{ asset('assets/buyer-assets/rock-rotated.png') }}" width="200" alt=""
                            class="img-fluid">
                    </div>
                    <div class="col-md-12 fill">
                        <ul class="categories-list w-100">
                            <li class="categories p-0">Menu</li>
                            <li class="p-0">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="main-item @if (request()->is('admin/dashboard')) active @endif d-flex align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-speedometer2"></i>Dashboard
                                </a>
                            </li>
                            <li class="p-0">
                                <a href="{{ route('admin.stores.index') }}"
                                    class="main-item d-flex  @if (request()->routeIs('admin.stores.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-shop"></i>Stores
                                </a>
                            </li>
                            <li class="p-0">
                                <a href="{{ route('admin.users.index') }}"
                                    class="main-item d-flex  @if (request()->routeIs('admin.users.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-person"></i>Buyers
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.categories.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-bookmark"></i>Categories
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.shipping-providers.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.shipping-providers.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-postage"></i>Shipping Providers
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.audits.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.audits.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-border-width"></i>Audits
                                </a>
                            </li>
                            <li class="p-0 ">
                                @php
                                    $pageTitle = $title ?? '';
                                @endphp
                                <a href="{{ route('admin.sales.index') }}"
                                    class="main-item d-flex  @if (request()->routeIs('admin.sales.index') || $pageTitle == 'Sales List') active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-receipt"></i>Sales
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.products.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.products.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-diagram-3"></i>Items
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.support.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.support.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-diagram-3"></i>Support Tickets
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.transactions.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.transactions.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-diagram-3"></i>Transactions
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="mainContent" class="col-12 p-1 hero-div-partition" style="width: 80%">
                {{-- left offcanvas  --}}
                <button class="btn btn-lg d-block d-md-none catergory-button" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                    <i class="bi bi-list"></i>
                </button>
                <div class="offcanvas offcanvas-start position-absolute" data-bs-backdrop="static" tabindex="-1"
                    id="staticBackdrop" aria-labelledby="staticBackdropLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="staticBackdropLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="categories-list">
                            <li class="categories p-0"></li>
                            <li class="p-0 mb-2">
                                <a href="{{ route('admin.dashboard') }}" class="main-item d-flex align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-speedometer2"></i>Dashboard
                                </a>
                            </li>
                            <li class="p-0 mb-2">
                                <a href="{{ route('admin.stores.index') }}"
                                    class="main-item d-flex align-items-center" style="gap: 10px">
                                    <i class="bi bi-speedometer2"></i>Stores
                                </a>
                            </li>
                            <li class="p-0 mb-2">
                                <a href="{{ route('admin.users.index') }}"
                                    class="main-item d-flex align-items-center" style="gap: 10px">
                                    <i class="bi bi-person"></i>Buyers
                                </a>
                            </li>
                            <li class="p-0 mb-2">
                                <a href="#" class="main-item d-flex align-items-center" style="gap: 10px">
                                    <i class="bi bi-speedometer2"></i>Categories
                                </a>
                            </li>
                            <li class="p-0 mb-2">
                                <a href="{{ route('admin.shipping-providers.index') }}"
                                    class="main-item d-flex align-items-center" style="gap: 10px">
                                    <i class="bi bi-speedometer2"></i>Shipping Providers
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.shipping-providers.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.shipping-providers.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-postage"></i>Shipping Providers
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.audits.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.audits.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-border-width"></i>Audits
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.sales.index') }}"
                                    class="main-item d-flex  @if (request()->routeIs('admin.sales.index') || $pageTitle == 'Sales List') active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-receipt"></i>Sales
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.products.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.products.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-diagram-3"></i>Items
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.support.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.support.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-diagram-3"></i>Support Tickets
                                </a>
                            </li>
                            <li class="p-0 ">
                                <a href="{{ route('admin.transactions.index') }}"
                                    class="main-item d-flex @if (request()->routeIs('admin.transactions.index')) active @endif align-items-center"
                                    style="gap: 10px">
                                    <i class="bi bi-diagram-3"></i>Transactions
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- dynamic part html --}}
                <div class="dynamic-part p-0 m-0 w-100" style="height: 91vh;overflssow:hidden!important">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- bootstrap cdn links --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    {{-- jquery dataTables js --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    {{-- toastr js cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

    <script>
        var collapsed = false;
        // var sidebarController = document.querySelector('#sidebarController');
        // var categoriesList = document.querySelector('.categories-list');
        $(document).ready(function() {
            var collapsed = false;

            $('#sidebarController').click(function() {
                if (collapsed) {
                    // Code to execute when the sidebar is closed
                    $('#mainContent').animate({
                        width: '80%',
                    })
                    $('#sidebar').animate({
                        width: '20%',
                    })
                    $('.categories-list').show();

                } else {
                    // Code to execute when the sidebar is open
                    $('#sidebar').animate({
                        width: '0%',
                    })
                    $('#mainContent').animate({
                        width: '100%',
                    })
                    $('.categories-list').hide();
                }

                // Toggle the collapsed state
                collapsed = !collapsed;
            });
        });
    </script>
    @yield('js')
</body>

</html>
