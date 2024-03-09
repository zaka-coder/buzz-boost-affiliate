@extends('layouts.admin')
@section('css')
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }
    </style>
@endsection
@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Store Details</h4>
    </div>
    <div class="row my-4 mx-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.stores.index') }}" class="anchor-button rounded-2" style="width: 100px">Back</a>
            </div>
        </div>
    </div>
    <div class="text-start m-3">
        <div class="col-md-12">
            <div class="row">
                <!-- Owner Details -->
                <div class="col-md-4">
                    <div class="row me-2">
                        <h5>Owner Details</h5>
                        <div class="card p-5" style="height: auto !important">
                            <div class="">
                                <img src="{{ $store->user->profile ? asset($store?->user?->profile?->image ?? 'assets/buyer-assets/user-icon.png') : asset('assets/buyer-assets/user-icon.png') }}"
                                    class="rounded-circle" alt="..." width="120px" height="120px"
                                    style="object-fit:cover;object-position:top center;">
                            </div>
                            <div class="card-body">
                                <p><strong>Owner Name: </strong>
                                    {{ $store->user->name ?? '' }}</p>
                                <p><strong>Email: </strong>
                                    {{ $store->user->email ?? '' }}</p>
                                <p> <strong>Phone: </strong>
                                    {{ $store->user->profile->phone ?? '' }}</p>
                                <p><strong>Address: </strong>
                                    {{ $store->user->profile->address ?? '' }}</p>
                                {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Store Details -->
                <div class="col-md-8">
                    <div class="row ms-2">
                        <h5>Store Details</h5>
                        <div class="card p-5" style="height: auto !important">
                            <div class="">
                                <img src="{{ asset($store->image) ?? '' }}" class="rounded-circle" alt="..."
                                    width="120px" height="120px" style="object-fit:cover;object-position:center">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Store Name: </strong>
                                            {{ $store->name }}</p>
                                        <p><strong>Email: </strong>
                                            {{ $store->email ?? '' }}</p>
                                        <p> <strong>Phone: </strong>
                                            {{ $store->phone ?? '' }}</p>
                                        <p><strong>Address: </strong>
                                            {{ $store->address ?? '' }}</p>
                                        <p><strong>City: </strong>
                                            {{ $store->city ?? '' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>State: </strong>
                                            {{ $store->state ?? '' }}</p>
                                        <p><strong>Country: </strong>
                                            {{ $store->country ?? '' }}</p>
                                        <p><strong>Bussiness or Personal Website: </strong>
                                            {{ $store->website ?? '' }}</p>
                                        <p><strong>Registered: </strong>
                                            {{ $store->registered ? 'Yes' : 'No' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products List -->
    <div class="text-start m-5">
        <div class="col-md-12">
            <div class="row">
                <h5>Store Products List</h5>
                <hr>
                <div class="table-responsive py-3">
                    <table id="table" class="table table-bordered table-striped" border="1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Store</th>
                                <th>Sale Type</th>
                                <th>Listing Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($store->products as $product)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td><a href="{{ route('products.show', $product->id) }}"
                                            class="text-decoration-none">{{ $product->name ?? '' }}</a></td>
                                    <td>{{ $product->store->name ?? '' }}</td>
                                    <td>{{ $product->productListing->item_type == 'auction' ? 'Auction' : 'Buy It Now' }}
                                    </td>
                                    <td>{{ $product->productListing->listing_type ?? '' }}</td>
                                    <td>{{ $product->created_at->format('d M, Y') ?? '' }}</td>
                                    <td class="text-capitalize"><span
                                            class="d-block text-center badge bg-{{ $product->status == 'active' ? 'success' : 'danger' }}">{{ $product->status }}</span></td>
                                    {{-- <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button class="btn btn-outline-danger btn-sm rounded-circle"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i></button>
                                        </div>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('overlay')
    @foreach ($store->products as $product)
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                    <option value="block" {{ $product->status == 'block' ? 'selected' : '' }}>Block
                                    </option>
                                    <option value="ban" {{ $product->status == 'ban' ? 'selected' : '' }}>Ban</option>
                                    <option value="sold" {{ $product->status == 'sold' ? 'selected' : '' }}>Sold</option>
                                    <option value="out of stock"
                                        {{ $product->status == 'out of stock' ? 'selected' : '' }}>Out of Stock</option>
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                //
            });

            $('#status').change(function() {
                $(this).closest('form').submit();
            })
        });
    </script>
@endsection
