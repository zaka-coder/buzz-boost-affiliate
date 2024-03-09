@extends('layouts.buyer', ['title' => 'Bids'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the bids section -->
    <div class="bids-main-container w-100 h-100">

        <!-- Header section within the bids container -->
        <div class="w-100 h-auto h-md-20">

            <!-- Filter options for bid status -->
            <div class="w-100 h-auto h-md-50 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                <a href="javascript:void(0)" type="button" id="pending-bids"
                    class="anchor-button rounded-2">Pending</a>
                <a href="javascript:void(0)" type="button" id="declined-bids" class="anchor-button rounded-2">Declined</a>
                <a href="javascript:void(0)" type="button" id="all-bids" class="anchor-button rounded-2 active">All</a>
            </div>

            <!-- Sorting options section -->
            <div class="w-100 h-50 pt-2">

                <!-- Sorting dropdown with label -->
                <div class="ms-auto" style="width: fit-content">
                    <span class="sort" style="font-size: 14px">Sort By :</span>

                    <!-- Dropdown menu for sorting -->
                    <select class="p-1" id="sort" name="sort">
                        <option value="recent">Most Recent</option>
                        <option value="old">Old</option>
                        <option value="expensive">Expensive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Main content area for displaying individual bids -->
        <div id="bids-container" class="w-100 h-80 p-md-3 p-1" style="background-color: #FFF; overflow:auto">
            {{-- ajax response will be appended here --}}
        </div>

    </div>
@endsection

@section('js')
    <script>
        var bids = null;

        const emptyHtml = `<div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                    <img src="{{ asset('assets/home/folder.png') }}" width="80px" alt=""
                        style="filter: invert(1)">
                    <p class="nunito">No bids found</p>
                </div>`;
        // jQuery Document Ready
        $(document).ready(function() {
            // gets all bids from the database using ajax
            $.ajax({
                type: "GET",
                url: "{{ url('buyer/ajax/bids') }}",
                success: function(response) {
                    // console.log(response.bids);
                    // set bids
                    bids = response.bids;
                    // empty bids container
                    $('#bids-container').empty();

                    // populate bids container
                    // check for bids count

                    if (bids.length > 0) {
                        bids.forEach(bid => {
                            populateBidsContainer(bid);
                        });
                    } else {
                        $('#bids-container').append(emptyHtml);
                    }


                },
                error: function(error) {
                    // Handle the error response from the server
                    console.error("Error getting bids:", error.responseText);
                }
            });

            // filters bids with only pending status
            $('#pending-bids').on('click', function() {
                alert('Pending bids clicked');
                $('#bids-container').html('');
                // check for bids count
                if (bids.length > 0) {
                    bids.forEach(bid => {
                        if (bid.status == 'pending') {
                            populateBidsContainer(bid);
                            console.log(bid);
                        } else {
                            $('#bids-container').html(emptyHtml);
                        }
                    })
                } else {
                    console.log('No bids found');
                    $('#bids-container').html(emptyHtml);
                }
            })

            // filters bids with only declined status
            $('#declined-bids').on('click', function() {
                alert('Declined bids clicked');
                $('#bids-container').html('');
                // check for bids count
                if (bids.length > 0) {
                    bids.forEach(bid => {
                        if (bid.status == 'declined') {
                            populateBidsContainer(bid);
                            console.log(bid);
                        } else {
                            $('#bids-container').html(emptyHtml);
                        }
                    })
                } else {
                    console.log('No bids found');
                    $('#bids-container').html(emptyHtml);
                }
            })

            // populates all bids
            $('#all-bids').on('click', function() {
                alert('All bids clicked');
                $('#bids-container').empty();
                // check for bids count
                if (bids.length > 0) {
                    bids.forEach(bid => {
                        populateBidsContainer(bid);
                    })
                } else {
                    $('#bids-container').append(emptyHtml);
                }
            })
        });

        function pendingBids() {
            $('#bids-container').empty();
            // check for bids count
            if (bids.length > 0) {
                bids.forEach(bid => {
                    if (bid.status == 'pending') {
                        populateBidsContainer(bid);
                    }
                })
            } else {
                $('#bids-container').append(emptyHtml);
            }
        }

        // function to populate bids container
        function populateBidsContainer(bid) {
            // Format the created_at date
            const createdAtDate = new Date(bid.created_at);
            const bidCreatedAt = createdAtDate.toLocaleDateString(
                'en-US', {
                    month: 'numeric',
                    day: 'numeric',
                    year: 'numeric'
                });
            $('#bids-container').append(`
                        <div class="bids-card w-95 m-auto d-flex flex-md-row flex-column align-items-center my-3">
                    <!-- Image section for the bid -->
                    <div class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('`+ bid.product.image +`') }}" alt=""
                            width="90%" height="90%" style="object-fit: cover">
                    </div>
                    <!-- Details section for the bid -->
                    <div class="w-100 w-md-45 h-100 p-2">
                        <!-- Title of the bid -->
                        <h3 class="text-truncate">` + bid.product.weight + ` ct ` + bid.product.name + `</h3>
                        <!-- Table for additional bid information -->
                        <table class="w-100">
                            <!-- Row for Auction Id -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Auction Id #:</td>
                                <td class="w-50 nunito-regular">{{ rand(100000, 999999) }}</td>
                            </tr>
                            <!-- Row for Bid Starting Date -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Bid Starting Date:</td>
                                <td class="w-50 nunito-regular">` + bidCreatedAt + `</td>
                            </tr>
                            <!-- Row for Store information -->
                            <tr class="w-100">
                                <td class="w-40 nunito-regular">Store:</td>
                                <td class="w-50 nunito-regular">` + bid.product.store.name + `</td>
                            </tr>
                        </table>
                    </div>
                    <span class="vr d-none d-md-block"></span>
                    <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                    <!-- Bid amount section -->
                    <div class="w-100 w-md-10 h-100 d-flex align-items-center justify-content-center">
                        <span class="nunito">$` + bid.price + `</span>
                    </div>
                    <span class="vr d-none d-md-block"></span>
                    <span class="d-block d-md-none w-100" style="border-top: 1px solid gray"></span>
                    <!-- Bid status section -->
                    <div class="w-100 w-md-25 h-100 d-flex align-items-center justify-content-center">
                        <span class="anchor-button m-3 bg-warning rounded-3 border-0 text-capitalize"
                            style="cursor: default">` + bid.status + `</span>
                    </div>
                </div>`);
        }
    </script>
@endsection
