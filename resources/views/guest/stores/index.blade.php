@extends('layouts.all-stores', ['title' => 'Stores'])
@section('css')
    <style>
        .dynamic-part {
            background-color: transparent;
        }

        .storenamelink:hover {
            border-bottom: 1px solid blue;
            transition: all ease 1s;
        }

        .afterLine {
            position: relative;
        }

        .afterLine::after {
            content: "";
            width: 2px;
            height: 15px;
            position: absolute;
            background-color: gray;
            right: -8px;
            top: 5px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="category-name">All Stores</div>
        </div>
    </div>
    <div class="row" id="stores-container">
        @foreach ($stores as $store)
            <div class="col-md-6" style="height:120px">
                <div class="h-95 rounded-2 border border-1 d-flex" style="background-color:#FFF;width:99%">
                    {{-- for store image div starts here --}}
                    <div class="w-30 h-100  d-flex align-items-center justify-content-center skeleton">
                        <img src="{{ asset($store->image ?? 'assets/buyer-assets/diamond-3.jpg') }}" alt="store-image"
                            width="90%" height="90%" style="max-width:90%;object-fit:cover" class="rounded-2">
                    </div>
                    {{-- for store image div ends here --}}
                    {{-- store other info starts here --}}
                    <div class="w-70 h-100 d-flex align-items-center justify-content-center">
                        <div class="w-100 h-90">
                            <div class="w-100 h-50 px-2">
                                <div class="m-0 p-0 skeleton">
                                    <a href="{{ route('stores.show', $store->id) }}"
                                        class="storenamelink">{{ $store->name }}</a>
                                    <i class="bi bi-patch-check-fill" style="color: #10B981"></i>
                                </div>
                                <small class="p-0 m-0 skeleton">{{ $store->country ?? '  -  ' }}</small>
                            </div>
                            {{-- empty for alignments --}}
                            <div class="w-100 h-20 px-2">
                                <div class="skeleton text-truncate text-muted" style="font-size:15px">
                                    {{ $store->description ?? '  - ' }}
                                </div>
                            </div>
                            <div class="w-100 h-30  px-2  d-flex align-items-end">
                                <div class="skeleton d-flex align-items-center justify-content-start gap-3 ">
                                    <p class="p-0 m-0 text-muted afterLine">{{ $store->products->count() }} Items</p>
                                    <p class="p-0 m-0 text-muted">{{ $store->ratings > 0 ? number_format($store->ratings, 1) . '%' : 'No'}} Ratings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- store other info starts here --}}
                </div>
            </div>
        @endforeach
    </div>
    {{-- incase if there is no data then this div must show to the user --}}
    @if ($stores->count() == 0)
        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2 ">
            <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt="" style="filter: invert(1)">
            <p class="nunito">No stores found</p>
        </div>
    @endif
@endsection
