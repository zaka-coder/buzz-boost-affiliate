{{-- @extends('layouts.buyer', ['title' => 'Buyer Profile']) --}}
@extends('layouts.guest', ['title' => 'Buyer Profile'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/buyer-css/buyer-profile.css') }}">
    <style>
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
    </style>
@endsection
@section('content')
    <form id="form" action="{{ route('buyer.profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="carousel slide carousel-fade" id="carouselExampleFade">
            {{-- welcome slide html --}}
            <div class="carousel-item active welcome-slide">
                <div class="rock-center">
                    <img src="{{ asset('assets/buyer-assets/rock.png') }}" alt="">
                </div>
                <div class="welcome-user">
                    <h1 class="text-center">Hello <span class="user__name px-4">{{ Auth::user()->name }}</span>, Welcome to
                        Gems Harbor!</h1>
                    <p class="text-center">Thanks for registering your account! We know that you are keen to start bidding
                        right away but we
                        recommend you
                        complete a couple of extra steps to unlock all the features of your verification. It will take just
                        a few
                        moments.</p>
                </div>
                <div class="welcome-footer">
                    <button type="button" class="started" type="button" class="next carousel-control-next"
                        data-bs-target="#carouselExampleFade" data-bs-slide="next">Get Started</button>
                    {{-- <button type="button" class="next carousel-control-next" data-bs-target="#carouselExampleFade"
                        data-bs-slide="next">Next</button> --}}
                </div>
            </div>
            {{-- email slide html --}}
            {{-- <div class="carousel-item email-slide ">
                <div class="email-main">
                    <div class="email-header-part ">
                        <img src="{{ asset('assets/buyer-assets/email.png') }}" alt="">
                    </div>
                    <div class="email-body-part ">
                        <p>Please check your inbox to verify your account</p>
                    </div>
                    <div class="email-footer-part ">
                        <button type="button" class="previous me-auto carousel-control-prev"
                            data-bs-target="#carouselExampleFade" data-bs-slide="prev">Previous</button>
                        <button type="button" class="next ms-auto carousel-control-next"
                            data-bs-target="#carouselExampleFade" data-bs-slide="next">Next</button>
                    </div>
                </div>
            </div> --}}
            {{-- profile slide html --}}
            <div class="carousel-item  profile-slide">
                <div class="profile-main">
                    <div class="profile-header-part ">
                        <div class="profile-header-two-part">
                            <img src="{{ asset('assets/buyer-assets/folder.png') }}" alt="">
                        </div>
                    </div>
                    <div class="profile-body-part">
                        <p class="">Upload Your Profile Picture</p>
                        <input class="mb-3 profile-picture form-control" type="file" name="image" id="">
                        {{-- <div class="contact">
                            <legend>Contact <span>*</span></legend>
                            <input class="" type="tel" name="phone" id="" required>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div> --}}
                    </div>
                    <div class="profile-footer-part ">
                        <button type="button" class="previous me-auto carousel-control-prev"
                            data-bs-target="#carouselExampleFade" data-bs-slide="prev">Previous</button>
                        <button type="button" class="next ms-auto carousel-control-next">Next</button>
                    </div>
                </div>
            </div>
            {{-- shipping slide html --}}
            <div class="carousel-item shipping-slide">
                <div class="shipping-main">
                    <div class="shipping-header-part">
                        <h1>Shipping Details</h1>
                    </div>
                    <div class="shipping-body-part">
                        {{-- <div class="contact">
                            <legend>Phone <span>*</span></legend>
                            <input class="" type="tel" name="phone" id="" required>
                        </div> --}}
                        <div class="country">
                            <legend>Country <span>*</span></legend>
                            <input class="" type="text" name="country" id="" required>
                            {{-- <small>this is an error</small> --}}
                        </div>
                        <div class="city">
                            <legend>City <span>*</span></legend>
                            <input class="" type="text" name="city" id="" required>
                            {{-- <small>this is an error</small> --}}
                        </div>
                        <div class="state">
                            <legend>State <span>*</span></legend>
                            <input class="" type="text" name="state" id="" required>
                            {{-- <small>this is an error</small> --}}
                        </div>
                        <div class="zipcode">
                            <legend>Zip Code <span>*</span></legend>
                            <input class="" type="number" name="zip" id="" required>
                            {{-- <small>this is an error</small> --}}
                        </div>
                        <div class="address">
                            <legend>Address <span>*</span></legend>
                            <input class="" type="text" name="address" id="" required>
                            {{-- <small>this is an error</small> --}}
                        </div>
                    </div>
                    <div class="shipping-footer-part">
                        <button type="button" class="previous me-auto carousel-control-prev"
                            data-bs-target="#carouselExampleFade" data-bs-slide="prev">Previous</button>
                        <button type="button" class="next ms-auto carousel-control-next">Next</button>
                    </div>
                </div>
            </div>
            {{-- payement slide html --}}
            <div class="carousel-item payement-slide">
                <div class="payement-main">
                    <div class="payement-header-part">
                        <h1>Payement Details</h1>
                    </div>
                    <div class="payement-body-part pt-4">

                        {{-- <div class="country">
                            <legend>Name on Card <span>*</span></legend>
                            <input class="" type="text" name="name" id="" required>
                            <!-- <small>this is an error</small> -->
                        </div>
                        <div class="city">
                            <legend>Card Number <span>*</span></legend>
                            <input class="" type="number" name="card_number" id="card-number" required>
                            <!-- <div id="card-number"></div> -->
                        </div>
                        <div class="state">
                            <legend>Expiry Date <span>*</span></legend>
                            <input class="" type="date" name="expiry_date" id="card-expiry" required>
                            <!-- <div id="card-expiry"></div> -->
                        </div>
                        <div class="zipcode">
                            <legend>CVC <span>*</span></legend>
                            <input class="" type="number" name="cvc" id="card-cvc" required>
                            <!-- <div id="card-cvc"></div> -->
                        </div> --}}


                        <div class="my-5 w-50 mx-auto">
                            <label for="card_elements_container" class="mb-4">
                                Card Details
                            </label>

                            <div class="rounded w-100">
                                <div id="card_elements_container" class="p-2" style="background-color: #f3f3f3">
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
                    <div class="payement-footer-part">
                        <button type="button" class="previous me-auto carousel-control-prev"
                            data-bs-target="#carouselExampleFade" data-bs-slide="prev">Previous</button>
                        <button type="submit" class="next carousel-control-next ms-auto">Finish</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script>
        // display error message in toastr if any
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif

        $(document).ready(function() {
            var totalSlides = $(".carousel-item").length;

            // Add event listeners for each carousel item
            $(".carousel-item").each(function(index) {
                var currentSlide = $(this);
                var nextButton = currentSlide.find(".carousel-control-next");
                var previousButton = currentSlide.find(".carousel-control-prev");

                // Handle next button click
                nextButton.click(function(event) {
                    if (validateInputs(currentSlide)) {
                        if (index === totalSlides - 1) {
                            // If it's the last slide, submit the form
                            // $("#form").submit();
                        } else {
                            // Move to the next slide
                            $("#carouselExampleFade").carousel('next');
                        }
                    } else {
                        // Prevent form submission on validation failure
                        event.preventDefault();
                    }
                });

                // Handle previous button click
                previousButton.click(function() {
                    // Move to the previous slide
                    $("#carouselExampleFade").carousel('prev');
                });
            });

            // Function to validate inputs for a specific carousel item
            function validateInputs(slide) {
                // Your validation logic goes here
                var isValid = true;

                // Example validation (you can replace this with your own validation)
                slide.find("input").each(function() {
                    $(this).next(".error-message").remove();
                    if ($(this).prop("required") && $(this).val().trim() === "") {
                        // Display error for required fields
                        $(this).next(".error-message").remove(); // Remove existing error message
                        $(this).after("<small class='error-message'>This field is required</small>");
                        isValid = false;
                    } else {
                        // Add specific validation for card_number input
                        if ($(this).attr("name") === "card_number") {
                            var cardNumber = $(this).val().trim();
                            if (cardNumber.length < 13 || cardNumber.length > 19) {
                                $(this).next(".error-message").remove();
                                $(this).after(
                                    "<small class='error-message'>Invalid credit card number</small>");
                                isValid = false;
                            }
                        }
                        // Add specific validation for cvc input
                        else if ($(this).attr("name") === "cvc") {
                            var cvc = $(this).val().trim();
                            if (cvc.length < 3 || cvc.length > 4) {
                                $(this).next(".error-message").remove();
                                $(this).after("<small class='error-message'>Invalid CVV</small>");
                                isValid = false;
                            }
                        }
                        // Add specific validation for expiry_date input
                        else if ($(this).attr("name") === "expiry_date") {
                            var expiryDate = $(this).val().trim();
                            if (!isValidExpiryDate(expiryDate)) {
                                $(this).next(".error-message").remove();
                                $(this).after("<small class='error-message'>Card is expired</small>");
                                isValid = false;
                            }
                        }
                        // Add more validation rules as needed
                    }
                });

                return isValid;
            }

            // Function to validate card expiry date
            function isValidExpiryDate(expiryDate) {
                var currentDate = new Date();
                var currentYear = currentDate.getFullYear();
                var currentMonth = currentDate.getMonth() + 1; // Month is zero-based

                // Parse expiryDate to extract year, month, and day
                var parts = expiryDate.split('-');
                var inputYear = parseInt(parts[0], 10);
                var inputMonth = parseInt(parts[1], 10);
                var inputDay = parseInt(parts[2], 10);

                // Compare with the current date
                if (inputYear < currentYear ||
                    (inputYear === currentYear && inputMonth < currentMonth) ||
                    (inputYear === currentYear && inputMonth === currentMonth && inputDay < currentDate.getDate())
                ) {
                    return false; // Card is expired
                }

                return true;
            }

        });
    </script>

    <!-- Stripe Scripts -->
    <!-- Include Stripe.js library -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let stripe = Stripe('{{ getenv('STRIPE_KEY') }}')
        const elements = stripe.elements()
        const style = {
            base: {
                fontSize: '20px',
                color: '#000000',
                // lineHeight: '50px',
                backgroundColor: '#f3f3f3',
                // '::placeholder': {
                //     color: '#000000'
                // },
            }
        };

        const cardElement = elements.create('card', {
            style: style,
            hidePostalCode: true
        });
        cardElement.mount('#card_elements_container');


        // var cardNumberElement = elements.create('cardNumber', {
        //     style: style,
        // });
        // var cardExpiryElement = elements.create('cardExpiry', {
        //     style: style,
        // });
        // var cardCvcElement = elements.create('cardCvc', {
        //     style: style,
        // });

        // cardNumberElement.mount('#card-number');
        // cardExpiryElement.mount('#card-expiry');
        // cardCvcElement.mount('#card-cvc');

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
