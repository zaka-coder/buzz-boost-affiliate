@extends('layouts.seller', ['title' => 'Blocked Users'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the offers section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Main content area for displaying individual offers -->
        <div class="w-100 h-100 table-responsive">
            @if ($blocked_users->count() > 0)
                <table class="w-100">
                    <tr class="w-100" style="background-color:#EFF3F7">
                        <td class="w-auto w-md-5  nunito text-center head-before" style="height:45px">#</td>
                        <td class="w-auto w-md-55 nunito text-center head-before" style="height:45px">User Name</td>
                        <td class="w-auto w-md-25 nunito text-center head-before" style="height:45px">Date</td>
                        <td class="w-auto w-md-15 nunito text-center head-before" style="height:45px">Action</td>
                    </tr>
                    {{-- Blocked users list --}}
                    @foreach ($blocked_users as $user)
                        <tr class="w-100" style="background-color:#FFF;border-top:15px solid  #F8F7FA">
                            <td class="w-auto w-md-5  nunito-regular text-center" style="height:60px">{{ $loop->iteration }}
                            </td>
                            <td class="w-auto w-md-55 nunito-regular text-center" style="height:60px">
                                {{ $user->name ?? 'N/A' }}
                            </td>
                            <td class="w-auto w-md-25 nunito-regular text-center" style="height:60px">
                                {{ $user->blocked_at }}
                            </td>
                            <td class="w-auto w-md-15 nunito-regular text-center text-capitalize" style="height:60px">
                                <button class="text-danger bg-transparent border border-1 border-danger rounded-1 px-2"
                                    onclick="document.getElementById('unblock-form-{{ $user->id }}').submit()"><i
                                        class="bi bi-unlock"></i> Unblock</button>
                                <form id="unblock-form-{{ $user->id }}"
                                    action="{{ route('seller.users.unblock', $user->id) }}" method="POST">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No blocked bidders found</p>
                </div>
            @endif
        </div>

    </div>
@endsection
