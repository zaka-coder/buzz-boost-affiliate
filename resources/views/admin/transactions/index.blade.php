@extends('layouts.admin')
@section('css')
    <style>
        th {
            font-family: nunito;
        }

        td {
            font-family: nunito-regular;
        }
    </style>
@endsection

@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Transactions List</h4>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive h-100">
            <table id="table" class="table table-bordered table-striped" border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Transaction ID</th>
                        <th>Item</th>
                        <th>Store</th>
                        <th>Customer</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $transaction->vendor_payment_id ?? '' }}</td>
                            <td><a href="{{ route('products.show', $transaction->product->id) }}">{{ $transaction->product->name ?? '' }}</a></td>
                            <td><a href="{{ route('admin.stores.show', $transaction->product->store->id) }}">{{ $transaction->product->store->name ?? '' }}</a></td>
                            <td><a href="{{ route('admin.users.show', $transaction->order->user->id) }}">{{ $transaction->order->user->name ?? '' }}</a></td>
                            <td>{{ $transaction->order->payment_method ?? '' }}</td>
                            <td>${{ $transaction->amount ?? '' }}</td>
                            <td>{{ $transaction->created_at->format('d M, Y') ?? '' }}</td>
                            <td class="text-capitalize">{{ $transaction->status ?? '' }}</td>
                            {{-- <td>
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $transaction->id }}" title="Delete">
                                    <i class="bi bi-trash"></i></button>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

{{-- @section('overlay')
    @foreach ($orders as $order)
        <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1"
            aria-labelledby="deleteModal{{ $order->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal{{ $order->id }}Label">Delete order
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this order?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection --}}

@section('js')
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                //
            });
        });
    </script>
@endsection
