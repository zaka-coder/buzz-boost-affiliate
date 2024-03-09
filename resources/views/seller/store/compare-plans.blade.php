@extends('layouts.seller', ['title' => 'Store Details'])
@section('css')
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        .anchor-button {
            /* Common styles for both anchor and button */
            display: inline-block;
            text-decoration: none;
            font-size: 16px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            color: #333;
            cursor: pointer;
            width: 150px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            color: #105082;
            font-family: nunito;
            background-color: transparent;
        }

        .anchor-button.active {
            background-color: #105082;
            color: white;
        }

        /* CSS for loader */
        .loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 1000;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .activePlan {
            background-color: #F1F1F1;
        }

        .status {
            display: block;
            width: fit-content;
            padding: 0px 10px;
            border-radius: 100px;
            color: white;
            font-size: 12px;
            background-color: #105082;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the plans section -->
    <div class="settings-main-container w-100 h-100">

        <!-- Header section within the plans container -->
        @include('seller.store.store_details_header')

        <!-- plans starts from here -->
        <div class="w-100 h-auto p-3" style="background-color:#FFF;">
            <div class="w-100 h-100">
                @foreach ($plans as $plan)
                    <div class="row  rounded-1 border border-2 my-3   @if ($plan->id == $store->plan_id) activePlan @else transparent @endif"
                        style="height: 150px">
                        <div class="col-12 d-flex align-items-center justify-content-between pe-1 pe-lg-3 ps-4 pt-1 "
                            style="height: 35px;border-bottom: 1px solid #1e1e1f23">
                            <h5 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 19px">
                                {{ $plan->name ?? 'N/A' }}
                                <span
                                    style="font-size: 12px">{{ $store->plan_id == $plan->id ? '(Current Plan)' : '' }}</span>
                            </h5>
                            <h5 style="font-size: 14px;font-weight:700">Store Charges : <span style="color: #105082"><b>
                                        @if ($plan->name == 'Starter')
                                            Free
                                        @else
                                            ${{ $plan->price }}
                                        @endif
                                    </b></span></h5>
                        </div>
                        <div class="col-12 col-md-6" style="height: 85px">
                            <div class="px-4 pt-2 d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill" style="color:yellowgreen"></i>
                                <h4 class="m-0 p-0" style="font-size: 16px;font-weight: 400">Upload
                                    {{ $plan->name == 'Platinum' ? 'Unlimited' : 'Upto ' . $plan->buyitnow_items }} Buy It
                                    Now
                                    Items
                                </h4>
                            </div>
                            <div class="px-4 pt-2 d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill" style="color:yellowgreen"></i>
                                <h4 class="m-0 p-0" style="font-size: 16px;font-weight: 400">Upload
                                    {{ $plan->name == 'Platinum' ? 'Unlimited' : 'Upto ' . $plan->auctions_items }} Auction
                                    Items</h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-6  d-flex align-items-start justify-content-end px-3">
                            @if ($plan->id == $store->plan_id)
                                <span class="status"> Selected</span>
                            @else
                                <a href="javascript:void(0)" data-plan-id="{{ $plan->id }}"
                                    data-plan-name="{{ $plan->name }}" {{-- data-bs-target="#paymentModal{{ $plan->id }}"
                                    data-bs-toggle="modal" --}}
                                    class="border-0 bg-transparent upgradeBtn">Upgrade</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@section('modals')
    @foreach ($plans as $plan)
        <div id="paymentModal{{ $plan->id }}" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Selected Plan: {{ $plan->name ?? 'N/A' }}</h5>
                        <button type="button" class="close p-2" data-bs-dismiss="modal"
                            aria-label="Close" style="border: none;
                            background: transparent;
                            font-size: 19px">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form{{ $plan->id }}" action="{{ route('seller.store.plans.update', $store->id) }}"
                        method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <div class="mb-5 mt-2 w-75 mx-auto">
                                <h5>Plan Details</h5>
                                <p>Plan Name: {{ $plan->name ?? 'N/A' }}</p>
                                <p>Plan Charges: ${{ $plan->price ?? 'N/A' }}</p>

                                <label for="card_elements_container{{ $plan->id }}" class="my-4">
                                    Card Details
                                </label>

                                <div class="rounded w-100">
                                    <div id="card_elements_container{{ $plan->id }}" class="p-2"
                                        style="background-color: #f3f3f3">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                </div>

                                <!-- Use this to display form errors. -->
                                <div id="card-errors{{ $plan->id }}" role="alert" class="text-danger mt-2"></div>
                            </div>

                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upgrade</button>
                        </div>
                        <!-- Loader element -->
                        <div class="loader" style="display: none;">
                            <!-- You can add a loading spinner or any other visual indicator here -->
                            <div class="spinner"></div>
                            <p>Please wait while we verify your payment details...</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($plan->name == 'Starter')
            <div id="starterPlanModal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Selected Plan: {{ $plan->name ?? 'N/A' }}</h5>
                            <button type="button" class="close p-2 bg-transparent border-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 19px">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="starterPlanForm" action="{{ route('seller.store.plans.update', $store->id) }}"
                            method="post">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <div class="mb-5 mt-2 w-75 mx-auto">
                                    <h5>Plan Details</h5>
                                    <p>Plan Name: {{ $plan->name ?? 'N/A' }}</p>
                                    <p>Plan Charges: ${{ $plan->price ?? 'N/A' }}</p>

                                    <div class="rounded w-100">
                                        <div class="p-2" style="background-color: #f3f3f3">
                                            Do you want to upgrade to the Starter plan?
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-primary">Yes</button>
                            </div>
                            <!-- Loader element -->
                            <div class="loader" style="display: none;">
                                <!-- You can add a loading spinner or any other visual indicator here -->
                                <div class="spinner"></div>
                                <p>Please wait while we verify your payment details...</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('js')
    <!-- Stripe Scripts -->
    <!-- Include Stripe.js library -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $('.upgradeBtn').click(function() {
            let planId = $(this).data('plan-id');
            let planName = $(this).data('plan-name');
            const cardName = '{{ auth()->user()->name }}';
            const cardForm = document.getElementById('form' + planId);

            if (planName == 'Starter') {
                // $('#paymentModal' + planId).modal('hide');
                $('#starterPlanModal').modal('show');
                // cardForm.submit();
                return;
            } else {

                let stripe = Stripe('{{ env('STRIPE_KEY') }}')
                const elements = stripe.elements()
                const style = {
                    base: {
                        fontSize: '20px',
                        color: '#000000',
                        backgroundColor: '#f3f3f3',
                    }
                };

                const cardElement = elements.create('card', {
                    style: style,
                    hidePostalCode: true
                });
                cardElement.mount('#card_elements_container' + planId);

                $('#paymentModal' + planId).modal('show');

                // Handle real-time validation errors from the card Element
                cardElement.on('change', function(event) {
                    var displayError = document.getElementById('card-errors' + planId);
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });

                // Handle form submission
                cardForm.addEventListener('submit', async (e) => {
                    e.preventDefault()

                    // display loader
                    $('.loader').css('display', 'flex');

                    const {
                        paymentMethod,
                        error
                    } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                        billing_details: {
                            name: cardName.value
                        }
                    })
                    if (error) {
                        console.log(error)
                        $('.loader').css('display', 'none');
                        toastr.error(error.message);
                    } else {

                        let input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'payment_method_id');
                        input.setAttribute('value', paymentMethod.id);
                        cardForm.appendChild(input);

                        cardForm.submit()
                    }
                });
            }

        })
    </script>
@endsection
