@extends('layouts.admin')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="py-2 px-4 text-white" style="background: #281d8a">
        <h4 class="m-0" style="margin-bottom: 3px !important">Audit Response</h4>
    </div>
    <div class="row my-4 mx-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.audits.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
    <div class="text-start m-5">
        <div class="col-md-12">
            <form action="{{ route('admin.audits.response.store', $audit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <h3>Response Form</h3>
                <div class="col-md-12 d-flex flex-row justify-content-between flex-wrap my-3">

                    <div class="col-md-6">
                        <div class="p-1">
                            <div>
                                <label for="cert_image" class="form-label">Certificate</label>
                                <input type="file" class="form-control" id="cert_image" name="cert_image">
                                @error('cert_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <p class="mb-2">Item Pictures</p>
                                <input type="radio" class="" id="verified" value="verified" name="product_image" {{ $audit->product_image == 'verified' ? 'checked' : '' }}>
                                <label for="verified" class="form-label">Verified</label>
                                <input type="radio" class="" id="unverified" value="unverified"
                                    name="product_image" {{ $audit->product_image == 'unverified' ? 'checked' : '' }}>
                                <label for="unverified" class="form-label">Unverified</label>
                                @error('product_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <p class="mb-2">Item Description</p>
                                <input type="radio" class="" id="desc-verified" value="verified" name="product_desc" {{ $audit->product_desc == 'verified' ? 'checked' : '' }}>
                                <label for="desc-verified" class="form-label">Verified</label>
                                <input type="radio" class="" id="desc-unverified" value="unverified"
                                    name="product_desc" {{ $audit->product_desc == 'unverified' ? 'checked' : '' }}>
                                <label for="desc-unverified" class="form-label">Unverified</label>
                                @error('product_desc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="form-label">Status</label>
                               <select name="status" id="" class="form-select">
                                <option value="pending" {{ $audit->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $audit->status == 'completed' ? 'selected' : '' }}>Completed</option>
                               </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-1">
                            <label for="comment" class="form-label">Comment<span class="text-danger">*</span></label>
                            <textarea class="form-control" name="comment" id="comment" cols="30" rows="11">{{ $audit->comment ?? '' }}</textarea>
                            @error('comment')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary m-1">Submit</button>
            </form>
        </div>
    </div>
@endsection
