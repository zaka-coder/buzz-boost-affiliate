@extends('layouts.seller', ['title' => 'Offers'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .status {
            display: block;
            width: fit-content;
            padding: 0px 10px;
            border-radius: 100px;
            color: white;
            font-size: 12px;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the offers section -->
    <div class="bids-main-container w-100 @if ($offers->count() == 0) h-100 @else  h-auto @endif">

        <!-- Header section within the offers container -->
        <div class="w-100 h-auto my-2">

            <!-- Filter options for offer status -->

            <div class="w-100 h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                {{-- <a href="{{ route('seller.offers.index') }}"
                    class="anchor-button rounded-2 @if ($status == null) active @endif">All</a> --}}
                <a href="{{ route('seller.offers.index', 'pending') }}"
                    class="anchor-button rounded-2 @if ($status == null || $status == 'pending') active @endif">Pending</a>
                <a href="{{ route('seller.offers.index', 'accepted') }}"
                    class="anchor-button rounded-2 @if ($status == 'accepted') active @endif">Accepted</a>
                <a href="{{ route('seller.offers.index', 'declined') }}"
                    class="anchor-button rounded-2 @if ($status == 'declined') active @endif">Declined</a>
            </div>
        </div>
        @if (count($offers) > 0)
            <!-- Main content area for displaying individual offers -->
            <div class="w-100 h-85 table-responsive">
                <table class="w-100">
                    <tr class="w-100" style="background-color:#EFF3F7">
                        <td class="w-auto w-md-5  nunito text-center head-before" style="height:45px">#</td>
                        <td class="w-auto w-md-40 nunito text-center head-before" style="height:45px">Product Title</td>
                        <td class="w-auto w-md-20 nunito text-center head-before" style="height:45px">Customer Name</td>
                        <td class="w-auto w-md-10 nunito text-center head-before" style="height:45px">Offer</td>
                        <td class="w-auto w-md-15 nunito text-center head-before" style="height:45px">Date</td>
                        <td class="w-auto w-md-10 nunito text-center head-before" style="height:45px">Status</td>
                    </tr>
                    {{-- Offers list --}}
                    @foreach ($offers as $offer)
                        <tr class="w-100" style="background-color:#FFF;border-top:15px solid  #F8F7FA">
                            <td class="w-auto w-md-5  nunito-regular text-center" style="height:60px">
                                {{ $loop->iteration }}
                            </td>
                            <td class="w-auto w-md-40 nunito-regular text-center" style="height:60px">
                                <a href="{{ route('products.show', $offer->product->id) }}">{{ $offer->product->weight . ' ct ' . $offer->product->name }}</a></td>
                            <td class="w-auto w-md-20 nunito-regular text-center" style="height:60px">
                                {{ $offer->user->name }}</td>
                            <td class="w-auto w-md-10 nunito-regular text-center" style="height:60px">
                                ${{ number_format($offer->offer_value, 2) }}</td>
                            <td class="w-auto w-md-15 nunito-regular text-center" style="height:60px">
                                {{ $offer->created_at->format('d/m/Y') }}</td>
                            <td class="w-auto w-md-10 nunito-regular text-center text-capitalize pe-2" style="height:60px">
                                @if ($offer->status == 'pending')
                                    <form id="statusForm-{{ $offer->id }}"
                                        action="{{ route('seller.offers.update', $offer->id) }}" method="post">
                                        @csrf
                                        <select name="status" id="{{ $offer->id }}">
                                            <option value="pending" @if ($offer->status == 'pending') selected @endif>
                                                Pending
                                            </option>
                                            <option value="accepted" @if ($offer->status == 'accepted') selected @endif>
                                                {{ $offer->status == 'accepted' ? 'Accepted' : 'Accept' }}</option>
                                            <option value="declined" @if ($offer->status == 'declined') selected @endif>
                                                {{ $offer->status == 'declined' ? 'Declined' : 'Decline' }}</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="status"
                                        style="background-color:@if ($offer->status == 'accepted') #28A745 ;@elseif($offer->status == 'declined') #DC3545;@else transparent @endif)">
                                        {{ $offer->status }}
                                    </span @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @else
            <div class="w-100 h-85 d-flex flex-column align-items-center justify-content-center gap-2">
                <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt="" style="filter: invert(1)">
                <p class="nunito">No offers</p>
            </div>
        @endif


    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // update status using ajax
            $('select[name="status"]').on('change', function() {
                var status = $(this).val();
                var offerId = $(this).attr('id');
                // var url = "/seller/offers/" + offerId + "/update";
                // $.ajax({
                //     type: "POST",
                //     url: url,
                //     data: {
                //         status: status,
                //         _token: "{{ csrf_token() }}"
                //     },
                //     success: function(response) {
                //         console.log(response);
                //         location.reload();
                //     },
                //     error: function(xhr, status, error) {
                //         console.log(xhr.responseText);
                //     }
                // })

                // update status using form
                $('#statusForm-' + offerId).submit();
            })
        });
    </script>
@overwrite
