@extends('layouts.admin')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Add Category</h4>
    </div>
    <div class="row my-4 mx-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.categories.index') }}" class="anchor-button rounded-2" style="width: 100px">Back</a>
            </div>
        </div>
    </div>
    <div class="text-start m-3">
        <div class="">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12 d-flex flex-row justify-content-between flex-wrap my-3">
                    <div class="col-md-6">
                        <div class="p-1">
                            <div class="mb-4">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select class="form-control" id="parent_id" name="parent_id">
                                    <option value="">Select parent if any...</option>
                                    @php
                                        $categories = \App\Models\Category::all();
                                    @endphp
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if (old('parent_id') == $category->id) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-1 mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="9">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="anchor-button rounded-2 text-white"
                    style="background-color: #105082">Submit</button>
            </form>
        </div>
    </div>
@endsection
