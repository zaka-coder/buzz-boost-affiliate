@extends('layouts.buyer', ['title' => 'Support'])
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .modal legend {
            font-size: 14px;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: 0px;
            color: #000000;
            opacity: 1;
        }

        .modal input {
            border-radius: 4px;
            opacity: 1;
            padding-left: 10px;
            padding-right: 10px;
            border: none;
            width: 100%;
            background-color: rgba(34, 34, 34, 0.132);
            border-radius: 6px;
            opacity: 1;
            height: 35px;
        }

        .modal select {
            border: none;
            border-radius: 8px;
            padding-left: 15px;
            padding-right: 15px;
            background-color: rgba(34, 34, 34, 0.132);
            font-size: 14px;
            font-weight: 400;
            line-height: 1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .modal select:focus {
            outline: none;
            box-shadow: none;
        }

        .modal textarea {
            padding-left: 10px;
        }

        .modal textarea:focus {
            outline: none;
            box-shadow: none;
        }

        .status {
            display: block;
            width: fit-content;
            padding: 0px 10px;
            border-radius: 100px;
            color: white;
            font-size: 12px;
        }

        .continue-ticket {
            background-color: #105082;
            color: white;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the offers section -->
    <div class="bids-main-container w-100 h-100">
        <div class="w-100 h-10">
            <button class="ms-auto text-white rounded-1 border-0 text-capitalize" type="button"
                class="bg-transparent border-0 text-white" data-bs-toggle="modal" data-bs-target="#supportTicketIndex"
                style="display: block;background-color:#105082;width:fit-content;padding:8px 17px">Open a new support
                Ticket</button>
        </div>
        <!-- Main content area for displaying supports list -->
        <div class="w-100 {{ $supports->count() > 0 ? 'h-90' : 'h-auto' }} table-responsive">
            <table class="w-100">
                <tr class="w-100" style="background-color:#EFF3F7">
                    <td class="w-auto w-md-5  nunito text-center head-before" style="height:45px">#</td>
                    <td class="w-auto w-md-40 nunito text-center head-before" style="height:45px">Question</td>
                    <td class="w-auto w-md-20 nunito text-center head-before" style="height:45px">Category</td>
                    <td class="w-auto w-md-15 nunito text-center head-before" style="height:45px">Status</td>
                    <td class="w-auto w-md-20 nunito text-center head-before" style="height:45px">Created at</td>
                </tr>
                @foreach ($supports as $support)
                    <tr class="w-100" style="background-color:#FFF;border-top:15px solid  #F8F7FA">
                        <td class="w-auto w-md-5  nunito-regular text-center" style="height:60px">
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#supportTicketView{{ $support->id }}"><span
                                        class="continue-ticket animate__animated">{{ $support->id }}</span></a>
                            </div>
                        </td>
                        <td class="w-auto w-md-40 nunito-regular text-center" style="height:60px">{{ $support->message }}
                        </td>
                        <td class="w-auto w-md-20 nunito-regular text-center text-capitalize" style="height:60px">
                            {{ $support->category }}</td>
                        <td class="w-auto w-md-15 nunito-regular text-center" style="height:60px">
                            <span class="w-50 m-auto status"
                                style="background-color: @if ($support->status == 'Resolved') #28A745; @elseif($support->status == 'Pending')#ffc107; @else #6C757D; @endif)">{{ $support->status }}</span>
                        </td>
                        <td class="w-auto w-md-20 nunito-regular text-center text-capitalize" style="height:60px">
                            {{ $support->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @if ($supports->count() == 0)
            <div class="w-100 h-80 d-flex flex-column align-items-center justify-content-center gap-2">
                <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt="" style="filter: invert(1)">
                <p class="nunito">No tickets found</p>
            </div>
        @endif

    </div>
@endsection

@section('modals')
    <!-- modal section -->
    <div class="modal fade" id="supportTicketIndex" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false" style="z-index: 99999">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content p-3  position-relative">
                <div class="modal-header w-100 position-relative" style="border: 0;--bs-modal-header-padding:0">
                    <h1>&nbsp;</h1>
                    <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                        aria-label="Close" style="right: 0!important;margin:0"></button>
                </div>
                <div class="modal-body p-0 m-0 w-100" style="border: none">
                    <form action="{{ route('support.store', ['role' => 'buyer']) }}" method="post">
                        @csrf
                        <div class="w-95  m-auto">
                            <legend>Question</legend>
                            <textarea name="message" id="" cols="5" rows="5" class="w-100" required></textarea>
                        </div>
                        <div class="row p-md-4">
                            <div class="col-md-6">
                                <legend>Which Category best discribes your question?</legend>
                                <select name="category" id="" class="w-100" style="height: 34px">
                                    <option value="Order">Order</option>
                                    <option value="Bid">Bid</option>
                                    <option value="Store">Store</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Policy">Policy</option>
                                    <option value="Bug">Bug</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-100 my-3">
                            <button type="submit" class="px-3 m-auto d-block" style="width:200px">Open Support
                                Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($supports as $support)
        <div class="modal fade" id="supportTicketView{{ $support->id }}" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 99999">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content p-3  position-relative">
                    <div class="modal-header w-100 position-relative" style="border: 0;--bs-modal-header-padding:0">
                        <div class="w-95  m-auto">
                            {{-- <h1>Category : {{ $support->category }}</h1> --}}
                            <p>Ticket #{{ $support->id }} - {{ $support->user->name }}</p>
                        </div>
                        <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                            aria-label="Close" style="right: 0!important;margin:0"></button>
                    </div>
                    <div class="modal-body p-0 m-0 w-100" style="border: none">
                        <hr>
                        <div class="w-95  m-auto">
                            <legend class="bg-light p-2">
                                {{ $support->user->name . ' - ' . $support->created_at->diffForHumans() }}</legend>
                            <p>{{ $support->message }}</p>
                        </div>
                        @foreach ($support->replies as $reply)
                            <div class="w-95 m-auto">
                                <div class="w-100 d-flex align-items-center bg-light">
                                    <div class="w-75 p-2">
                                        <legend class="m-0">
                                            {{ $reply->user->name . ' - ' . $reply->created_at->diffForHumans() }}
                                        </legend>
                                    </div>
                                    <div class="w-25 text-end pe-2">
                                        @if ($reply->attachment)
                                            <a href="{{ asset($reply->attachment) }}" target="_blank">View Attachment</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="w-100">
                                    <p>{{ $reply->message ?? '(empty)' }}</p>
                                </div>
                            </div>
                        @endforeach
                        <form
                            action="{{ route('support.reply.store', ['role' => 'buyer', 'parent_id' => $support->id]) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="w-95  m-auto">
                                {{-- <legend>Comment</legend> --}}
                                <textarea name="comment" id="" cols="5" rows="5" class="w-100"
                                    placeholder="Write something nice..." required></textarea>
                            </div>
                            <div class="w-95  m-auto">
                                <legend>Attachment</legend>
                                <input type="file" name="attachment" id="" accept="image/*"
                                    class="w-100 form-control">
                            </div>
                            <div class="w-100 my-3">
                                <button type="submit" class="px-3 m-auto d-block" style="width:200px">Reply</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
