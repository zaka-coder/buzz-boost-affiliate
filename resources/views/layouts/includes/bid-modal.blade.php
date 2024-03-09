@php
    $store = $product->store;
    // check if the user is blocked by the store
    if (auth()->user()) {
        $isBlocked = $store->blocked_users->contains(auth()->user()->id);
    } else {
        $isBlocked = false;
    }
@endphp

<!-- Bid Modal -->
<div class="modal fade" id="bid-popup{{ $product->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false" style="z-index: 99999">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header w-100 position-relative d-flex flex-column"
                style="border: 0;--bs-modal-header-padding:0">
                {{--  --}}
                @guest
                    <div class="w-100 alert alert-danger rounded-0 border-0 d-flex align-items-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        <small class="modal-text">Please login first to place a bid.</small>
                    </div>
                @endguest
                @auth
                    <div id="bid-success-alert{{ $product->id }}"
                        class="w-100 alert alert-success rounded-0 border-0 d-flex align-items-center justify-content-center gap-3"
                        style="--bs-alert-margin-bottom:0">
                        <small><i class="bi bi-info-circle"></i></small>
                        @if (Auth::user()->bids()->where('product_id', $product->id)->exists())
                            <small class="modal-text">You have already bid on this auction. But you can place another
                                bid.</small>
                        @else
                            <small class="modal-text">You have not bid on this auction yet.</small>
                        @endif
                    </div>
                @endauth
                <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                    aria-label="Close" style="right: 0!important;margin:0"></button>
            </div>
            <div class="modal-body  w-100 d-flex align-items-start flex-column flex-md-row  m-0" style="border: none">
                @if (!$isBlocked)
                    <div class="modal-body-left w-100 w-md-50 px-3 py-3">
                        <div
                            class="w-100 d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                            <div class="w-100 rounded-3 w-md-35">
                                <img src="{{ asset($product->image ?? 'assets/home/stones.jpg') }}" alt=""
                                    width="100px" height="100px"
                                    style="object-fit: cover;max-width:100px;border-radius:inherit;margin:auto;display:block">
                            </div>
                            <div class="w-100 w-md-65">
                                <h2 class="text-center">{{ $product->weight . ' (ct)' . $product->name }}</h2>
                                <p class="text-center"><i class="bi bi-hammer"></i>
                                    ${{ number_format($product->productPricing->starting_price ?? 0, 2) }}
                                </p>
                            </div>
                        </div>
                        {{-- bid form starts here --}}
                        <form id="bid-form{{ $product->id }}" action="{{ route('buyer.bids.store') }}" class="w-100"
                            method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            {{-- <div class="w-100 mt-3">
                            <label class="">Minimum Bid</label>
                            <div class="input-group w-100 " style="height:35px;">
                                <input type="text" name="min_bid"
                                    class="form-control w-90 ms-auto d-block h-100" value="$1" readonly>
                                <span class="input-group-text border-0">USD</span>
                            </div>
                        </div>
                        <div class="w-100 mt-2">
                            <label class="">Password</label>
                            <div class="input-group w-100 " style="height:35px">
                                <input type="password" class="form-control w-90 ms-auto d-block h-100">
                            </div>
                        </div> --}}
                            <div class="w-100 mt-2">
                                <label class="">My Minimum Bid</label>
                                <div class="input-group w-100 " style="height:35px">
                                    <span class="input-group-text border-0">$</span>
                                    <input type="text" class="form-control w-90 ms-auto d-block h-100" value="1"
                                        name="price" required>
                                    <span class="input-group-text border-0">USD</span>
                                </div>
                                <small class="text-danger" id="bid-value-error{{ $product->id }}"></small>
                            </div>
                            <div class="w-100 mt-5">
                                {{-- <button id="bid-form-submit{{ $product->id }}" type="submit" class="m-auto d-block">Place Bid</button> --}}
                                <button id="bid-form-submit{{ $product->id }}"
                                    onclick="bidformsubmit({{ $product->id }})" type="button"
                                    class="m-auto d-flex align-items-center justify-content-center gap-2">Place Bid
                                    <div class="spinner-border loaderSpinBid" role="status"
                                        style="display:none;width:20px;height:20px">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div
                        class="modal-body-right w-100 w-md-50 mt-md-5 p-3 d-flex flex-column align-items-center justify-content-center">
                        <div class="bid-time w-100">
                            @php
                                $countdownTime = $product->countdown_time;
                            @endphp
                            <h1 id="countdown{{ $product->id }}" class="text-center">
                                {{ $countdownTime['days'] }}d
                                {{ $countdownTime['hours'] }}h
                                {{ $countdownTime['minutes'] }}m {{ $countdownTime['seconds'] }}s
                            </h1>

                            {{-- JavaScript code for countdown --}}
                            <script>
                                // Function to update the countdown every second
                                function updateCountdown() {
                                    // Your PHP countdown time
                                    var countdownTime = @json($countdownTime);

                                    // Convert the PHP countdown time to a JavaScript Date object
                                    var targetDate = new Date();
                                    targetDate.setSeconds(targetDate.getSeconds() + countdownTime.seconds);
                                    targetDate.setMinutes(targetDate.getMinutes() + countdownTime.minutes);
                                    targetDate.setHours(targetDate.getHours() + countdownTime.hours);
                                    targetDate.setDate(targetDate.getDate() + countdownTime.days);

                                    // Update the countdown every second
                                    setInterval(function() {
                                        var currentDate = new Date();
                                        var timeDifference = targetDate - currentDate;

                                        var days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                                        // Update the HTML with the new countdown values
                                        var countdownElement = document.getElementById('countdown{{ $product->id }}');
                                        countdownElement.textContent = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';

                                    }, 1000); // Update every second
                                }

                                // Call the updateCountdown function on page load
                                updateCountdown();
                            </script>

                            <p class="text-center"><span
                                    id="bid-count{{ $product->id }}">{{ $product->bids ? $product->bids->count() : 0 }}</span>
                                bid(s) placed so
                                far</p>
                        </div>
                        <div id="bid-history{{ $product->id }}" class="w-100">
                            <div class="bid-history-heading w-100 px-md-3">
                                <p class="m-0 p-0"><b>Bids History
                                        ({{ $product->bids ? $product->bids->count() : 0 }})
                                    </b></p>
                            </div>
                            {{-- this div is to be dynamic for dynamic bid history --}}
                            @forelse ($product->bids ?? [] as $bid)
                                <div class="bid-history-details w-100 ps-md-3 d-flex align-items-center">
                                    <div class="w-75 h-100">
                                        <p class="text-primary my-2">
                                            {{ $bid->user->name }}</p>
                                        {{-- <p style="line-height: 0">1 day ago</p> --}}
                                        <p style="line-height: 0">{{ $bid->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="w-25">
                                        <b> ${{ number_format($bid->price, 2) }}</b>
                                    </div>
                                </div>
                            @empty
                                <div class="w-100 text-center">
                                    <p>No history</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <p class="w-100 text-center m-5 p-5"><small><i class="bi bi-info-circle"></i></small> Sorry! You
                        have been blocked by this seller.</p>
                @endif
            </div>
        </div>
    </div>
</div>
