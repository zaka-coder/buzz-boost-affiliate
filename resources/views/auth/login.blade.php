@extends('layouts.guest')
@section('css')
    <style>
        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: #ffffff;
            padding: 30px;
            width: 450px;
            border-radius: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
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
            color: #151717;
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
            margin-left: 15px;
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
            margin: 20px 0 10px 0;
            background-color: #105082;
            border: none;
            color: white;
            font-size: 15px;
            font-weight: 500;
            border-radius: 10px;
            height: 50px;
            width: 100%;
            cursor: pointer;
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
    </style>
@endsection
@section('content')
    <div class="h-100 w-100 d-flex align-items-center justify-content-center">
        <form class="form m-auto shadow-lg " method="POST" action="{{ route('login') }}">
            <h1 class="display-6 fs-3 fw-bold text-center m-2">Sign In</h1>
            @csrf
            <div class="flex-column">
                <label>Email </label>
            </div>
            <div class="inputForm">
                <span>@</span>
                <input placeholder="Enter your Email" class="input" type="text" name="email">
            </div>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div class="flex-column">
                <label>Password </label>
            </div>
            <div class="inputForm">
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
            <button class="button-submit" type="submit">Sign In</button>
            <p class="p">Don't have an account? <a href="{{ route('register') }}"
                    class="span text-decoration-none">Sign
                    up</a>
            </p>
        </form>
    </div>
@endsection
