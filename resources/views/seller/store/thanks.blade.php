@extends('layouts.buyer', ['title' => 'Thank You'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/seller-css/seller-profile.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection
@section('content')
        {{-- welcome slide html --}}
        <div class="carousel-item active welcome-slide">
            <div class="rock-center">
                <img src="{{ asset('assets/buyer-assets/thanks.png') }}" alt="">
            </div>
            <div class="welcome-user">
                <h1 class="text-center">Thank you!</h1>
                <p class="text-center">Your Seller account details have been submitted successfully!</p>
                <p class="text-center">Please wait for 24 hrs to approve your request.</p>
            </div>
            <div class="welcome-footer">
                <a href="/"><button class="started" type="button" class="next">
                    Home
                </button></a>
            </div>
        </div>
@endsection
