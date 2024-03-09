@extends('layouts.seller', ['title' => 'Edit Item'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/seller-css/product.css') }}">
    {{-- summer notes cdn link --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editable {
            min-height: 200px;
        }

        .transition {
            transition: all 0.5s ease-in-out;
        }

        .selected-category {
            background-color: rgb(204, 204, 204) !important;
            color: black !important;
        }

        .selected {
            background-color: rgb(185, 185, 185) !important;
            color: black !important;
        }
    </style>
@endsection

@section('content')
    <div class="w-100 h-auto p-2 p-md-5" style="background-color: #FFF">
        <!-- Title and header section -->
        <div class="">
            <h2>Edit Item</h2>
            <h3 class="mt-4 m-0 nunito" style="font-size: 19px">Item Details</h3>
            <small class="nunito-regular">Template selection and title setting</small>
        </div>

        <!-- Form section for creating a new item -->
        <div class="w-100 h-auto mt-4">
            {{-- Display validation errors if any --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {{-- Loop through each error and display it in a list --}}
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <table class="w-100">
            <form action="{{ route('seller.items.update', $product->id) }}" method="post" enctype="multipart/form-data"
                class="w-100">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_type" value="{{ $product->productListing->item_type }}">
                <tbody class="w-100">
                    <!-- Title input row -->
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Title<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Placeholder for character count --}}
                            <p class="m-0 p-0 text-end" style="height:15px"><span id="title_count">0</span>/75</p>
                            <div class="w-100" style="height:35px">
                                <!-- Title input field -->
                                <input type="text" id="title" name="title" maxlength="75"
                                    class="w-90 ms-auto d-block h-100" value="{{ old('title') ?? $product->name }}">
                            </div>
                            <!-- Error message for title -->
                            <div class="" style="height: 20px">
                                <p id="title_error" class="text-center text-danger"></p>
                            </div>
                        </td>
                    </tr>

                    <!-- Category selection row -->
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Category<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Placeholder for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-100" style="height:35px">
                                <input type="hidden" id="category_id" name="category_id"
                                    value="{{ old('category_id') ?? $product->category_id }}">
                                <input type="text" id="category" name="category"
                                    value="{{ old('category') ?? $product->category->name }}"
                                    class="w-90 ms-auto d-block h-100" autocomplete="off" readonly
                                    onfocus="this.removeAttribute('readonly');">
                                <div id="category_dropdown" class="position-absolute d-none"
                                    style="width:300px;height:auto;background-color:#f8f7fa;z-index:9;left:60px">
                                    @foreach ($categories as $category)
                                        <button data-id="{{ $category->id }}" data-value="{{ $category->name }}"
                                            id="category-{{ $category->id }}"
                                            class="px-3 my-2 border-0 bg-transparent d-block"
                                            style="background-color:rgb(230, 230, 230);color:black;">{{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Additional content (commented out for now) --}}
                            <div class="d-flex flex-column flex-md-row align-items-center pt-0 pt-md-3"
                                style="height: 20px">
                                <p class="w-100 w-md-40 text-center text-md-end" style="line-height:18px">Click to <a
                                        href="javascript:void(0)" class="mt-0 mb-0" data-bs-toggle="modal"
                                        data-bs-target="#categoriesModal">Browse Categories</a></p>
                            </div>
                        </td>
                    </tr>

                    <!-- Thumbnail -->
                    {{-- <tr class="w-100">
                        <td class="w-20 text-start title h-100">Thumbnail</td>
                        <td class="w-80 h-100">
                            <!-- Don't remove this empty p; it's for alignment -->
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-100" style="height:35px">
                                <input type="file" name="thumbnail" class="form-control w-90 ms-auto d-block h-100">
                            </div>
                        </td>
                    </tr> --}}

                    {{-- Upload Gallery --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Upload Gallery</td>
                        <td class="w-80 h-100">
                            {{-- Don't remove this empty p; it's for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-100" style="height:35px">
                                <input type="file" name="gallery[]" accept="image/*" multiple
                                    class="form-control w-90 ms-auto d-block h-100">
                            </div>
                            {{-- Additional content (commented out for now) --}}
                            {{-- <div class="" style="height: 20px">
                             <p class="text-center text-danger">this field is required</p>
                            </div> --}}
                        </td>
                    </tr>

                    {{-- Description --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Description<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Don't remove this empty p; it's for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-90 ms-auto">
                                <textarea name="description" style="min-height:200px;max-height: 400px" id="summernote">{{ old('description') ?? $product->description }}</textarea>
                            </div>
                            {{-- Additional content (commented out for now) --}}
                            {{-- <div class="" style="height: 20px">
                            <p class="text-center text-danger">this field is required</p>
                           </div> --}}
                        </td>
                    </tr>

                    {{-- Weight --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Weight<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Don't remove this empty p; it's for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="input-group w-90 ms-auto" style="height:35px">
                                <input type="number" step="0.01"  name="weight" class="form-control w-90 ms-auto d-block h-100"
                                    value="{{ old('weight') ?? $product->weight }}">
                                <span class="input-group-text border-0">cts</span>
                            </div>
                            {{-- Additional content (commented out for now) --}}
                            {{-- <div class="" style="height: 20px">
                            <p class="text-center text-danger">this field is required</p>
                            </div> --}}
                        </td>
                    </tr>

                    {{-- Dimensions --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Dimensions<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Don't remove this empty p; it's for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="input-group w-20 ms-auto" style="height:35px">
                                    <input type="number" step="0.01"  name="dim_length"
                                        class="form-control w-90 ms-auto d-block h-100" placeholder="Length"
                                        value="{{ old('dim_length') ?? $product->dim_length }}">
                                    <span class="input-group-text border-0 m-0 p-0">mm</span>
                                </div>
                                <div class="input-group w-20 ms-auto" style="height:35px">
                                    <input type="number" step="0.01"  name="dim_width"
                                        class="form-control w-90 ms-auto d-block h-100" placeholder="Width"
                                        value="{{ old('dim_width') ?? $product->dim_width }}">
                                    <span class="input-group-text border-0 p-0 m-0">mm</span>
                                </div>
                                <div class="input-group w-30 ms-auto" style="height:35px">
                                    <input type="number" step="0.01"  name="dim_depth"
                                        class="form-control w-90 ms-auto d-block h-100" placeholder="Depth"
                                        value="{{ old('dim_depth') ?? $product->dim_depth }}">
                                    <span class="input-group-text border-0 p-0 m-0">mm</span>
                                </div>
                            </div>
                            {{-- Additional content (commented out for now) --}}
                            {{-- <div class="" style="height: 20px">
                            <p class="text-center text-danger">this field is required</p>
                            </div> --}}
                        </td>
                    </tr>

                    {{-- Certified Gemstone --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Certified-Gemstone<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Don't remove this empty p; it's for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-90 ms-auto d-flex align-items-center gap-4" style="height:95px">
                                <div class="d-flex flex-column align-items-center" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="certified"
                                        @if ($product->is_certified == 1) checked @endif id="certified-gemstone-yes"
                                        value="1">
                                    <label for="certified-gemstone-yes">Yes</label>
                                </div>
                                <div class="d-flex flex-column align-items-center" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="certified"
                                        id="certified-gemstone-no" @if ($product->is_certified == 0) checked @endif
                                        value="0">
                                    <label for="certified-gemstone-no">No</label>
                                </div>
                            </div>
                            <div class="" style="height: 20px">
                                {{-- <p class="text-center text-danger">this field is required</p> --}}
                            </div>
                        </td>
                    </tr>

                    {{-- Treatment --}}
                    <tr class="w-100 position-relative">
                        <td class="w-20 text-start title h-100 position-absolute top-0 mt-4">Treatment<span
                                class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Don't remove this empty p; it's for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-90 ms-auto" style="height:75px">
                                <span>Choose an option</span>
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="treatment"
                                        @if ($product->treatment == 'Heat Treatment') checked @endif id="heat-treatment"
                                        value="Heat Treatment">
                                    <label for="heat-treatment">Heat Treatment</label>
                                </div>
                            </div>
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" name="treatment" class="form-check-input h-90"
                                        id="Beryllium-treatment" value="Beryllium Treatment"
                                        @if ($product->treatment == 'Beryllium Treatment') checked @endif>
                                    <label for="Beryllium-treatment">Beryllium Treatment</label>
                                </div>
                            </div>
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="treatment" id="Surface"
                                        value="Surface diffusion" @if ($product->treatment == 'Surface diffusion') checked @endif>
                                    <label for="Surface">Surface diffusion</label>
                                </div>
                            </div>
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="treatment" id="Irradiated"
                                        value="Irradiated" @if ($product->treatment == 'Irradiated') checked @endif>
                                    <label for="Irradiated">Irradiated</label>
                                </div>
                            </div>
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="treatment" id="Olied"
                                        value="Olied" @if ($product->treatment == 'Olied') checked @endif>
                                    <label for="Olied">Olied</label>
                                </div>
                            </div>
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="treatment" id="HPHT"
                                        value="HPHT" @if ($product->treatment == 'HPHT') checked @endif>
                                    <label for="HPHT">HPHT</label>
                                </div>
                            </div>
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="treatment" id="Laser"
                                        value="Laser drilled" @if ($product->treatment == 'Laser drilled') checked @endif>
                                    <label for="Laser">Laser drilled</label>
                                </div>
                            </div>
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="treatment"
                                        id="Surface-coating" value="Surface coating"
                                        @if ($product->treatment == 'Surface coating') checked @endif>
                                    <label for="Surface-coating">Surface coating</label>
                                </div>
                            </div>
                            <div class="" style="height: 20px">
                                {{-- <p class="text-center text-danger">this field is required</p> --}}
                            </div>
                        </td>
                    </tr>

                    {{-- for shapes --}}
                    <tr class="w-100 position-relative">
                        <td class="w-20 text-start title h-100 position-absolute top-0 mt-4">Shapes<span
                                class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- dont't remove this empty p as its for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="d-flex flex-md-row flex-column align-items-center">
                                <div class="w-100 w-md-30 ms-auto me-3">
                                    <div class="w-90 ms-auto" style="height:75px">
                                        @php
                                            // extract each shape separated by comma and store in an array
                                            $shapes = explode(',', $product->shapes);
                                        @endphp
                                        <span>Choose Options</span>
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="asscher" value="Asscher"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Asscher') checked @endif @endforeach>
                                            <label for="asscher">Asscher</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" name="shape[]" class="form-check-input h-80"
                                                id="cushion" value="Cushion"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Cushion') checked @endif @endforeach>
                                            <label for="cushion">Cushion</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="fancy" value="Fancy"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Fancy') checked @endif @endforeach>
                                            <label for="fancy">Fancy</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="hexagon" value="Hexagon"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Hexagon') checked @endif @endforeach>
                                            <label for="hexagon">Hexagon</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="oval" value="Oval"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Oval') checked @endif @endforeach>
                                            <label for="oval">Oval</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Princes" value="Princes"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Princes') checked @endif @endforeach>
                                            <label for="Princes">Princes</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Rhomboid" value="Rhomboid"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Rhomboid') checked @endif @endforeach>
                                            <label for="Rhomboid">Rhomboid</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Square" value="Square"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Square') checked @endif @endforeach>
                                            <label for="Square">Square</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100 w-md-30">
                                    <div class="w-90 ms-auto" style="height:75px">
                                        <span>&nbsp;</span>
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Baguette" value="Baguette"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Baguette') checked @endif @endforeach>
                                            <label for="Baguette">Baguette</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" name="shape[]" class="form-check-input h-80"
                                                id="Drop" value="Drop"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Drop') checked @endif @endforeach>
                                            <label for="Drop">Drop</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Freeform" value="Freeform"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Freeform') checked @endif @endforeach>
                                            <label for="Freeform">Freeform</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Marquise" value="Marquise"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Marquise') checked @endif @endforeach>
                                            <label for="Marquise">Marquise</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Pear" value="Pear"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Pear') checked @endif @endforeach>
                                            <label for="Pear">Pear</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Rabient" value="Rabient"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Rabient') checked @endif @endforeach>
                                            <label for="Rabient">Rabient</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Rose" value="Rose"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Rose') checked @endif @endforeach>
                                            <label for="Rose">Rose</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Triangle" value="Triangle"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Triangle') checked @endif @endforeach>
                                            <label for="Triangle">Triangle</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100 w-md-30">
                                    <div class="w-90 ms-auto" style="height:75px">
                                        <span>&nbsp;</span>
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Briolette" value="Briolette"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Briolette') checked @endif @endforeach>
                                            <label for="Briolette">Briolette</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" name="shape[]" class="form-check-input h-80"
                                                id="Emerald" value="Emerald"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Emerald') checked @endif @endforeach>
                                            <label for="Emerald">Emerald</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Heart" value="Heart"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Heart') checked @endif @endforeach>
                                            <label for="Heart">Heart</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Octagonal" value="Octagonal"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Octagonal') checked @endif @endforeach>
                                            <label for="Octagonal">Octagonal</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Pentagon" value="Pentagon"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Pentagon') checked @endif @endforeach>
                                            <label for="Pentagon">Pentagon</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Rectangle" value="Rectangle"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Rectangle') checked @endif @endforeach>
                                            <label for="Rectangle">Rectangle</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Round" value="Round"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Round') checked @endif @endforeach>
                                            <label for="Round">Round</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="shape[]"
                                                id="Trilliant" value="Trilliant"
                                                @foreach ($shapes as $shape)
                                                    @if ($shape == 'Trilliant') checked @endif @endforeach>
                                            <label for="Trilliant">Trilliant</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="" style="height: 20px">
                                {{-- <p class="text-center text-danger">this field is required</p> --}}
                            </div>
                        </td>
                    </tr>
                    {{-- for types --}}
                    <tr class="w-100 position-relative">
                        <td class="w-20 text-start title h-100 position-absolute top-0 mt-4">Type<span
                                class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- dont't remove this empty p as its for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="d-flex flex-md-row flex-column align-items-center">
                                <div class="w-100 w-md-30 ms-auto me-3">
                                    <div class="w-90 ms-auto" style="height:75px">
                                        @php
                                            $types = explode(',', $product->types ?? '');
                                        @endphp
                                        <span>Choose Options</span>
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="type[]"
                                                id="Faceted" value="Faceted"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Faceted') checked @endif @endforeach>
                                            <label for="Faceted">Faceted</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" name="type[]" class="form-check-input h-80"
                                                id="Specimen" value="Specimen"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Specimen') checked @endif @endforeach>
                                            <label for="Specimen">Specimen</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="type[]"
                                                id="Carved" value="Carved"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Carved') checked @endif @endforeach>
                                            <label for="Carved">Carved</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100 w-md-30">
                                    <div class="w-90 ms-auto" style="height:75px">
                                        <span>&nbsp;</span>
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="type[]"
                                                id="cobochon" value="Cobochon"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Cobochon') checked @endif @endforeach>
                                            <label for="cobochon">Cobochon</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" name="type[]" class="form-check-input h-80"
                                                id="Facet" value="Facet Grade Rough"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Facet Grade Rough') checked @endif @endforeach>
                                            <label for="Facet">Facet Grade Rough</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="type[]"
                                                id="Matched" value="Matched Pair"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Matched Pair') checked @endif @endforeach>
                                            <label for="Matched">Matched Pair</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100 w-md-30">
                                    <div class="w-90 ms-auto" style="height:75px">
                                        <span>&nbsp;</span>
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" class="form-check-input h-80" name="type[]"
                                                id="Rough" value="Rough"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Rough') checked @endif @endforeach>
                                            <label for="Rough">Rough</label>
                                        </div>
                                    </div>
                                    <div class="w-90 ms-auto" style="height:50px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="checkbox" name="type[]" class="form-check-input h-80"
                                                id="Bead" value="Bead"
                                                @foreach ($types as $type)
                                                    @if ($type == 'Bead') checked @endif @endforeach>
                                            <label for="Bead">Bead</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="" style="height: 20px">
                                {{-- <p class="text-center text-danger">this field is required</p> --}}
                            </div>
                        </td>
                    </tr>
                    {{-- Clarity Table Row --}}
                    <tr class="w-100 position-relative">
                        {{-- Clarity Title --}}
                        <td class="w-20 text-start title h-100 position-absolute top-0 mt-4">Clarity<span
                                class="text-danger">*</span></td>

                        {{-- Clarity Options --}}
                        <td class="w-80 h-100">
                            {{-- Empty p for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>

                            {{-- IF Option --}}
                            <div class="w-90 ms-auto" style="height:75px">
                                <span>Choose an option</span>
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="clarity"
                                        @if ($product->clarity == 'IF') checked @endif
                                        style="border:1px solid gray!important" id="IF" value="IF">
                                    <label for="IF">IF</label>
                                </div>
                            </div>

                            {{-- VVS Option --}}
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" name="clarity" class="form-check-input h-90"
                                        style="border:1px solid gray!important" id="VVS" value="VVS"
                                        @if ($product->clarity == 'VVS') checked @endif>
                                    <label for="VVS">VVS</label>
                                </div>
                            </div>

                            {{-- VS Option --}}
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="clarity"
                                        style="border:1px solid gray!important" id="VS" value="VS"
                                        @if ($product->clarity == 'VS') checked @endif>
                                    <label for="VS">VS</label>
                                </div>
                            </div>

                            {{-- SI Option --}}
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="clarity"
                                        style="border:1px solid gray!important" id="SI" value="SI"
                                        @if ($product->clarity == 'SI') checked @endif>
                                    <label for="SI">SI</label>
                                </div>
                            </div>

                            {{-- I Option --}}
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="clarity"
                                        style="border:1px solid gray!important" id="I" value="I"
                                        @if ($product->clarity == 'I') checked @endif>
                                    <label for="I">I</label>
                                </div>
                            </div>

                            {{-- OPAQUE Option --}}
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="clarity"
                                        style="border:1px solid gray!important" id="OPAQUE" value="OPAQUE"
                                        @if ($product->clarity == 'OPAQUE') checked @endif>
                                    <label for="OPAQUE">OPAQUE</label>
                                </div>
                            </div>

                            {{-- N/A Option --}}
                            <div class="w-90 ms-auto" style="height:50px">
                                <div class="d-flex align-items-center gap-2" style="height:35px">
                                    <input type="radio" class="form-check-input h-90" name="clarity"
                                        style="border:1px solid gray!important" id="N/A" value="N/A"
                                        @if ($product->clarity == 'N/A') checked @endif>
                                    <label for="N/A">N/A</label>
                                </div>
                            </div>

                            {{-- Additional space --}}
                            <div class="" style="height: 20px">
                                {{-- Commented out error message (if needed) --}}
                                {{-- <p class="text-center text-danger">This field is required</p> --}}
                            </div>
                        </td>
                    </tr>

                    @php
                        $listing = $product->productListing;
                        $pricing = $product->productPricing;
                        $shipping = $product->productShipping;
                    @endphp

                    {{-- Listing Type Heading --}}
                    <tr class="w-100">
                        <td class="w-20" colspan="2">
                            <div class="">
                                <h2 class="mb-0" style="line-height:1.5">Listing Details</h2>
                                <p class="mt-1" style="line-height: 1">Listing type and duration of the listing</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Listing Type --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Listing Type<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Empty p for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-100" style="height:35px">
                                {{-- Listing Type Dropdown --}}
                                <select id="listing_type" name="listing_type" class="w-90 ms-auto d-block h-100">
                                    <option value="">Please select</option>
                                    <option value="Boost" @if ($listing->listing_type == 'Boost') selected @endif>Boost - $0.00 (30 Days)
                                    </option>
                                    <option value="Premium" @if ($listing->listing_type == 'Premium') selected @endif>Premium - $15.00 (7 Days)
                                    </option>
                                    <option value="Showcase" @if ($listing->listing_type == 'Showcase') selected @endif>Showcase - $30.00 (7 Days)
                                    </option>
                                    <option value="Standard" @if ($listing->listing_type == 'Standard') selected @endif>Standard - $0.65
                                    </option>
                                </select>
                            </div>
                        </td>
                    </tr>

                    {{-- Listing Duration --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Duration<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Empty p for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-100" style="height:35px">
                                {{-- Listing Duration Dropdown --}}
                                <select id="duration" name="duration" class="w-90 ms-auto d-block h-100">
                                    <option value="">Please select</option>
                                    {{-- Duration Options --}}
                                    @for ($i = 1; $i <= 30; $i++)
                                        <option value="{{ $i }} days"
                                            @if ($listing->duration == $i . ' days') selected @endif>
                                            {{ $i }} days
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </td>
                    </tr>
                    {{-- Start Row --}}
                    <tr class="w-100 position-relative">
                        <td class="w-30 text-start title h-100 position-absolute top-0 mt-4">Start<span
                                class="text-danger">*</span></td>
                        <td class="w-70 h-100">
                            {{-- Empty p for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-90 d-flex align-items-center justify-content-center ms-auto">
                                {{-- Now Option --}}
                                <div class="w-70" style="height:75px">
                                    <div class="d-flex align-items-center gap-2" style="height:35px">
                                        <input type="radio" class="form-check-input h-90" name="start"
                                            @if ($listing->start == 'Now') checked @endif
                                            style="border:1px solid gray!important" id="Now" value="Now">
                                        <label for="Now">Now</label>
                                    </div>
                                </div>
                                {{-- Later Option --}}
                                <div class="w-70" style="height:75px">
                                    <div class="d-flex align-items-center gap-2" style="height:35px">
                                        <input type="radio" name="start" class="form-check-input h-90"
                                            style="border:1px solid gray!important" id="Later" value="Later"
                                            @if ($listing->start == 'Later') checked @endif>
                                        <label for="Later">Later</label>
                                    </div>
                                </div>
                            </div>
                            {{-- Additional space --}}
                            <div class="" style="height: 20px">
                                {{-- Commented out error message (if needed) --}}
                                {{-- <p class="text-center text-danger">This field is required</p> --}}
                            </div>
                        </td>
                    </tr>

                    {{-- Start Later Row --}}
                    <tr id="start_later_row" class="w-100 {{ $listing->start == 'Now' ? 'd-none' : '' }}">
                        <td class="w-20 text-start title h-100"><span class="text-danger"></span></td>
                        <td class="w-80 h-100 position-relative">
                            {{-- Empty p for alignment --}}
                            {{-- <p class="m-0 p-0" style="height:15px"></p> --}}
                            <div class="w-90 row ms-auto gap-2  position-absolute top-0 end-0" style="height:35px">
                                {{-- Start Date and Time Input --}}
                                <input type="date" name="start_date" class="w-45 h-100 px-3"
                                    value="{{ $listing->start_time ? date('Y-m-d', strtotime($listing->start_time)) : '' }}">
                                <input type="time" name="start_time" class="w-45 h-100 px-3 ms-auto"
                                    value="{{ $listing->start_time ? date('H:i', strtotime($listing->start_time)) : '' }}">
                            </div>
                        </td>
                    </tr>

                    {{-- Check if the type is 'auction' --}}
                    @if ($listing->item_type == 'auction')
                        {{-- Relist Row --}}
                        {{-- <tr class="w-100 position-relative">
                            <td class="w-30 text-start title h-100 position-absolute top-0 mt-4">Relist</td>
                            <td class="w-70 h-100">
                                <!-- Empty p for alignment -->
                                <p class="m-0 p-0" style="height:15px"></p>
                                <div class="w-90 d-flex align-items-center justify-content-center ms-auto">
                                    <!-- Unlimited Option -->
                                    <div class="w-70" style="height:75px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="radio" class="form-check-input" name="relisting" checked
                                                @if ($listing->relisting == 'Unlimited') checked @endif
                                                style="    outline: 1px solid rgba(128, 128, 128, 0.301);
                                                "
                                                id="Unlimited" value="Unlimited">
                                            <label for="Unlimited">Unlimited</label>
                                        </div>
                                    </div>
                                    <!-- Limited Option -->
                                    <div class="w-70" style="height:75px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="radio" name="relisting" class="form-check-input"
                                                style="    outline: 1px solid rgba(128, 128, 128, 0.301);
                                                "
                                                id="Limited" value="Limited"
                                                @if ($listing->relisting == 'Limited') checked @endif>
                                            <label for="Limited">Limited</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Additional space -->
                                <div class="" style="height: 20px">
                                    <!-- Commented out error message (if needed) -->
                                    <!-- <p class="text-center text-danger">This field is required</p> -->
                                </div>
                            </td>
                        </tr> --}}

                        <tr class="w-100 position-relative">
                            <td class="w-30 text-start title h-100 position-absolute top-0 mt-4">Relist</td>
                            <td class="w-70 h-100">
                                {{-- Empty p for alignment --}}
                                <p class="m-0 p-0" style="height:15px"></p>
                                <div class="w-90 d-flex align-items-center justify-content-center ms-auto">
                                    {{-- Unlimited Option --}}
                                    {{-- <div class="w-70" style="height:75px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="radio" class="form-check-input" name="relisting"
                                                @if (old('relisting') == 'Unlimited') checked @endif
                                                style="    outline: 1px solid rgba(128, 128, 128, 0.301);
                                                    "
                                                id="Unlimited" value="Unlimited">
                                            <label for="Unlimited">Unlimited</label>
                                        </div>
                                    </div> --}}
                                        {{-- Limited Option --}}
                                        <div class="w-50" style="height:75px">
                                            <div class="d-flex align-items-center gap-2" style="height:35px">
                                                <input type="radio" name="relisting" class="form-check-input"
                                                    style="outline: 1px solid rgba(128, 128, 128, 0.301);" checked
                                                    id="Limited" value="Limited">
                                                <label for="Limited">Limited</label>
                                            </div>
                                        </div>
                                        <div class="w-100" style="height:75px">
                                            <div class="d-flex align-items-center gap-2" style="height:35px">
                                                <label for="relist_limit">Enter relist limit</label>
                                                <input type="number"  name="relist_limit" class="h-100 ms-auto"
                                        value="{{ old('relist_limit') ?? $listing->relist_limit }}" placeholder="Enter relist limit">
                                            </div>
                                        </div>
                                </div>
                                {{-- Additional space --}}
                                <div class="" style="height: 20px">
                                    {{-- Commented out error message (if needed) --}}
                                    {{-- <p class="text-center text-danger">This field is required</p> --}}
                                </div>
                            </td>
                        </tr>


                        {{-- Relist Limited Row --}}
                        {{-- <tr id="relist_limited_row"
                            class="w-100 {{ $listing->relisting == 'Unlimited' ? 'd-none' : '' }}">
                            <td class="w-20 text-start title h-100"><span class="text-danger"></span></td>
                            <td class="w-80 h-100 position-relative">
                                <div class="w-90 row ms-auto  position-absolute top-0 end-0" style="height:35px">
                                    <input type="number" step="0.01"  name="relist_limit" class="w-50 h-100 ms-auto"
                                        value="{{ $listing->relist_limit }}" placeholder="Enter relist limit">
                                </div>
                            </td>
                        </tr> --}}
                    @endif

                    {{-- Pricing Heading --}}
                    <tr class="w-100">
                        <td class="w-20" colspan="2">
                            <div class="">
                                <h2 class="mb-0" style="line-height:1.5">Pricing Details</h2>
                                <p class="mt-1" style="line-height: 1">Fill out the price information</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Check if the type is 'auction' --}}
                    @if ($listing->item_type == 'auction')
                        {{-- Reserve Row --}}
                        <tr class="w-100 position-relative">
                            <td class="w-30 text-start title h-100 position-absolute top-0 mt-4">Reserve<span
                                    class="text-danger">*</span></td>
                            <td class="w-70 h-100">
                                {{-- Empty p for alignment --}}
                                <p class="m-0 p-0" style="height:15px"></p>
                                <div class="w-90 d-flex align-items-center justify-content-center ms-auto">
                                    {{-- Yes Option --}}
                                    <div class="w-70" style="height:75px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="radio" class="form-check-input" name="reserve"
                                                @if ($listing->reserved == 1) checked @endif
                                                style="outline: 1px solid rgba(128, 128, 128, 0.301);
                                        "
                                                id="reserve_yes" value="1">
                                            <label for="reserve_yes">Yes</label>
                                        </div>
                                    </div>
                                    {{-- No Option --}}
                                    <div class="w-70" style="height:75px">
                                        <div class="d-flex align-items-center gap-2" style="height:35px">
                                            <input type="radio" name="reserve" class="form-check-input"
                                                style="outline: 1px solid rgba(128, 128, 128, 0.301);
                                        "
                                                id="reserve_no" value="0"
                                                @if ($listing->reserved == 0) checked @endif>
                                            <label for="reserve_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                {{-- Additional space --}}
                                <div class="" style="height: 20px">
                                    {{-- Commented out error message (if needed) --}}
                                    {{-- <p class="text-center text-danger">This field is required</p> --}}
                                </div>
                            </td>
                        </tr>

                        {{-- Reserve Price Row --}}
                        <tr id="reserve_price_row" class="w-100 {{ $listing->reserved == 0 ? 'd-none' : '' }}">
                            <td class="w-20 text-start title h-100">Reserve Price<span class="text-danger">*</span></td>
                            <td class="w-80 h-100">
                                {{-- Empty p for alignment --}}
                                <p class="m-0 p-0" style="height:15px"></p>
                                <div class="input-group w-90 ms-auto" style="height:35px">
                                    {{-- Reserve Price Input --}}
                                    <input type="number" step="0.01"  name="reserve_price"
                                        class="form-control w-90 ms-auto d-block h-100"
                                        value="{{ old('reserve_price') ?? ($product->productPricing->reserve_price ?? '') }}">
                                    <span class="input-group-text border-0">USD</span>
                                </div>
                                {{-- Additional space --}}
                                <div class="" style="height: 20px">
                                    {{-- Commented out error message (if needed) --}}
                                    {{-- <p class="text-center text-danger">This field is required</p> --}}
                                </div>
                            </td>
                        </tr>

                        {{-- Starting Price Row --}}
                        <tr class="w-100">
                            <td class="w-20 text-start title h-100">Starting Price<span class="text-danger">*</span></td>
                            <td class="w-80 h-100">
                                {{-- Empty p for alignment --}}
                                <p class="m-0 p-0" style="height:15px"></p>
                                <div class="input-group w-90 ms-auto" style="height:35px">
                                    {{-- Starting Price Input --}}
                                    <input type="number" step="0.01"  name="starting_price"
                                        class="form-control w-90 ms-auto d-block h-100"
                                        value="{{ old('starting_price') ?? $pricing->starting_price }}">
                                    <span class="input-group-text border-0">USD</span>
                                </div>
                                {{-- Additional space --}}
                                <div class="" style="height: 20px">
                                    {{-- Commented out error message (if needed) --}}
                                    {{-- <p class="text-center text-danger">This field is required</p> --}}
                                </div>
                            </td>
                        </tr>
                    @else
                        {{-- Buy it Now Price Row --}}
                        <tr class="w-100">
                            <td class="w-20 text-start title h-100">Buy it Now Price<span class="text-danger">*</span>
                            </td>
                            <td class="w-80 h-100">
                                {{-- Empty p for alignment --}}
                                <p class="m-0 p-0" style="height:15px"></p>
                                <div class="input-group w-90 ms-auto" style="height:35px">
                                    {{-- Buy it Now Price Input --}}
                                    <input type="number" step="0.01"  name="buyitnow_price"
                                        class="form-control w-90 ms-auto d-block h-100"
                                        value="{{ old('buyitnow_price') ?? $pricing->buy_it_now_price }}">
                                    <span class="input-group-text border-0">USD</span>
                                </div>
                                {{-- Additional space --}}
                                <div class="" style="height: 20px">
                                    {{-- Commented out error message (if needed) --}}
                                    {{-- <p class="text-center text-danger">This field is required</p> --}}
                                </div>
                            </td>
                        </tr>
                        @endif

                        {{-- Recommended Retail Price Row --}}
                        <tr class="w-100">
                            <td class="w-20 text-start title h-100">Recommended Retail Price</td>
                            <td class="w-80 h-100">
                                {{-- Empty p for alignment --}}
                                <p class="m-0 p-0" style="height:15px"></p>
                                <div class="input-group w-90 ms-auto" style="height:35px">
                                    {{-- Recommended Retail Price Input --}}
                                    <input type="number" step="0.01"  name="retail_price"
                                        class="form-control w-90 ms-auto d-block h-100"
                                        value="{{ old('retail_price') ?? $pricing->retail_price }}">
                                    <span class="input-group-text border-0">USD</span>
                                </div>
                                {{-- Additional space --}}
                                <div class="" style="height: 20px">
                                    {{-- Commented out error message (if needed) --}}
                                    {{-- <p class="text-center text-danger">This field is required</p> --}}
                                </div>
                            </td>
                        </tr>

                    {{-- Shipping Heading --}}
                    <tr class="w-100">
                        <td class="w-20" colspan="2">
                            <div class="">
                                <h2 class="mb-0" style="line-height:1.5">Shipping Details</h2>
                                <p class="mt-1" style="line-height: 1">Shipping type selection and costs settings</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Shipping Type Row --}}
                    <tr class="w-100">
                        <td class="w-20 text-start title h-100">Shipping<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Empty p for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="w-90 ms-auto" style="height:auto">
                                {{-- Normal Shipping Option --}}
                                <div class="w-100 d-flex align-items-center gap-2 border ps-2 rounded-2"
                                    style="height:100px; background-color: #DAE1EB" id="normal-div">
                                    <input type="radio" class="form-check-input h-30" name="shipping_type"
                                        @if ($shipping->shipping_type == 'normal') checked @endif value="normal" id="normal">
                                    <label for="normal">Normal Shipping
                                        <p class="" style="color:#105082;line-height:1">Shipping cost will be
                                            based on your global shipping providers settings</p>
                                    </label>
                                </div>
                                {{-- Free/Custom Shipping Option --}}
                                <div class="w-100 d-flex align-items-center gap-2 border ps-2 rounded-2"
                                    style="height:100px" id="free-div">
                                    <input type="radio" class="form-check-input h-30" name="shipping_type"
                                        @if ($shipping->shipping_type == 'custom') checked @endif value="custom" id="free">
                                    <label for="free">Free Shipping
                                        <p class="text-muted">No Shipping Costs</p>
                                    </label>
                                </div>
                            </div>
                            {{-- Additional space --}}
                            <div class="" style="height: 20px">
                                {{-- Commented out error message (if needed) --}}
                                {{-- <p class="text-center text-danger">This field is required</p> --}}
                            </div>
                        </td>
                    </tr>

                    {{-- Custom Shipping Price Row --}}
                    <tr id="custom_shipping_price_tr"
                        class="w-100 {{ $shipping->shipping_type == 'normal' ? 'd-none' : '' }}">
                        <td class="w-20 text-start title h-100">Shipping Costs<span class="text-danger">*</span></td>
                        <td class="w-80 h-100">
                            {{-- Empty p for alignment --}}
                            <p class="m-0 p-0" style="height:15px"></p>
                            <div class="input-group w-90 ms-auto" style="height:35px">
                                {{-- Custom Shipping Price Input --}}
                                <input type="number" step="0.01"  name="custom_shipping_price"
                                    class="form-control w-90 ms-auto d-block h-100"
                                    value="{{ old('custom_shipping_price') ?? $shipping->custom_shipping_cost }}">
                                <span class="input-group-text border-0">USD</span>
                            </div>
                            {{-- Additional space --}}
                            <div class="" style="height: 20px">
                                {{-- Commented out error message (if needed) --}}
                                {{-- <p class="text-center text-danger">This field is required</p> --}}
                            </div>
                        </td>
                    </tr>

                    {{-- Buttons Row --}}
                    <tr class="w-100">
                        <td class="w-20 d-none d-md-block"></td>
                        <td class="w-100 w-md-80">
                            <div class="ms-auto d-flex align-items-center gap-2" style="width:fit-content">
                                <button type="button" class="cancel" onclick="window.history.back()">Cancel</button>
                                <button type="submit" class="save">Save</button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </form>
        </table>
    </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" tabindex="-1" id="categoriesModal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Browse Categories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center transition" id="level-1">
                            <div class="mx-auto" style="width: 90%">
                                <span class="d-block" style="width: 100%">Level 1</span>
                                @foreach ($categories as $category)
                                    <button data-id="{{ $category->id }}" data-value="{{ $category->name }}"
                                        id="category-{{ $category->id }}"
                                        class="m-1 d-flex align-items-center justify-content-between px-2"
                                        style="width: 100%;background-color:rgb(230, 230, 230);color:black">{{ $category->name }}
                                        @if ($category->children->count() > 0)
                                            <i class="bi bi-chevron-right"></i>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-none text-center" id="level-2">
                            <div class="mx-auto" style="width: 90%">
                                <span class="d-block" style="width: 100%">Level 2</span>
                                <div id="level-2-categories">

                                </div>
                            </div>
                        </div>
                        <div class="d-none text-center" id="level-3">
                            <div class="mx-auto" style="width: 90%">
                                <span class="d-block" style="width: 100%">Level 3</span>
                                <div id="level-3-categories">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="anchor-button text-dark" data-bs-dismiss="modal"
                        style="background-color:rgb(197, 194, 194)!important ">Close</button>
                    <button type="button" id="save-category" class="anchor-button">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
        });

        // for shipping types selection background colors
        const normalDiv = document.getElementById('normal-div');
        const freeDiv = document.getElementById('free-div');
        const normal = document.getElementById('normal');
        const free = document.getElementById('free');
        var shipping_type = "{{ old('shipping_type') ?? $shipping->shipping_type }}";
        if (shipping_type == 'normal') {
            normalDiv.style.backgroundColor = '#DAE1EB';
            freeDiv.style.backgroundColor = 'transparent';
        } else if (shipping_type == 'custom') {
            freeDiv.style.backgroundColor = '#DAE1EB';
            normalDiv.style.backgroundColor = 'transparent';
        }

        normal.addEventListener('click', () => {
            normalDiv.style.backgroundColor = '#DAE1EB';
            freeDiv.style.backgroundColor = 'transparent';
        });

        free.addEventListener('click', () => {
            freeDiv.style.backgroundColor = '#DAE1EB';
            normalDiv.style.backgroundColor = 'transparent';
        });

        // for custom shipping price
        // $('#free').change(function() {
        //     if ($(this).is(':checked')) {
        //         $('#custom_shipping_price_tr').removeClass('d-none');
        //     } else {
        //         $('#custom_shipping_price_tr').addClass('d-none');
        //     }
        // });
        // $('#normal').change(function() {
        //     if ($(this).is(':checked')) {
        //         $('#custom_shipping_price_tr').addClass('d-none');
        //     } else {
        //         $('#custom_shipping_price_tr').removeClass('d-none');
        //     }
        // });

        // for relisting
        $('#Limited').change(function() {
            if ($(this).is(':checked')) {
                $('#relist_limited_row').removeClass('d-none');
            } else {
                $('#relist_limited_row').addClass('d-none');
            }
        });
        $('#Unlimited').change(function() {
            if ($(this).is(':checked')) {
                $('#relist_limited_row').addClass('d-none');
            } else {
                $('#relist_limited_row').removeClass('d-none');
            }
        });

        // for reserve_price_row
        $('#reserve_yes').change(function() {
            if ($(this).is(':checked')) {
                $('#reserve_price_row').removeClass('d-none');
            } else {
                $('#reserve_price_row').addClass('d-none');
            }
        });
        $('#reserve_no').change(function() {
            if ($(this).is(':checked')) {
                $('#reserve_price_row').addClass('d-none');
            } else {
                $('#reserve_price_row').removeClass('d-none');
            }
        });

        // for title count
        const title = document.getElementById('title');
        const titleCount = document.getElementById('title_count');
        const titleError = document.getElementById('title_error');
        title.addEventListener('keyup', () => {
            titleCount.innerHTML = title.value.length;
            // also check if title not exceeded 75
            if (title.value.length > 75) {
                title.value = title.value.slice(0, 75);
                titleCount.innerHTML = title.value.length;
                // show error in title_error
                titleError.innerHTML = 'Title cannot exceed 75 characters';

            }
        });

        // for categories selection
        $(document).ready(function() {
            // for start later time
            $('#Later').change(function() {
                if ($(this).is(':checked')) {
                    $('#start_later_row').removeClass('d-none');
                } else {
                    $('#start_later_row').addClass('d-none');
                }
            });
            $('#Now').change(function() {
                if ($(this).is(':checked')) {
                    $('#start_later_row').addClass('d-none');
                } else {
                    $('#start_later_row').removeClass('d-none');
                }
            });

        });
    </script>

    {{-- For categories --}}
    <script>
        $(document).ready(function() {

            let selectedCategory = '';
            let selectedCategoryId = '';

            // Click event for Level 1 categories
            $('#level-1').on('click', 'button', function() {
                var categoryId = $(this).data('id');
                var level2Container = $('#level-2');
                var level3Container = $('#level-3');

                selectedCategoryId = $(this).data('id');
                selectedCategory = $(this).data('value');

                // Remove selected class from all categories
                $('.selected').removeClass('selected');

                // // Remove selected-category class from other categories from the same level
                $(this).siblings().removeClass('selected-category');

                // Add selected-category class to the clicked category
                $(this).addClass('selected-category');
                $(this).addClass('selected');

                // Check if the clicked category has children
                if ($(this).find('.bi-chevron-right').length > 0) {
                    // Add transition class to smoothly show/hide
                    level2Container.addClass('transition');

                    // Remove col-12 class from Level 1 and add col-6 class to Level 1 and Level 2
                    $('#level-1').removeClass('col-12').addClass('col-6');
                    level3Container.addClass('d-none');

                    // after 0.5 second, add col-6 class to Level 2
                    setTimeout(function() {
                        level2Container.removeClass('d-none').addClass('col-6');
                    }, 500);

                    // Simulate loading Level 2 categories
                    var url = '{{ url('api/get-sub-categories') }}/' + categoryId;

                    // Make an AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // Handle the response data
                            let level2Categories = '';


                            // populate sub_category dropdown
                            $.each(response.data, function(index, subCategory) {
                                // Check if subCategory has children
                                let hasChildren = subCategory.children && subCategory
                                    .children
                                    .length > 0;

                                level2Categories += `<button data-id="${subCategory.id}" data-value="${subCategory.name}" id="category-${subCategory.id}"
                                        class="m-1 d-flex align-items-center justify-content-between px-2"
                                        style="width: 100%;background-color:rgb(230, 230, 230);color:black">${subCategory.name}
                                        ${hasChildren ? '<i class="bi bi-chevron-right"></i>' : ''}
                                    </button>`;

                            });

                            $('#level-2-categories').html(level2Categories);
                        }
                    })
                    // $('#level-2-categories').html(level2Categories);
                } else {
                    // Add col-12 class to Level 1 and add d-none class to Level 2 and Level 3
                    $('#level-1').addClass('col-12');
                    level2Container.addClass('d-none');
                    level3Container.addClass('d-none');
                }
            });

            // Click event for Level 2 categories
            $('#level-2').on('click', 'button', function() {
                var categoryId = $(this).data('id');
                var level3Container = $('#level-3');

                selectedCategoryId = $(this).data('id');
                selectedCategory = $(this).data('value');

                // Remove selected class from all categories
                $('.selected').removeClass('selected');

                // // Remove selected-category class from other categories from the same level
                $(this).siblings().removeClass('selected-category');

                // Add selected-category class to the clicked category
                $(this).addClass('selected-category');
                $(this).addClass('selected');

                // Check if the clicked category has children
                if ($(this).find('.bi-chevron-right').length > 0) {
                    // Add transition class to smoothly show/hide
                    // level3Container.addClass('transition');

                    // Remove col-6 class from Level 1 and Level 2 and add col-4 class to Level 3
                    $('#level-1').removeClass('col-6').addClass('col-4');
                    $('#level-2').removeClass('col-6').addClass('col-4');


                    setTimeout(function() {
                        level3Container.removeClass('d-none').addClass('col-4');
                    }, 500);

                    // Simulate loading Level 3 categories
                    var url = '{{ url('api/get-sub-categories') }}/' + categoryId;

                    // Make an AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // Handle the response data
                            let level3Categories = '';

                            // populate level 3 categories
                            $.each(response.data, function(index, subCategory) {
                                level3Categories += `<button data-id="${subCategory.id}" data-value="${subCategory.name}" id="category-${subCategory.id}"
                                        class="m-1 d-flex align-items-center justify-content-between px-2"
                                        style="width: 100%;background-color:rgb(230, 230, 230);color:black">${subCategory.name}
                                    </button>`;

                            });

                            $('#level-3-categories').html(level3Categories);
                        }
                    });
                }
            });

            // Click event for Level 3 categories
            $('#level-3').on('click', 'button', function() {
                var categoryId = $(this).data('id');

                selectedCategoryId = $(this).data('id');
                selectedCategory = $(this).data('value');

                // Remove selected class from all categories
                $('.selected').removeClass('selected');

                // // Remove selected-category class from other categories from the same level
                $(this).siblings().removeClass('selected-category');

                // Add selected-category class to the clicked category
                $(this).addClass('selected-category');
                $(this).addClass('selected');

            });

            $('#save-category').click(function() {

                if (selectedCategoryId) {
                    $('#category_id').val(selectedCategoryId);
                    $('#category').val(selectedCategory);
                }

                // close the categoriesModal
                $('#categoriesModal').modal('hide');
            });
        });
    </script>

    {{-- For categories search --}}
    {{-- <script>
        $(document).ready(function() {
            let delayTimer;
            // Add keyup event listener to the #category input
            $('#category').on('keyup', function() {
                clearTimeout(delayTimer);

                // Set a delay of 500 milliseconds before triggering the search
                delayTimer = setTimeout(function() {
                    let inputValue = $('#category').val();
                    // Check if the input value is not empty
                    if (inputValue.trim() !== '') {
                        // Show the #category_dropdown
                        $('#category_dropdown').removeClass('d-none');
                    } else {
                        // Hide the #category_dropdown if the input is empty
                        $('#category_dropdown').addClass('d-none');
                    }
                }, 500);

            });

            // Add click event listener to the window
            $(window).on('click', function(event) {
                // Check if the clicked element is not within #category or #category_dropdown
                if (!$(event.target).closest('#category').length && !$(event.target).closest(
                        '#category_dropdown').length) {
                    // Close the #category_dropdown
                    $('#category_dropdown').addClass('d-none');
                }
            });
        });
    </script> --}}
@endsection
