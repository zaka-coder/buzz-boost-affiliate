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
        <h4 class="m-0" style="margin-bottom: 3px !important">Buyers List</h4>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive h-100">
            <table id="table" class="table table-bordered table-striped" border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Joining Date</th>
                        {{-- <th>Status</th> --}}
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $user->name ?? '' }}</td>
                            <td>{{ $user->email ?? '' }}</td>
                            <td>{{ $user->profile?->country ?? 'None' }}</td>
                            <td>{{ $user->profile?->state ?? 'None' }}</td>
                            <td>{{ $user->profile?->city ?? 'None' }}</td>
                            <td>{{ $user->created_at->format('d M, Y') ?? '' }}</td>
                            {{-- <td class="text-capitalize">{{ $user->status ?? '' }}</td> --}}
                            {{-- <td>
                                 <a href="#"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i></a>
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $user->id }}" title="Delete">
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
