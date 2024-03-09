@extends('layouts.buyer', ['title' => 'Buyer Dashboard'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
@endsection
@section('content')
    <div class="w-100 h-100">
        <div class="w-100 h-15 d-flex align-items-center justify-content-start ps-4" style="background-color: #105082">
            <h2>Dashboard</h2>
        </div>
        <div class="w-100 h-auto h-md-85 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="w-80 m-auto">
                <h1 class="text-center">Hello <span class="user__name px-4">{{ Auth::user()->name }}</span>, Welcome to the Buyer Dashboard</h1>
                <p class="text-center">Thanks for registering your account! We know that you are keen to start bidding right
                    away but we
                    recommend
                    you complete a couple of extra steps to unlock all the features of your verification. It will take just
                    a
                    few moments.</p>
                <a href="/" class="anchor-button m-auto d-block text-white rounded-2"
                    style="background-color: #105082">Browse</a>
            </div>
        </div>
    </div>
@endsection
