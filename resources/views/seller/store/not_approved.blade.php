@extends('layouts.seller', ['title' => 'Store'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/seller-css/seller-profile.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection
@section('content')
        {{-- welcome slide html --}}
        <div class="carousel-item active welcome-slide">
            <div class="rock-center">
                <img src="{{ asset('assets/buyer-assets/rock.png') }}" alt="">
            </div>
            <div class="welcome-user">
                <h1 class="text-center">Welcome to Seller Account</h1>
                <p class="text-center">Your seller account request has been received. Please wait while admin approves your request.</p>
                <p class="text-center">Thank you</p>
            </div>
            <div class="welcome-footer">
                <a href="/"><button class="started" type="button" class="next">
                    Home
                </button></a>
            </div>
        </div>
@endsection
