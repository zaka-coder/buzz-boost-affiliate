@extends('layouts.admin')
@section('css')
    <style>
        .anchor-button {
            width: 90px;
            height: 30px;
            line-height: 30px;
            border-radius: 4px;
            color: white !important;
            border: none
        }

        .anchor-button.ban {
            background-color: rgba(255, 0, 0, 0.7);

        }

        .anchor-button.active {
            background-color: rgba(11, 126, 11, 0.87);
            padding: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Items List</h4>
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
                    <form action="{{ route('admin.items.filter.id') }}" method="POST">
                        @csrf
                        <input type="text" id="searchById" name="itemId" class="form-control"
                            placeholder="Filter by auction id..." value="{{ $itemId ?? '' }}">
                    </form>
                </div>
                <div class="col-md-2 pe-1">
                    <form action="{{ route('admin.items.filter.item') }}" method="POST">
                        @csrf
                        <input type="text" id="searchByItem" name="itemName" class="form-control"
                            placeholder="Filter by item..." value="{{ $itemName ?? '' }}">
                    </form>
                </div>
                <div class="col-md-2 pe-1">
                    <form action="{{ route('admin.items.filter.store') }}" method="POST">
                        @csrf
                        <input type="text" id="searchByStore" name="storeName" class="form-control"
                            placeholder="Filter by store..." value="{{ $storeName ?? '' }}">
                    </form>

                </div>
                <div class="col-md-2 pe-1">
                    <input type="text" id="searchByDate" class="form-control" placeholder="Filter by date..." readonly
                        onclick="openDatePickerModal();">
                </div>
                <div class="col-md-2 pe-1">
                    @php
                        $selectedSaleType = $saleType ?? '';
                    @endphp
                    <form id="saleType_form" action="{{ route('admin.items.filter.saleType') }}" method="POST">
                        @csrf
                        <select name="saleType" id="saleType" class="form-control">
                            <option value="">Filter by sale type...</option>
                            <option value="auction" {{ $selectedSaleType == 'auction' ? 'selected' : '' }}>Auction
                            </option>
                            <option value="buy-it-now" {{ $selectedSaleType == 'buy-it-now' ? 'selected' : '' }}>
                                Buy It Now</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-2">
                    @php
                        $selectedStatus = $status ?? '';
                    @endphp
                    <form id="status_form" action="{{ route('admin.items.filter.status') }}" method="POST">
                        @csrf
                        <select name="status" id="statusSelect" class="form-control">
                            <option value="">Filter by status...</option>
                            <option value="active" {{ $selectedStatus == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="sold" {{ $selectedStatus == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="inactive" {{ $selectedStatus == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive  py-3">
            <table id="table" class="table table-bordered table-striped w-100" border="1">
                <thead class="w-100">
                    <tr class="w-100">
                        <th class="w-5">#</th>
                        <th class="w-5">Auction ID</th>
                        <th class="w-20">Name</th>
                        <th class="w-10">Store</th>
                        <th class="w-15">Sale Type</th>
                        <th class="w-15">Listing Type</th>
                        <th class="w-10">Date</th>
                        <th class="w-10">Status</th>
                        {{-- <th class="w-10">Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>GM{{ $product->id ?? 'None' }}</td>
                            <td><a href="{{ route('products.show', $product->id) }}"
                                    class="text-decoration-none">{{ $product->name ?? '' }}</a></td>
                            <td>{{ $product->store->name ?? '' }}</td>
                            <td>{{ $product->productListing->item_type == 'auction' ? 'Auction' : 'Buy It Now' }}</td>
                            <td>{{ $product->productListing->listing_type ?? '' }}</td>
                            <td>{{ $product->created_at->format('d M, Y') ?? '' }}</td>
                            <td class="text-capitalize">
                                {{-- @if ($product->is_sold)
                                    <a href="javascript:void(0)" type="button" class="anchor-button ban">
                                        {{ $product->status }}</a>
                                @else --}}
                                    <a href="javascript:void(0)" type="button"
                                        class="anchor-button @if ($product->status == 'active') active @else ban @endif"
                                        data-bs-toggle="modal" data-bs-target="#changeStatusModal{{ $product->id }}"
                                        title="Change Status">{{ $product->status }}</a>
                                {{-- @endif --}}
                            </td>
                            {{-- <td><span class="d-flex align-items-center justify-content-center">

                                    <button class="btn btn-outline-danger btn-sm rounded-circle" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $product->id }}" title="Delete">
                                        <i class="bi bi-trash"></i></button>
                                </span>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('overlay')
    @foreach ($products as $product)
        <!-- Change Status Modal -->
        <div class="modal fade" id="changeStatusModal{{ $product->id }}" tabindex="-1"
            aria-labelledby="changeStatusModal{{ $product->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-center">
                <div class="modal-content">
                    <form action="{{ route('admin.products.update.status', $product->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeStatusModal{{ $product->id }}Label">Change Status
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="sold" {{ $product->status == 'sold' ? 'selected' : '' }}>Sold
                                    </option>
                                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> --}}
                            {{-- <button type="submit" class="btn btn-primary">Update</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Product Modal -->
        {{-- <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1"
            aria-labelledby="deleteModal{{ $product->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal{{ $product->id }}Label">Delete product
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this product?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
    @endforeach

    {{-- Date Picker Modal --}}
    <div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <form action="{{ route('admin.items.filter.date') }}" method="POST">
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
                searching: false,
            });

            // change status of the item
            $('#status').change(function() {
                // check if the status is selected
                if ($(this).val() !== '') {
                    $(this).closest('form').submit();
                }
            });

            // filter by status
            $('#statusSelect').on('change', function() {
                // check if the status is selected
                if ($(this).val() !== '') {
                    $('#status_form').submit();
                }
            })

            // filter by saleType
            $('#saleType').on('change', function() {
                // check if the status is selected
                if ($(this).val() !== '') {
                    $('#saleType_form').submit();
                }
            })
        });
    </script>
@endsection
