{{-- @extends('layouts.app') --}}
@extends('layouts.guest', ['title' => 'Verify Email'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-profile.css') }}">
@endsection

@section('content')
    {{-- email slide html --}}
    <div class="email-slide ">
        <div class="email-main">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <div class="email-header-part ">
                <img src="{{ asset('assets/buyer-assets/email.png') }}" alt="">
            </div>
            <div class="email-body-part ">
                <p>Before proceeding, please check your email for a verification link.</p>
                <p>If you did not receive the email,
                    <button type="button" class="btn btn-link p-0 m-0 align-baseline"
                        onclick="document.getElementById('resend-form').submit()">click here to request another</button>
                </p>
            </div>
            <!-- this form will be used to send the email again -->
            <form id="resend-form" class="" method="POST" action="{{ route('verification.resend') }}">
                @csrf
            </form>
        </div>
    </div>
@endsection
