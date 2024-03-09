@extends('layouts.buyer', ['title' => 'Edit Shipping Details'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/cart.css') }}">
@endsection
@section('content')
    <div class="w-100 h-100  p-3" style="background-color:#FFF;overflow:auto">
        <h2 class="text-center text-black mb-3">Update Shipping Details</h2>
        <form method="POST" action="{{ route('buyer.shipping.address.store', $id) }}" class="w-100">
            @csrf
            <h2 class="mx-md-4">Contact Information</h2>
            <div class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-center gap-md-3">
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">Name</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="text" name="name" id="name" value="{{ auth()->user()->name }}"
                            class="form-control w-90 ms-auto d-block h-100">
                    </div>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">Email</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="email" id="email" name="email" class="form-control w-90 ms-auto d-block h-100" value="{{ auth()->user()->email }}">
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            {{-- <div class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-center gap-md-3">
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">Phone</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="tel" id="phone" name="phone" class="form-control w-90 ms-auto d-block h-100" value="{{ auth()->user()->profile->contact }}">
                    </div>
                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="w-100 w-md-45 mt-3">

                </div>

            </div> --}}
            <h2 class="mx-md-4 mt-4">Shipping Address</h2>
            <div class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-center gap-md-3">
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">Search For Your Shipping Address</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="text" id="search_address" name="search_address"
                            class="form-control w-90 ms-auto d-block h-100">
                    </div>
                </div>
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">Address</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="text" id="address" name="address" class="form-control w-90 ms-auto d-block h-100">
                    </div>
                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-center gap-md-3">
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">City</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="text" id="city" name="city" class="form-control w-90 ms-auto d-block h-100">
                    </div>
                    @error('city')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">State</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="text" id="state" name="state"
                            class="form-control w-90 ms-auto d-block h-100">
                    </div>

                    @error('state')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-center gap-md-3">
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">Country</label>
                    {{-- <div class="w-100" style="height:35px">
                        <select class="w-100  d-block h-100">
                            <option value="">Please select</option>
                            <option value="">For 2 days</option>
                        </select>
                    </div> --}}
                    <div class="input-group w-100 " style="height:35px">
                        <input type="text" id="country" name="country"
                            class="form-control w-90 ms-auto d-block h-100">
                    </div>
                    @error('country')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="w-100 w-md-45 mt-3">
                    <label class="contact-input-label">Postal Code</label>
                    <div class="input-group w-100 " style="height:35px">
                        <input type="number" id="postal_code" name="postal_code"
                            class="form-control w-90 ms-auto d-block h-100">
                    </div>
                    @error('postal_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            {{-- hidden fields --}}
            <input type="hidden" id="product_id" name="product_id" value="{{ $product_id ?? '' }}">
            <input type="hidden" id="shipping_provider" name="shipping_provider"
                value="{{ $shipping_provider ?? '' }}">
            <input type="hidden" id="insurance" name="insurance" value="{{ $insurance ?? '' }}">

            <div class="contact-info m-5">
                {{-- <button type="submit" class="px-4 m-auto d-block">Continue</button> --}}
                <button type="submit" class="px-4 m-auto d-block">Save and Continue</button>
            </div>
        </form>
    </div>
@endsection

@section('js')

@endsection
