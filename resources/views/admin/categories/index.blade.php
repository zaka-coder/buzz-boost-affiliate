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
            border-radius: 5px;
            background-color: #105082;
            color: white
        }
    </style>
@endsection

@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Categories</h4>
    </div>
    <div class="row my-4 mx-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.categories.create') }}" class="anchor-button">Add Category</a>
            </div>
        </div>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive  py-3">
            <table id="table" class="table  table-bordered table-striped" border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Parent</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <th><img src="{{ asset($category->image) }}" alt="..." width="50px" height="50px" style="display: block" class="m-auto"></th>
                            <td>
                                {{-- <a href="{{ route('admin.categories.show', $category->id) }}"
                                    class="text-decoration-none"> --}}
                                    {{ $category->name ?? 'None' }}
                                {{-- </a> --}}
                            </td>
                            <td>{{ $category->description ?? 'None' }}</td>
                            <td>{{ $category->parent->name ?? 'None' }}</td>
                            <td class="">
                                <div class="w-100 d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-circle">
                                        <i class="bi bi-pencil"></i></a>
                                    <button class="btn btn-outline-danger btn-sm rounded-circle" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $category->id }}">
                                        <i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('overlay')
    @foreach ($categories as $category)
        {{-- delete modal --}}
        <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1"
            aria-labelledby="deleteModal{{ $category->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal{{ $category->id }}Label">Delete Category
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this category?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
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
