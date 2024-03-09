@extends('layouts.admin')
@section('css')
    <style>
        th {
            font-family: nunito;
        }

        td {
            font-family: nunito-regular;
        }

        .audit_status {
            display: block;
            text-align: center;
            border-radius: 6px;
            color: black;
            margin: auto;
            background-color: rgba(103, 212, 103, 0.445);
        }
    </style>
@endsection

@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Audit List</h4>
    </div>
    <div class="row my-4 mx-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <div class="col-md-3">
                    @php
                        $selectedStatus = $status ?? '';
                    @endphp
                    <form id="status_form" action="{{ route('admin.audits.filter.status') }}" method="POST">
                        @csrf
                        <select name="status" id="status" class="form-control">
                            <option value="">Filter by status...</option>
                            <option value="completed" {{ $selectedStatus == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="pending"
                                {{ $selectedStatus == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive h-100">
            <table id="table" class="table table-bordered table-striped w-100" border="1">
                <thead class="w-100">
                    <tr class="w-100">
                        <th class="w-5">#</th>
                        <th class="w-15">User</th>
                        <th class="35">Item</th>
                        <th class="w-15">Request Date</th>
                        <th class="w-15">Status</th>
                        <th class="w-15">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td><a href="{{ route('admin.users.show', $audit->user->id) }}"
                                    class="text-decoration-none">{{ $audit->user->name ?? '' }}</a></td>
                            <td><a href="{{ route('products.show', $audit->product->id) }}"
                                    class="text-decoration-none">{{ $audit->product->name ?? '' }}</a></td>
                            <td>{{ $audit->created_at->format('d M, Y') ?? '' }}</td>
                            <td class="text-capitalize"><span class="audit_status">{{ $audit->status ?? '' }}</span></td>
                            <td class="d-flex align-items-center justify-content-center gap-2"><a
                                    href="{{ route('admin.audits.response', $audit->id) }}"
                                    class="btn btn-outline-primary btn-sm rounded-circle"
                                    title="{{ $audit->status == 'pending' ? 'Respond' : 'Edit' }}">
                                    @if ($audit->status == 'pending')
                                        <i class="bi bi-reply" style="color: green;"></i>
                                    @else
                                        <i class="bi bi-pencil-square"></i>
                                    @endif
                                </a>
                                {{-- <a href="#"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i></a> --}}
                                <button class="btn btn-outline-danger btn-sm rounded-circle" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $audit->id }}" title="Delete">
                                    <i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

{{-- @section('overlay')
    @foreach ($audits as $audit)
        <div class="modal fade" id="deleteModal{{ $audit->id }}" tabindex="-1"
            aria-labelledby="deleteModal{{ $audit->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal{{ $audit->id }}Label">Delete audit
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this audit?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('admin.audits.destroy', $audit->id) }}" method="POST">
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
                // searching: false,
            });

            $('#status').on('change', function() {
                // check if the status is selected
                if ($(this).val() !== '') {
                    $('#status_form').submit();
                }
            })
        });
    </script>
@endsection
