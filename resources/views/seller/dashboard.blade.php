@extends('layouts.seller', ['title' => 'Seller Dashboard'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/seller-css/dashboard.css') }}">
@endsection
@section('content')
    <div class="dashboard">
        <div class="dashboard-header d-flex align-items-center">
            <h1 class="ps-3 pt-2">Dashboard</h1>
        </div>
        <div class="dashboard-body d-flex align-items-center">
            <div class="dashboard-body-first p-5">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th class="text-center">Count</th>
                            <th class="text-center">Plan Maximum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Auction Items</th>
                            <td>{{ $store->auctions_items ?? 'None' }}</td>
                            <td>{{ $store->plan->auctions_items ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>Buy It Now Items</th>
                            <td>{{ $store->buyitnow_items ?? 'None' }}</td>
                            <td>{{ $store->plan->buyitnow_items ?? 'None' }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Closed Auctions</th>
                            <td>{{ $store->closed_auctions ?? 'None' }}</td>
                            <td>{{ $store->plan->auctions_items ?? 'None' }}</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
            <div class="dashboard-body-second p-5">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th class="text-center">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Open Auctions</th>
                            <td>{{ $open_auctions?->count() ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>Closed Auctions</th>
                            <td>{{ $store->closed_auctions ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>No Reserve</th>
                            <td>{{ $no_reserve?->count() ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>Total Offers</th>
                            <td>{{ $offers->count() ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>Boost</th>
                            <td>{{ $boosted?->count() ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>Premium</th>
                            <td>{{ $premium?->count() ?? 'None' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
