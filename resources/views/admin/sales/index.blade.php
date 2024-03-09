@extends('layouts.admin', ['title' => 'Sales List'])
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
        <h4 class="m-0" style="margin-bottom: 3px !important">Sales List</h4>
    </div>
    <div class="row my-4 mx-3">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="d-flex justify-content-end">
                <div class="col-md-2 pe-1">
                    <form action="{{ route('admin.sales.filter.id') }}" method="POST">
                        @csrf
                        <input type="text" id="searchById" name="itemId" class="form-control"
                            placeholder="Filter by auction id..." value="{{ $itemId ?? '' }}">
                    </form>
                </div>
                <div class="col-md-2 pe-1">
                    <form action="{{ route('admin.sales.filter.item') }}" method="POST">
                        @csrf
                        <input type="text" id="searchByItem" name="itemName" class="form-control"
                            placeholder="Filter by item..." value="{{ $itemName ?? '' }}">
                    </form>
                </div>
                <div class="col-md-2 pe-1">
                    <form action="{{ route('admin.sales.filter.store') }}" method="POST">
                        @csrf
                        <input type="text" id="searchByStore" name="storeName" class="form-control"
                            placeholder="Filter by store..." value="{{ $storeName ?? '' }}">
                    </form>

                </div>
                <div class="col-md-2 pe-1">
                    <form action="{{ route('admin.sales.filter.customer') }}" method="POST">
                        @csrf
                        <input type="text" id="searchByCustomer" name="customerName" class="form-control"
                            placeholder="Filter by customer..." value="{{ $customerName ?? '' }}">
                    </form>
                </div>
                <div class="col-md-2 pe-1">
                    <input type="text" id="searchByDate" class="form-control" placeholder="Filter by date..." readonly
                        onclick="openDatePickerModal();">
                </div>
                <div class="col-md-2">
                    @php
                        $selectedStatus = $status ?? '';
                    @endphp
                    <form id="status_form" action="{{ route('admin.sales.filter.status') }}" method="POST">
                        @csrf
                        <select name="status" id="status" class="form-control">
                            <option value="">Filter by status...</option>
                            <option value="pending" {{ $selectedStatus == 'pending' ? 'selected' : '' }}>Awaiting Payments
                            </option>
                            <option value="paid awaiting shippment"
                                {{ $selectedStatus == 'paid awaiting shippment' ? 'selected' : '' }}>Paid Awaiting Shipment
                            </option>
                            <option value="paid & shipped" {{ $selectedStatus == 'paid & shipped' ? 'selected' : '' }}>Paid
                                & Shipped</option>
                            <option value="cancelled" {{ $selectedStatus == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive h-100">
            <table id="table" class="table table-bordered table-striped" border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Auction ID</th>
                        <th>Item</th>
                        <th>Store</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>GM{{ $order->products->first()->id ?? 'None' }}</td>
                            <td><a
                                    href="{{ route('products.show', $order->products->first()->id) }}">{{ $order->products->first()->name ?? '' }}</a>
                            </td>
                            <td>{{ $order->store->name ?? '' }}</td>
                            <td>{{ $order->user->name ?? '' }}</td>
                            <td>{{ $order->created_at->format('d M, Y') ?? '' }}</td>
                            <td class="text-capitalize">{{ $order->status ?? '' }}</td>
                            {{-- <td>
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $order->id }}" title="Delete">
                                    <i class="bi bi-trash"></i></button>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('overlay')
    <div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <form action="{{ route('admin.sales.filter.date') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="datePickerModalLabel">Select Date
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row gap-2">
                            <div class="col">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" id="startDate" name="startDate" class="form-control">
                            </div>
                            <div class="col">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" id="endDate" name="endDate" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // open date picker modal
        function openDatePickerModal() {
            $('#datePickerModal').modal('show');
        }

        $(document).ready(function() {
            $('#table').DataTable({
                // disable search
                searching: false,
            });

            // $('#searchByItem').on('keyup', function(e) {
            //     // var value = $(this).val().toLowerCase();
            //     if (e.keyCode === 13) {
            //         var search = $('#searchByItem').val();
            //         // validate the search so that it can't be empty and can't be null or undefined or empty string without spaces
            //         if (search === '' || search === null || search === undefined || search.trim() === '') {
            //             toastr.error('Search cannot be empty');
            //             return;
            //         } else {
            //             // submitting the form
            //             // $('#search_form').submit();

            //             // use ajax request to perform the search
            //             $.ajax({
            //                 url: "{{ route('admin.sales.filter.item') }}",
            //                 method: 'POST',
            //                 data: {
            //                     search: search,
            //                     _token: '{{ csrf_token() }}'
            //                 },
            //                 success: function(response) {
            //                     // handle the response
            //                     console.log(response);

            //                     // if (response.search) {
            //                     //     $("#connected_users li:not(:first)").remove();

            //                     //     var users = response.users;
            //                     //     var usersArray = [];
            //                     //     // Check if 'users' is an object and convert it to an array if needed
            //                     //     if (typeof users === 'object' && users !== null) {
            //                     //         // Convert the object to an array
            //                     //         var usersArray = Object.values(users);
            //                     //     } else {
            //                     //         usersArray = users;
            //                     //     }
            //                     //     usersArray.forEach(function(user) {
            //                     //         $("#connected_users").append(
            //                     //             `<li class="my-4">
        //                     //             <a href="{{ url('/${response.role}/messages/${user.id}/show') }}"
        //                     //                 class="connected_user_profile text-dark">
        //                     //                 <img src="{{ asset('assets/buyer-assets/user-icon.png') }}" alt=""
        //                     //                     class="connected_user-img">
        //                     //                 <p class="connected_username">${user.name}</p>
        //                     //             </a>
        //                     //         </li>`
            //                     //         );
            //                     //     });
            //                     // }
            //                     // else {
            //                     //     return;
            //                     // }

            //                 },
            //                 error: function(error) {
            //                     // handle the error
            //                     console.log(error.responseText);
            //                 }

            //             });
            //         }
            //     }
            // })

            $('#status').on('change', function() {
                // check if the status is selected
                if ($(this).val() !== '') {
                    $('#status_form').submit();
                }
            });

        });
    </script>
@endsection
