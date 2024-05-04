@extends('layouts.guest')
@section('css')
    <style>
        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 30px;
            width: 90%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .register-form {
            position: absolute;
            top: 110%;
        }

        @media(max-width:998px) {
            .form {
                width: 85vw;
            }
        }

        ::placeholder {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .form button {
            align-self: flex-end;
        }

        .flex-column>label {
            color: white;
            font-weight: 600;
        }

        .inputForm {
            border: 1.5px solid #ecedec;
            border-radius: 10px;
            height: 50px;
            display: flex;
            align-items: center;
            padding-left: 10px;
            transition: 0.2s ease-in-out;
        }

        .input {
            margin-left: 10px;
            padding-left: 15px;
            border-radius: 10px;
            border: none;
            width: 100%;
            height: 100%;
        }

        @media(max-width:998px) {
            .input {
                padding-left: 0px;
            }
        }

        .input:focus {
            outline: none;
        }

        .flex-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            justify-content: space-between;
        }

        .flex-row>div>label {
            font-size: 14px;
            color: black;
            font-weight: 400;
        }

        .span {
            font-size: 14px;
            margin-left: 5px;
            color: #2d79f3;
            font-weight: 500;
            cursor: pointer;
        }

        .button-submit {
            padding: 10px 15px;
            display: inline-block;
            width: 100%;
            text-align: center;
            background: #E3CD81FF 0% 0% no-repeat padding-box;
            opacity: 1;
            border: none;
            color: black;
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            text-decoration: none;
            border-radius: 6px;
            box-shadow: var(--btn-box-shadow);
            -webkit-box-shadow: var(--btn-box-shadow);
            -moz-box-shadow: var(--btn-box-shadow);
            transition: all 0.5s ease-in-out;
            margin-top: 20px;
        }

        .p {
            text-align: center;
            color: black;
            font-size: 14px;
            margin: 5px 0;
        }

        .dynamic-part {
            background-color: transparent !important;
        }

        .menu-control {
            display: none;
        }

        i {
            color: white
        }

        .entry-btn {
            padding: 10px 15px;
            display: inline-block;
            width: 120px;
            text-align: center;
            background: #E3CD81FF 0% 0% no-repeat padding-box;
            opacity: 1;
            border: none;
            color: black;
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            text-decoration: none;
            border-radius: 6px;
            box-shadow: var(--btn-box-shadow);
            -webkit-box-shadow: var(--btn-box-shadow);
            -moz-box-shadow: var(--btn-box-shadow);
            transition: all 0.5s ease-in-out;
        }

        .hidden {
            display: none
        }
    </style>
@endsection
@section('content')
    <div class="row mt-3">
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <button class="entry-btn" id="register-btn">Register <i class="bi bi-arrow-down text-black"></i></button>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <button class="entry-btn" id="login-btn">Login</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 d-flex align-items-center justify-content-center position-relative  pt-3">
            <form class="form m-2 shadow-lg my-3 hidden " method="POST" action="{{ route('register') }}"
                id="register-form">
                <div class="w-100 m-auto">
                    <h1 class="display-6 fs-3 fw-bold text-center text-white">Register</h1>
                    @csrf
                    <div class="flex-column">
                        <label>Name</label>
                    </div>
                    <div class="inputForm mb-3">
                        <i class="bi bi-person"></i>
                        <input placeholder="Enter your Name" class="input" type="text" name="name"
                            value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="flex-column">
                        <label>Email</label>
                    </div>
                    <div class="inputForm mb-3">
                        <span class="text-white">@</span>
                        <input placeholder="Enter your Email" class="input" type="email" name="email"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="flex-column">
                        <label>Password </label>
                    </div>
                    <div class="inputForm mb-3">
                        <i class="bi bi-lock"></i>
                        <input placeholder="Enter your Password" class="input" type="password" name="password">
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="flex-column">
                        <label>Confirm Password</label>
                    </div>
                    <div class="inputForm">
                        <i class="bi bi-lock"></i>
                        <input placeholder="Enter your Confirm Password" class="input" type="password"
                            name="password_confirmation">
                    </div>
                    <button class="button-submit" type="submit">Register</button>
                    <p class="p">have an account? <a href="{{ route('login') }}" class="span text-decoration-none">Log
                            in</a>
                    </p>
                </div>
            </form>
        </div>
        <div class="col-md-6 d-flex align-items-start justify-content-center position-relative  pt-3">
            <form class="form m-2 shadow-lg my-3 hidden" method="POST" action="{{ route('login') }}" id="login-form">
                <h1 class="display-6 fs-3 fw-bold text-center text-white">Log In</h1>
                @csrf
                <div class="flex-column">
                    <label>Email </label>
                </div>
                <div class="inputForm mb-3">
                    <span class="text-white">@</span>
                    <input placeholder="Enter your Email" class="input" type="text" name="email">
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <div class="flex-column">
                    <label>Password </label>
                </div>
                <div class="inputForm mb-3">
                    <i class="bi bi-lock"></i>
                    <input placeholder="Enter your Password" class="input" type="password" name="password">
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <div class="flex-row">
                    <div>
                        <input type="radio">
                        <label>Remember me </label>
                    </div>
                    <a href="/forget-password" class="span">Forgot password?</a>
                </div>
                <button class="button-submit" type="submit">Log In</button>
                <p class="p">Don't have an account? <a href="{{ route('register') }}"
                        class="span text-decoration-none">Sign
                        up</a>
                </p>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // for register Controls
            $('#register-btn').click(function() {
                $('#register-form').toggle('slide', {
                    direction: 'up'
                }, 'slow').toggleClass('hidden');
            });
            $(document).click(function(e) {
                var registerForm = $('#register-form');
                // Check if the form is visible and the click event is outside the button or the form
                if (registerForm.is(':visible') && !$(e.target).closest('#register-btn, #register-form')
                    .length) {
                    registerForm.toggle('slide', {
                        direction: 'up'
                    }, 'slow').toggleClass('hidden');
                }
            });
            // for register Controls
            $('#login-btn').click(function() {
                $('#login-form').toggle('slide', {
                    direction: 'up'
                }, 'slow').toggleClass('hidden');
            });
            $(document).click(function(e) {
                var loginForm = $('#login-form');
                // Check if the form is visible and the click event is outside the button or the form
                if (loginForm.is(':visible') && !$(e.target).closest('#login-btn, #login-form')
                    .length) {
                    loginForm.toggle('slide', {
                        direction: 'up'
                    }, 'slow').toggleClass('hidden');
                }
            });
        });
    </script>
@endsection
