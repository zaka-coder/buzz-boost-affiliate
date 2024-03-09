@extends('layouts.seller', ['title' => 'Feedbacks History'])
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
    @include('layouts.includes.feedbacks', ['user' => 'seller'])
@endsection
@section('js')
@endsection
