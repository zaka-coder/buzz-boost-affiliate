@extends('layouts.seller', ['title' => 'Sales Summary'])
@section('css')
    <style>
        .nunito {
            font-family: nunito;
            font-size: 14px;
        }

        .nunito-regular {
            font-family: nunito-regular;
            font-size: 14px;
        }
    </style>
@endsection
@section('content')
    <section class="w-100 h-100">
        <div class="row">
            <div class="col-md-7  p-3">
                <h2 class="nunito" style="font-size: 26px">Sales Summary</h2>
                <table class="table table-bordered">
                    <tr>
                        <td colspan="2"
                            style="font-size:23px;background-color:#105082;color
                        :white!important">
                            Summary</td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">
                            Number of Items
                        </td>
                        <td class="nunito-regular w-70">{{ $order->products->count() }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">Sale Amount</td>
                        <td class="nunito-regular w-70">${{ $order->total ?? '' }}</td>
                    </tr>
                    {{-- <tr>
                        <td class="nunito w-30">Bids</td>
                        <td class="nunito-regular w-70">$23</td>
                    </tr> --}}
                    <tr>
                        <td class="nunito w-30">Purchase Type</td>
                        <td class="nunito-regular w-70 text-capitalize">
                            {{-- {{ $order->won_via ?? 'N/A' }} --}}
                            @if ($order?->won_via == 'auction')
                                Auction
                            @elseif ($order?->won_via == 'offer')
                                Make an Offer
                            @else
                                Add to Cart
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">Status</td>
                        <td class="nunito-regular w-70 text-capitalize">
                            {{ $order->status === 'pending' ? 'Awaiting Payments' : $order->status ?? '' }}</td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <tr>
                        <td colspan="2"
                            style="font-size:23px;background-color:#105082;color
                        :white!important">
                            Items Details</td>
                    </tr>
                    @foreach ($order->products as $product)
                    <tr>
                        <td class="nunito w-30">
                            {{ $product->productListing->item_type == 'auction' ? 'Auction' : 'Item' }} ID
                        </td>
                        <td class="nunito-regular w-70">GM{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">Title</td>
                        <td class="nunito-regular w-70">${{ $product->weight . ' (Ct) ' . $product->name }}</td>
                    </tr>
                    @endforeach

                </table>
            </div>
            <div class="col-md-5 p-3">
                <h2 class="nunito" style="font-size: 26px">&nbsp;</h2>
                <table class="table border border-1">
                    <tr>
                        <td colspan="2"
                            style="font-size:23px;background-color:#105082;color
                        :white!important">
                            Buyer Details</td>
                    </tr>
                    <tr>
                        <td class="nunito w-100">{{ $order->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-100">{{ $order->shipping_email }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-100">{{ $order->shipping_address }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-100">{{ $order->shipping_country }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-100">{{ $order->shipping_city . ' ' . $order->shipping_postal_code }}</td>
                    </tr>

                </table>
                <table class="table border border-1">
                    <tr>
                        <td colspan="2"
                            style="font-size:23px;background-color:#105082;color
                        :white!important">
                            Feedback</td>
                    </tr>
                    <tr>
                        <td class="nunito w-100">
                            {{ $user->feedbacks->where('product_id', $order->products->first()?->id ?? '')->first() ? $user->feedbacks->where('product_id', $order->products->first()?->id ?? '')->first()->feedback : 'No feedback' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row p-3">
            <div class="col-12">
                <table class="table table-bordered">
                    <tr>
                        <td colspan="2"
                            style="font-size:23px;background-color:#105082;color
                        :white!important">
                            Shipment Details</td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">Registered Shipping</td>
                        <td class="nunito-regular w-70 text-uppercase">
                            {{ $order->shippingProvider->shipping_provider ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">Shipped</td>
                        <td class="nunito-regular w-70">
                            {{ $order->status === 'paid & shipped' ? $order?->ship_date?->format('d M Y') ?? 'Yes' : 'No' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">ETA</td>
                        <td class="nunito-regular w-70">N/A</td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">Postal Insurance</td>
                        <td class="nunito-regular w-70">{{ $order->postal_insurance > 0 ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td class="nunito w-30">Tracking Number</td>
                        <td class="nunito-regular w-70">{{ $order->tracking_number ?? '' }}</td>
                    </tr>
                    <!-- incase of no data  -->
                    {{-- <tr>
                        <td class="nunito-regular w-100">No invoice record associated with this auction</td>
                    </tr> --}}
                </table>
            </div>
        </div>
        @if ($order->transaction)
            <div class="row p-3">
                <div class="col-12">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="2"
                                style="font-size:23px;background-color:#105082;color
                        :white!important">
                                Payment Details</td>
                        </tr>
                        <tr>
                            <td class="nunito w-30">Transaction ID</td>
                            <td class="nunito-regular w-70">{{ $order->vendor_order_id }}</td>
                        </tr>
                        <tr>
                            <td class="nunito w-30">Payment Method</td>
                            <td class="nunito-regular w-70">{{ $order->payment_method }}</td>
                        </tr>
                        <tr>
                            <td class="nunito w-30">Transaction Amount</td>
                            <td class="nunito-regular w-70">${{ $order->total }}</td>
                        </tr>
                        <tr>
                            <td class="nunito w-30">Transaction Date</td>
                            <td class="nunito-regular w-70">{{ $order->transaction->created_at->format('d M Y') }}</td>
                        </tr>
                        <!-- incase of no data  -->
                        {{-- <tr>
                        <td class="nunito-regular w-100">No invoice record associated with this auction</td>
                    </tr> --}}
                    </table>
                </div>
            </div>
        @endif

    </section>
@endsection


@section('js')
@endsection
