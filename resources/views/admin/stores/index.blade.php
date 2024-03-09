@extends('layouts.admin')
@section('css')
    <style>
        th {
            font-family: nunito;
        }

        td {
            font-family: nunito-regular;
        }

        .anchor-button {
            width: 90px;
            height: 30px;
            line-height: 30px;
            border-radius: 4px;
            color: white !important;
            border: none
        }

        .anchor-button.reject {
            background-color: rgba(255, 0, 0, 0.7);

        }

        .anchor-button.approve {
            background-color: rgba(11, 126, 11, 0.87);
        }

        .store_status {
            display: block;
            text-align: center;
            border-radius: 6px;
            color: black
        }

        .store_status.Active {
            background-color: rgba(56, 184, 56, 0.63);
        }

        .store_status.Inactive {
            background-color: rgba(255, 0, 0, 0.7);
        }

        .yes_no {
            display: block;
            text-align: center;
            border-radius: 6px;
            color: black;
            width: 50px;
            margin: auto
        }

        .yes_no.yes {
            background-color: rgba(56, 184, 56, 0.63);
        }

        .yes_no.no {
            background-color: rgba(255, 0, 0, 0.7);
        }
    </style>
@endsection
@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Stores</h4>
    </div>
    <div class="row my-4 mx-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <div class="col-md-3">
                    @php
                        $selectedStatus = $status ?? '';
                    @endphp
                    <form id="status_form" action="{{ route('admin.stores.filter.status') }}" method="POST">
                        @csrf
                        <select name="status" id="status" class="form-control">
                            <option value="">Filter by status...</option>
                            <option value="1" {{ $selectedStatus == 1 ? 'selected' : '' }}>Approved
                            </option>
                            <option value="0"
                                {{ $selectedStatus == 0 ? 'selected' : '' }}>Rejected
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
                <thead class="mt-3">
                    <tr class="mt-5">
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Approved</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stores as $store)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td><a href="{{ route('admin.stores.show', $store->id) }}">{{ $store->name }}</a></td>
                            <td>{{ $store->email }}</td>
                            <td>{{ $store->phone }}</td>
                            <td class="text-center">{{ $store->address }}</td>
                            <td>
                                <span class="text-center yes_no {{ $store->approved == 1 ? 'yes' : 'no' }}">
                                    {{ $store->approved == 1 ? 'Yes' : 'No' }}
                                </span>
                            </td>

                            <td><a href="{{ route('admin.stores.approve', $store->id) }}"
                                    class="anchor-button {{ $store->approved == 1 ? 'reject' : 'approve' }}">{{ $store->approved == 1 ? 'Reject' : 'Approve' }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
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
            });
        });
    </script>
@endsection
