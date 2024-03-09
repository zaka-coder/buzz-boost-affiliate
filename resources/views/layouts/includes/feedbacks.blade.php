{{-- css starts --}}
<style>
    .multiline-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-tag {
        border-bottom-left-radius: 7px;
    }

    .feedback-item {
        transition: background 0.2s ease-in-out;
    }

    .feedback-item:hover {
        background: #f8f7fa70;
    }
</style>
{{-- css ends --}}


<section class="w-100 h-85 warning">
    <div class="w-100" style="height:130px">
        <div class="w-100 h-100 m-auto" style="box-shadow: 2px 5px 15px rgb(208, 207, 207);">
            <table class="table w-100 h-100 table-bordcered">
                <tr class="w-100 h-100">
                    <td class="w-20 h-100 feedback-item">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <i class="bi bi-hand-thumbs-up-fill"
                                    style="color: green!important;font-size:22px!important"></i>
                            </div>
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <h4 class="nunito">Positive</h4>
                            </div>
                            <div class="w-100 h-40 d-flex align-items-center justify-content-center">
                                <h4 class="nunito">{{ $positive }}</h4>
                            </div>
                        </div>
                    </td>
                    <td class="w-20 h-100 feedback-item">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <i class="bi bi-dash fw-1"
                                    style="color: rgba(196, 196, 49, 0.733)!important;font-size:35px!important"></i>
                            </div>
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <h4 class="nunito">Neutral</h4>
                            </div>
                            <div class="w-100 h-40 d-flex align-items-center justify-content-center">
                                <h4 class="nunito">{{ $neutral }}</h4>
                            </div>
                        </div>
                    </td>
                    <td class="w-20 h-100 feedback-item">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <i class="bi bi-hand-thumbs-down-fill"
                                    style="color:red!important;font-size:22px!important"></i>
                            </div>
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <h4 class="nunito">Negative</h4>
                            </div>
                            <div class="w-100 h-40 d-flex align-items-center justify-content-center">
                                <h4 class="nunito">{{ $negative }}</h4>
                            </div>
                        </div>
                    </td>
                    <td class="w-20 h-100 feedback-item">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <svg width="28" height="28" viewBox="0 0 16 16"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#000000"
                                        d="M6.291 3.218a4.803 4.803 0 0 1-.12.782h.32a1.51 1.51 0 0 1 1.443 1.945a334.63 334.63 0 0 0-.767 2.607a2.004 2.004 0 0 1-2.506 1.36l-3.203-.97a1.5 1.5 0 0 1-.972-.918L.095 6.961a1.5 1.5 0 0 1 .663-1.82l.994-.568a1.5 1.5 0 0 0 .577-.591l1.209-2.243c.239-.444.788-.884 1.45-.696c.558.158.917.506 1.113.934c.186.407.215.858.19 1.241m3.537 8.78a4.802 4.802 0 0 0-.12.781c-.024.384.004.835.19 1.241c.197.43.556.777 1.113.934c.662.188 1.212-.252 1.451-.695l1.208-2.243a1.5 1.5 0 0 1 .577-.591l.994-.568a1.5 1.5 0 0 0 .664-1.82l-.391-1.064a1.5 1.5 0 0 0-.973-.917l-3.202-.97a2.004 2.004 0 0 0-2.506 1.36c-.224.77-.506 1.735-.767 2.607a1.51 1.51 0 0 0 1.443 1.945z" />
                                </svg>
                            </div>
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <h4 class="nunito">Total Feedbacks</h4>
                            </div>
                            <div class="w-100 h-40 d-flex align-items-center justify-content-center">
                                <h4 class="nunito">{{ $feedbacks->count() }}</h4>
                            </div>
                        </div>
                    </td>
                    <td class="w-20 h-100 feedback-item">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <i class="bi bi-star-fill"
                                    style="color: rgb(255, 217, 0)!important;font-size:22px!important"></i>
                            </div>
                            <div class="w-100 h-30  d-flex align-items-center justify-content-center">
                                <h4 class="nunito">Feedback Ratings</h4>
                            </div>
                            <div class="w-100 h-40 d-flex align-items-center justify-content-center">
                                <h4 class="nunito">{{ number_format($ratings, 1) }}%</h4>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="w-100 h-auto mt-4 pb-2">
        @foreach ($feedbacks as $feedback)
            <div class="w-90 m-auto d-flex my-4 rounded-3" style="height: 130px;outline:1px solid gray">
                <div class="w-20 h-100 position-relative">
                    <img class="rounded-3" src="{{ asset($feedback->product->image ?? 'assets/home/stones.jpg') }}"
                        alt=""
                        style="width: 100%;max-width:100%;height:130px;object-fit:cover;border-bottom-right-radius:0!important">
                    @if ($feedback->nature == 'positive')
                        <p class="position-absolute px-2 product-tag"
                            style="top:0;right:0;background-color:green;color:white;font-size:12px">Positive
                        </p>
                    @elseif ($feedback->nature == 'neutral')
                        <p class="position-absolute px-2 product-tag"
                            style="top:0;right:0;background-color:orange;color:white;font-size:12px">Neutral
                        </p>
                    @elseif ($feedback->nature == 'negative')
                        <p class="position-absolute px-2 product-tag"
                            style="top:0;right:0;background-color:red;color:white;font-size:12px">Negative
                        </p>
                    @endif
                </div>
                <div class="px-2 w-45 h-100" style="outline: 1px solid rgba(128, 128, 128, 0.411)">
                    <div class="w-100 h-50 pt-1">
                        <a href="{{ route('products.show', $feedback?->product?->id) }}"
                            style="color: rgba(0, 0, 255, 0.596)">
                            <h2 class="p-0 m-0 nunito multiline-truncate text-uppercase"
                                style="font-size: 15px;text-align:justify;-webkit-line-clamp:3!important">
                                {{ $feedback?->product?->weight . ' ct ' . $feedback?->product?->name }}</h2>
                        </a>
                    </div>
                    <div class="w-100 h-50  d-flex flex-column align-items-start justify-content-center pb-1">
                        <div class="w-100 h-30 nunito-regular" style="font-size:13px">
                            {{ $feedback->created_at->diffForHumans() }}
                        </div>
                        <div class="w-100 h-30 nunito-regular" style="font-size:13px">Auction ID:
                            GM00{{ $feedback->product->id ?? '' }}
                        </div>
                        {{-- <div class="w-100 h-30 nunito-regular" style="font-size:13px">
                            {{ $feedback->product->store->name ?? '' }}
                        </div> --}}
                    </div>
                </div>
                <div class="w-35 d-flex flex-column justify-content-start align-items-center gap-1">
                    @if ($user == 'buyer')
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-up fs-6 fw-medium"></i><span class="fs-6 fw-medium">Sent</span>
                        </div>
                    @elseif ($user == 'seller')
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-down fs-6 fw-medium"></i><span class="fs-6 fw-medium">Received</span>
                        </div>
                    @endif
                    <span>{{ $feedback->user->name ?? 'N/A' }}</span>
                    @if ($feedback->nature == 'positive')
                        <span class="rounded-1 px-2"
                            style="display: block;background-color:green;color:white;font-size:14px">Positive
                        </span>
                    @elseif ($feedback->nature == 'neutral')
                        <span class="rounded-1 px-2"
                            style="display: block;background-color:orange;color:white;font-size:14px">Neutral
                        </span>
                    @elseif ($feedback->nature == 'negative')
                        <span class="rounded-1 px-2"
                            style="display: block;background-color:red;color:white;font-size:14px">Negative
                        </span>
                    @endif
                    <span class="w-100 multiline-truncate text-center">
                        "{{ $feedback->feedback ?? 'N/A' }}"
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</section>
