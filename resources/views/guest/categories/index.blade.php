@extends('layouts.guest', ['title' => 'Products'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/home/home.css') }}">
    <style>
        .card-start {
            height: 50%;
            width: 100%;
        }

        .card img {
            height: 100% !important;
            width: 100% !important;
            max-width: 100%;
            object-fit: cover !important;
        }

        .card-body-first {
            width: 100% !important;
            height: 30% !important;
        }

        .card-body-first h5 {
            font-size: 17px !important;
        }

        .card-body-second {
            width: 100% !important;
            height: 100% !important;
            display: flex;
            align-items: start;
            justify-content: center;
            padding-top: 20px
        }

        .card-body-second a {
            padding: 5.5px 40.5px;
            background: #EA008B 0% 0% no-repeat padding-box;
            box-shadow: 0px 0px 3px #00000029;
            border-radius: 3px;
            color: white;
            opacity: 1;
        }
    </style>
@endsection
@section('content')
    <div class="category-main">
        <div class="filter">
            <select name="" id="">
                <option value="1">Active</option>
                <option value="2">Not Active</option>
            </select>
            <select name="" id="">
                <option value="1">Certified</option>
                <option value="1">Not Certified</option>
            </select>
            <select name="" id="">
                <option value="1">All</option>
                <option value="1">Not All</option>
            </select>
            <select name="" id="">
                <option value="1">Newest</option>
                <option value="2">Old</option>
            </select>
        </div>
        {{-- all categories cards html --}}
        <div class="row px-2 py-3">
            <div class="products-cards grid_system_rectangle">
                @php
                    $categories = App\Models\Category::all();
                @endphp
                @foreach ($categories as $category)
                    <div class="card">
                        <div class="card-start skeleton">
                            <img src="{{ asset($category->image ?? 'assets/home/stones.jpg') }}" class="card-img-top"
                                alt="...">
                        </div>
                        <div class="card-body">
                            <div class="card-body-first">
                                <h5 class="m-0 p-2 text-center text-truncate skeleton-text  ">{{ strtoupper($category->name) }}</h5>
                            </div>
                            <div class="card-body-second">
                                <a href="{{ route('categories.products', $category->id) }}" class="skeleton">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        {{-- incase if there is no data then this div must show to the user --}}
        {{-- <div class="no-data">
        <div class="no-data-img">
            <img src="{{asset('assets/home/folder.png')}}" alt="">
            <p>No Data</p>
        </div>
    </div> --}}
    </div>
@endsection
