@extends('layouts.buyer')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
@endsection
@section('content')
    {{-- category cards html --}}
    <div class="w-100 px-2 py-3 ">
        <div class="w-95 d-flex align-items-center  justify-content-center flex-wrap m-auto">
            @for ($p = 1; $p < 10; $p++)
                <div class="card m-3">
                    <img src="{{ asset('assets/home/stones.jpg') }}" class="card-img-top" alt="...">
                    <span class="featured-tag">Featured</span>
                    {{-- <span class="discount-tag">25 % off</span> --}}
                    <a href="#" class="products-card-link">
                        <div class="card-body">
                            <div class="card-body-first">
                                <h5 class="m-0 p-2 text-start text-truncate">0.61 Fancy Intense Browning and is </h5>
                            </div>
                            <div class="card-body-second px-2 pt-1">
                                <p class="">Shape: <span>Round</span></p>
                                <p class="">Quantity: <span>12</span></p>
                                <p class="">Color: <span>Red</span></p>
                            </div>
                        </div>
                    </a>
                    <div class="card-footer">
                        <div class="footer-icons-part px-2 pt-1">
                            <button class="m-0 p-0 border-0 bg-transparent" type="button" data-bs-toggle="modal"
                                data-bs-target="#bid-popup" title="Bid now">
                                <i class="bi bi-hammer"></i>
                            </button>
                            <i class="bi bi-shield-check"></i>
                            {{-- <i class="bi bi-bag"></i> --}}
                            <button class="m-0 p-0 border-0 bg-transparent" type="button" data-bs-toggle="modal"
                                data-bs-target="#make-offer-popup" title="Make an offer">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                        </div>
                        <div class="footer-price-part">
                            <p>$5,0000</p>
                        </div>
                        <div class="footer-time-part">
                            <p>33 h 55min</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
    {{-- all the modals used in this page are here --}}
    {{-- the modal for the bid popup is used here --}}
    <!-- Modal -->
    <div class="modal fade" id="bid-popup" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false" style="z-index: 99999">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header w-100 position-relative d-flex flex-column"
                    style="border: 0;--bs-modal-header-padding:0">
                    <div class="w-100 alert alert-danger rounded-0 border-0 d-flex align-items-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        <small class="modal-text">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Repellendus, voluptates
                            praesentium! Quam itaque voluptatum animi nemo quod, magni sint ab?</small>
                    </div>
                    <div class="w-100 alert alert-success rounded-0 border-0 d-flex align-items-center justify-content-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        <small class="modal-text">You have not bid on this auction.</small>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                        aria-label="Close" style="right: 0!important;margin:0"></button>
                </div>
                <div class="modal-body  w-100 d-flex align-items-start flex-column flex-md-row  m-0" style="border: none">
                    <div class="modal-body-left w-100 w-md-50 px-3 py-3">
                        <div class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <img src="{{ asset('assets/home/stones.jpg') }}" alt="">
                            <div>
                                <h2>0.61 Fancy Intense Browning</h2>
                                <p><i class="bi bi-hammer"></i> $90000.00</p>
                            </div>
                        </div>
                        {{-- bid form starts here --}}
                        <form action="w-100">
                            <div class="w-100 mt-3">
                                <label class="">Minimum Bid</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="text" class="form-control w-90 ms-auto d-block h-100">
                                    <span class="input-group-text border-0">USD</span>
                                </div>
                                {{-- <small class="text-danger">this is for error</small> --}}
                            </div>
                            <div class="w-100 mt-2">
                                <label class="">Password</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="password" class="form-control w-90 ms-auto d-block h-100">
                                </div>
                                {{-- <small class="text-danger">this is for error</small> --}}
                            </div>
                            <div class="w-100 mt-2">
                                <label class="">My Minimum Bid</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <input type="text" class="form-control w-90 ms-auto d-block h-100">
                                    <span class="input-group-text border-0">USD</span>
                                </div>
                                {{-- <small class="text-danger">this is for error</small> --}}
                            </div>
                            <div class="w-100 mt-2">
                                <label class="">Remember my Password For</label>
                                <div class="w-100" style="height:35px">
                                    <select class="w-100  d-block h-100">
                                        <option value="">Please select</option>
                                        <option value="">For 2 days</option>
                                    </select>
                                </div>
                                {{-- <small class="text-danger">this is for error</small> --}}
                            </div>
                            <div class="w-100 mt-5">
                                <button type="button" class="m-auto d-block">Place Bid</button>
                            </div>
                        </form>
                    </div>
                    <div
                        class="modal-body-right w-100 w-md-50 mt-md-5 p-3 d-flex flex-column align-items-center justify-content-center">
                        <div class="bid-time w-100">
                            <h1 class="text-center">2d 23h 25m 21s</h1>
                            <p class="text-center">zero bids placed so far</p>
                        </div>
                        <div class="bid-history-heading w-100 px-md-3">
                            <p class="m-0 p-0"><b>Bids History (2)</b></p>
                        </div>
                        {{-- this div is to be dynamic for dynamic bid history --}}
                        @for ($x = 1; $x < 3; $x++)
                            <div class="bid-history-details w-100 ps-md-3 d-flex align-items-center">
                                <div class="w-75 h-100">
                                    <p class="text-primary my-2"
                                        style="line-hei
                                            t: 0">Zaka
                                        Ullah</p>
                                    <p style="line-height: 0">1 day ago</p>
                                </div>
                                <div class="w-25">
                                    <b> $9999999</b>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- the modal for the make offer is used here --}}
    <!-- Modal -->
    <div class="modal fade" id="make-offer-popup" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false" style="z-index: 99999">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                {{-- modal header starts here --}}
                <div class="modal-header w-100 position-relative d-flex flex-column"
                    style="border: 0;--bs-modal-header-padding:0">
                    <div class="w-100 alert alert-danger rounded-0 border-0 d-flex align-items-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        <small class="modal-text">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Repellendus, voluptates
                            praesentium! Quam itaque voluptatum animi nemo quod, magni sint ab?</small>
                    </div>
                    <div class="w-100 alert alert-success rounded-0 border-0 d-flex align-items-center justify-content-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        <small class="modal-text">You have not bid on this auction.</small>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                        aria-label="Close" style="right: 0!important;margin:0"></button>
                </div>
                {{-- modal body starts here --}}
                <div class="modal-body w-100  m-0" style="border: none">
                    <form action="w-100 d-flex flex-column">
                        <div class="d-flex align-items-end flex-column flex-md-row">
                            <div class="modal-body-left w-100 w-md-50 px-3 py-3">
                                <div
                                    class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-between">
                                    <img src="{{ asset('assets/home/stones.jpg') }}" alt="">
                                    <div>
                                        <h2>0.61 Fancy Intense Browning</h2>
                                        <p><i class="bi bi-hammer"></i> $90000.00</p>
                                    </div>
                                </div>
                                {{-- bid form starts here --}}
                                <div class="w-100 mt-3">
                                    <label class="">My Offer</label>
                                    <div class="input-group w-100 " style="height:35px">
                                        <input type="text" class="form-control w-90 ms-auto d-block h-100">
                                        <span class="input-group-text border-0">USD</span>
                                    </div>
                                    {{-- <small class="text-danger">this is for error</small> --}}
                                </div>
                                <div class="w-100 mt-2">
                                    <label class="">Password</label>
                                    <div class="input-group w-100 " style="height:35px">
                                        <input type="password" class="form-control w-90 ms-auto d-block h-100">
                                    </div>
                                    {{-- <small class="text-danger">this is for error</small> --}}
                                </div>
                            </div>
                            <div class="modal-body-right w-100 w-md-50 px-3 py-3">
                                <div class="w-100 mt-2">
                                    <label class="">Valid For</label>
                                    <div class="w-100" style="height:35px">
                                        <select class="w-100  d-block h-100">
                                            <option value="">Please select</option>
                                            <option value="">For 3 days</option>
                                        </select>
                                    </div>
                                    {{-- <small class="text-danger">this is for error</small> --}}
                                </div>
                                <div class="w-100 mt-2">
                                    <label class="">Remember my Password For</label>
                                    <div class="w-100" style="height:35px">
                                        <select class="w-100  d-block h-100">
                                            <option value="">Please select</option>
                                            <option value="">Don't Remember</option>
                                        </select>
                                    </div>
                                    {{-- <small class="text-danger">this is for error</small> --}}
                                </div>
                            </div>
                        </div>
                        <div class="w-100">
                            <button type="button" class="m-auto d-block">Make Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
