@extends('layouts.buyer', ['title' => 'Credit Card Settings'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-dashboard.css') }}">
    <style>
        .dynamic-part {
            background-color: transparent !important;
        }

        legend {
            font-size: 17px;
            font-family: nunito-regular;
            line-height: 1;
        }

        .setting-input {
            height: 34px;
            border-radius: 8px;
            padding-left: 9px;
            border: none;
            background-color: rgba(34, 34, 34, 0.132) !important;
        }

        .setting-input:active,
        .setting-input:focus {
            box-shadow: none;
            outline: none;
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

        .special_for--card {
            display: inline-block;
            width:150px;
            word-wrap: break-word;
        }
    </style>
@endsection
@section('content')
    <!-- Container for the setting section -->
    <div class="bids-main-container w-100 h-100">
        <!-- Header section within the setting container -->
        <div class="w-100 h-auto h-md-15">
            <!-- navbar area -->
            <div class="w-100 h-auto h-md-100 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
                <a href="{{ route('buyer.settings.index') }}" class="anchor-button rounded-2">Basic Settings</a>
                <a href="{{ route('buyer.settings.shipping') }}" class="anchor-button rounded-2">Shipping Address</a>
                <a href="#" class="anchor-button rounded-2 active">Credit Card</a>
                <a href="{{ route('password.change') }}" class="anchor-button rounded-2">Change Password</a>
            </div>
        </div>
        <!-- Main content area for displaying individual bids -->
        <div class="w-100 h-auto p-4" style="background-color: #FFF;">
            {{-- Shipping Address Form --}}
            <form id="form" action="{{ route('buyer.settings.store.credit-card') }}" method="POST">
                @csrf
                <div class="row w-100">
                    <h1 class="fs-5 fw-bold">Add New Credit Card</h1>
                    <div class="col-md-12 my-4 rounded-1 p-2 d-flex align-items-center justify-content-evenly gap-2"
                        style="outline: 1px solid rgba(0, 0, 0, 0.342)">
                        <i class="bi bi-info-circle"></i>
                        Add a credit card to place bids. You can pay a seller with any supported method including Credit
                        Card and Paypal.
                    </div>
                    {{-- <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>Name on Card</legend>
                            <input type="text" name="name" class="w-100 w-md-90 setting-input"
                                value="{{ old('name') }}">
                        </fieldset>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>Card Number</legend>
                            <input type="number" name="card_number" class="w-100 w-md-90 setting-input"
                                value="{{ old('card_number') }}">
                        </fieldset>
                        @error('card_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>Expiry Date</legend>
                            <input type="date" name="expiry_date" class="w-100 w-md-90 setting-input"
                                value="{{ old('expiry_date') }}">
                        </fieldset>
                        @error('expiry_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 my-2">
                        <fieldset class="w-100">
                            <legend>CVC</legend>
                            <input type="text" name="cvc" class="w-100 w-md-90 setting-input"
                                value="{{ old('cvc') }}">
                        </fieldset>
                        @error('cvc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}

                    <div class="my-5 w-50 me-auto">
                        <label for="card_elements_container" class="mb-4">
                            Card Details
                        </label>

                        <div class="rounded w-100">
                            <div id="card_elements_container">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                        </div>

                        <!-- Use this to display form errors. -->
                        <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                    </div>
                    <input type="hidden" name="cardNumber" id="cardNumber">
                    <input type="hidden" name="expiryMonth" id="expiryMonth">
                    <input type="hidden" name="expiryYear" id="expiryYear">

                    <!-- Loader element -->
                    <div id="loader" class="loader" style="display: none;">
                        <!-- You can add a loading spinner or any other visual indicator here -->
                        <div class="spinner"></div>
                        <p>Please wait while we verify your payment details...</p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <button type="submit" class="anchor-button rounded-2 text-white"
                            style="background-color:#105082">Save</button>
                    </div>
                </div>
            </form>
            <div class="my-5 w-100">
                <h1 class="fs-5 fw-bold">Saved Credit Cards</h1>
                <table class="table w-100">
                    <tr class="w-100">
                        <td class="nunito w-5 text-center">#</td>
                        <td class="nunito w-25 text-center">Name on Card</td>
                        <td class="nunito w-15 text-center">Card Number</td>
                        <td class="nunito w-20 text-center">Expiry Date</td>
                        <td class="nunito w-20 text-center">Date Added</td>
                        <td class="nunito w-20 text-center">Default</td>
                        <td class="nunito w-15 text-center">Action</td>
                    </tr>
                    @foreach ($user->credit_cards as $card)
                        <tr class="">
                            <td class="nunito-regular  text-center">{{ $loop->iteration }}</td>
                            <td class="nunito-regular  text-center ">
                                <div class="special_for--card">
                                    {{ $card->name ?? '' }}
                                </div>
                            </td>
                            <td class="nunito-regular text-center">
                                <div class=" special_for--card">
                                    *******{{ $card->card_number ?? '' }}
                                </div>
                            </td>
                            <td class="nunito-regular text-center">
                                <div class=" special_for--card">
                                    {{ $card?->expiry_month . '/' . $card?->expiry_year ?? '' }}
                                </div>
                            </td>
                            <td class="nunito-regular text-center">
                                {{ $card->created_at->format('d M Y') ?? '' }}
                            </td>
                            <td class="nunito-regular text-center">{{ $card->default ? 'Yes' : 'No' }}</td>
                            <td class="nunito-regular text-center">
                                <button type="button" class="border-0 bg-transparent" title="Delete" data-bs-toggle="modal"
                                    data-bs-target="#delete-confirmation-modal-{{ $card->id }}"><i
                                        class="bi bi-trash text-danger"></i></button>
                                @if (!$card->default)
                                    <button type="button" class="border-0 bg-transparent" title="Set As Default"
                                        onclick="document.getElementById('set-default-{{ $card->id }}').submit()">
                                        <i class="bi bi-check2-circle text-primary"></i>
                                    </button>
                                    <form method="POST" id="set-default-{{ $card->id }}"
                                        action="{{ route('buyer.settings.default.credit-card', $card->id) }}">
                                        @csrf
                                    </form>
                                @else
                                    <i class="bi bi-check2-square text-white"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

    </div>
@endsection

@section('modals')
    {{-- create delete confirmation modal using bootstrap modal --}}
    @foreach ($user->credit_cards as $card)
        <div class="modal fade" id="delete-confirmation-modal-{{ $card->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this credit card?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('buyer.settings.delete.credit-card') }}" method="POST">
                            @csrf
                            <input type="hidden" name="card_id" value="{{ $card->id }}">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('js')
    <!-- Stripe Scripts -->
    <!-- Include Stripe.js library -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let stripe = Stripe('{{ env('STRIPE_KEY') }}')
        const elements = stripe.elements()
        const style = {
            base: {
                fontSize: '20px',
                color: '#000000',
                lineHeight: '50px',
                backgroundColor: '#f3f3f3',

                // '::placeholder': {
                //     color: '#000000'
                // },
            },
        };

        const cardElement = elements.create('card', {
            style: style,
            hidePostalCode: true
        });
        cardElement.mount('#card_elements_container');

        // Handle real-time validation errors from the card Element
        cardElement.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        const cardName = '{{ auth()->user()->name }}';
        const cardForm = document.getElementById('form');

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
                // card: cardNumberElement,
                billing_details: {
                    name: cardName.value
                }
            })
            if (error) {
                console.log(error)
                $('.loader').css('display', 'none');
                toastr.error(error.message);
            } else {

                // Extract the values of card-number, cvc and card-expiry from the payment method object
                const cardNumber = paymentMethod.card.last4;
                const cardExpiryMonth = paymentMethod.card.exp_month;
                const cardExpiryYear = paymentMethod.card.exp_year;

                $('#cardNumber').val(cardNumber);
                $('#expiryMonth').val(cardExpiryMonth);
                $('#expiryYear').val(cardExpiryYear);

                let input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('name', 'payment_method_id');
                input.setAttribute('value', paymentMethod.id);
                cardForm.appendChild(input);

                cardForm.submit()
            }
        });

        // Update an element with details collected elsewhere on your page
        // var myPostalCodeField = document.querySelector('input[name="my-postal-code"]');
        // myPostalCodeField.addEventListener('change', function(event) {
        //     cardElement.update({
        //         value: {
        //             postalCode: event.target.value
        //         }
        //     });
        // });
    </script>
@endsection
