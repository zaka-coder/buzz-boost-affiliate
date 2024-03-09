@php
    $store = $product->store;
    // check if the user is blocked by the store
    if (auth()->user()) {
        $isBlocked = $store->blocked_users->contains(auth()->user()->id);
    } else {
        $isBlocked = false;
    }
@endphp

<!-- Make Offer Modal -->
<div class="modal fade" id="make-offer-popup{{ $product->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false" style="z-index: 99999">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
        <div class="modal-content">
            {{-- modal header starts here --}}
            <div class="modal-header w-100 position-relative d-flex flex-column"
                style="border: 0;--bs-modal-header-padding:0">
                @guest
                    <div class="w-100 alert alert-danger rounded-0 border-0 d-flex align-items-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        <small class="modal-text">Please login first to make an offer.</small>
                    </div>
                @endguest
                @auth
                    <div id="offer-success-alert{{ $product->id }}"
                        class="w-100 alert alert-success rounded-0 border-0 d-flex align-items-center justify-content-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        @if (Auth::user()->offers()->where('product_id', $product->id)->exists())
                            <small class="modal-text">You have already made offer(s) for this item.</small>
                        @else
                            <small class="modal-text">You have not made an offer for this item yet.</small>
                        @endif
                    </div>
                @endauth
                <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                    aria-label="Close" style="right: 0!important;margin:0"></button>
            </div>
            {{-- modal body starts here --}}
            <div class="modal-body w-100  m-0" style="border: none">
                @if (!$isBlocked)
                    <form id="offer-form{{ $product->id }}" action="{{ route('buyer.offers.store') }}" method="POST"
                        class="w-100 d-flex flex-column">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="d-flex align-items-start flex-column flex-md-row  mb-3">
                            <div class="modal-body-left w-100  px-3 py-3">
                                <div
                                    class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                                    <div class="w-100 rounded-3 w-md-35">
                                        <img src="{{ asset($product->image ?? 'assets/home/stones.jpg') }}"
                                            alt="" width="100px" height="100px"
                                            style="object-fit: cover;max-width:100px;border-radius:inherit;margin:auto;display:block">
                                    </div>
                                    <div class="w-100 w-md-65">
                                        <h2 class="text-center">{{ $product->weight . ' (ct)' . $product->name }}</h2>
                                        <p class="text-center"><i class="bi bi-hammer"></i>
                                            ${{ $product->productPricing->buy_it_now_price ?? 'NaN' }}</p>
                                    </div>
                                </div>
                                {{-- offer form starts here --}}
                                <div class="w-100 mt-3">
                                    <label class="">My Offer</label>
                                    <div class="input-group w-100 " style="height:35px">
                                        <input type="number" class="form-control w-90 ms-auto d-block h-100"
                                            name="offer_value">
                                        <span class="input-group-text border-0">USD</span>
                                    </div>
                                    <small class="text-danger" id="offer-value-error{{ $product->id }}"></small>
                                </div>
                                <div class="w-100 mt-2">
                                    <label class="">Valid For</label>
                                    <div class="w-100" style="height:35px">
                                        <select class="w-100  d-block h-100" name="validity">
                                            <option value="2" selected>2 Days</option>
                                            {{-- <option value="3">3 Days</option>
                                        <option value="4">4 Days</option>
                                        <option value="5">5 Days</option>
                                        <option value="6">6 Days</option>
                                        <option value="7">7 Days</option> --}}
                                        </select>
                                    </div>
                                    <small class="text-danger" id="validity-error{{ $product->id }}"></small>
                                </div>
                                <div class="w-100 mt-4">
                                    <button id="offer-form-submit{{ $product->id }}"
                                        onclick="formsubmit({{ $product->id }})" type="button"
                                        class="m-auto d-flex align-items-center justify-content-center gap-2">Make
                                        Offer
                                        <div class="spinner-border loaderSpin" role="status"
                                            style="display:none;width:20px;height:20px">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="w-50">
                        <button id="offer-form-submit{{ $product->id }}"
                            onclick="formsubmit({{ $product->id }})" type="button" class="m-auto d-block">Make
                            Offer</button>
                    </div> --}}
                    </form>
                @else
                    <p class="text-center m-5 p-5"><small><i class="bi bi-info-circle"></i></small> Sorry! You
                        have been blocked by this seller.</p>
                @endif
            </div>
        </div>
    </div>
</div>
