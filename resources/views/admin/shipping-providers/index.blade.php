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
        <h4 class="m-0" style="margin-bottom: 3px !important">Shipping Providers</h4>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive h-100">
            <table id="table" class="table table-bordered table-striped" border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shippingProviders as $shippingProvider)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $shippingProvider->name ?? 'None' }}</td>
                            <td>{{ $shippingProvider->description ?? 'None' }}</td>
                            {{-- <td class="d-flex align-items-center justify-content-center"> <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                                    <i class="bi bi-pencil"></i></a>
                            </td> --}}
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
                //
            });
        });
    </script>
@endsection
