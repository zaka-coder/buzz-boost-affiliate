@extends('layouts.guest', ['title' => 'Categories'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/home/home.css') }}">
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
        {{-- first category cards html --}}
        <div class="row px-2 py-3 ruby-row">
            <div class="category-name">Ruby</div>
            <div class="products-cards">
                @for ($i = 1; $i < 9; $i++)
                    <div class="card">
                        <img src="{{ asset('assets/home/stones.jpg') }}" class="card-img-top" alt="...">
                        <span class="discount-tag">25 % off</span>
                        <a href="#" class="products-card-link">
                            <div class="card-body">
                                <div class="card-body-first">
                                    <h5 class="m-0 p-2 text-start text-truncate">0.61 Fancy Intense Browning and is </h5>
                                </div>
                                <div class="card-body-second px-2 pt-1">
                                    <p class="">Shape: <span>Round</span></p>
                                    <p class="">Quantity: <span>12</span></p>
                                    <p class="">Color: <span>Red</span></p>
                                </div>
                            </div>
                        </a>
                        <div class="card-footer">
                            <div class="footer-icons-part px-2 pt-1">
                                <i class="bi bi-hammer"></i>
                                <i class="bi bi-shield-check"></i>
                                <i class="bi bi-bag"></i>
                            </div>
                            <div class="footer-price-part">
                                <p>$5,0000</p>
                            </div>
                            <div class="footer-time-part">
                                <p>33 h 55min</p>
                            </div>
                        </div>
                    </div>
                @endfor
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
