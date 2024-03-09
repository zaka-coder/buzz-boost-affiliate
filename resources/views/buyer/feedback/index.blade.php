@extends('layouts.buyer', ['title' => 'Feedback'])
@section('css')
    <style>
        .nunito {
            font-family: nunito;
            font-size: 16px;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        .active {
            background-color: #105082;
            color: white
        }
    </style>
@endsection
@section('content')
    <!-- Header section within the bids container -->
    <div class="w-100 h-15" style="background-color: #f8f7fa!important">
        <!-- Filter options for orders status -->
        <div class="w-100 h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
            <a href="{{ route('buyer.feedbacks.index') }}" class="anchor-button active rounded-2">Feedbacks History</a>
            <a href="{{ route('buyer.feedbacks.awaiting') }}" class="anchor-button rounded-2"
                style="width: 200px !important">Awaiting Feedbacks</a>
        </div>
    </div>
    <!-- Main content area for displaying individual feedbacks -->
    @include('layouts.includes.feedbacks', ['user' => 'buyer'])
@endsection
@section('js')
@endsection
