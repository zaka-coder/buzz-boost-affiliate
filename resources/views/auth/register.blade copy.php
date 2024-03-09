<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gems Horber - Register</title>
    <link rel="shortcut icon" href="{{ asset('assets/buyer-assets/favicon.ico') }}" type="image/x-icon">
    {{-- bootstrap cdn link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    {{-- bootstrap icon cdn link --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
        integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    {{-- libraries cdn --}}
    <link rel="stylesheet" href="https://unpkg.com/sheryjs/dist/Shery.css" />
    {{-- custom css links --}}
    <link rel="stylesheet" href="{{ asset('assets/css/auth-css/auth.css') }}">
</head>

<body>
    <a href="/" class="position-absolute backButton" style="top:3%;left:10%">
        <div class="svg-wrapper-1">
            <div class="svg-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"></path>
                    <path fill="currentColor"
                        d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z">
                    </path>
                </svg>
            </div>
        </div>
        <span>Home</span>
    </a>
    <div class="row main-row">
        <div class="col-md-6 image-part">
            <img src="{{ asset('assets/buyer-assets/diamond.png') }}" alt="" class=" rock-image ">
        </div>
        <div class="col-md-6 credential-part">
            <div class="col-md-12 header-part">
                <h2>Register</h2>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="col-md-12 body-part">
                    <div class="input">
                        <legend>Name</legend>
                        <input type="text" name="name" value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input">
                        <legend>Email</legend>
                        <input type="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input">
                        <legend>Username</legend>
                        <input type="text" name="username" value="{{ old('username') }}">
                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input">
                        <legend>Password</legend>
                        <input type="password" name="password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input">
                        <legend>Confirm Password</legend>
                        <input type="password" name="password_confirmation">
                    </div>
                    <div class="login-direction">
                        <p class="ms-auto">Have an account? <a href="{{ route('login') }}">Login</a></p>
                    </div>
                </div>
                <div class="col-md-12 footer-part">
                    <button type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
    {{-- bootstrap cdn links --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    {{-- libraries cdn links --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"
        integrity="sha512-16esztaSRplJROstbIIdwX3N97V1+pZvV33ABoG1H2OyTttBxEGkTsoIVsiP1iaTtM8b3+hu2kB6pQ4Clr5yug=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://unpkg.com/sheryjs/dist/Shery.js"></script> <!-- Recommended -->
    <script>
        Shery.makeMagnet('.image-part', {
            ease: "cubic-bezier(0.23, 1, 0.320, 1)",
            duration: 1,
        });
    </script>
</body>

</html>
