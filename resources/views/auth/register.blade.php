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

        /*
                    .btn {
                        margin-top: 10px;
                        width: 100%;
                        height: 50px;
                        border-radius: 10px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-weight: 500;
                        gap: 10px;
                        border: 1px solid #ededef;
                        background-color: white;
                        cursor: pointer;
                        transition: 0.2s ease-in-out;
                    }

                    .btn:hover {
                        border: 1px solid #2d79f3;
                        ;
                    } */

        .dynamic-part {
            background-color: transparent !important;
        }
    </style>
@endsection
@section('content')
    <form class="form m-auto shadow-lg my-3" method="POST" action="{{ route('register') }}">
        <h1 class="display-6 fs-3 fw-bold text-center m-2">Register</h1>
        @csrf
        <div class="flex-column">
            <label>Name</label>
        </div>
        <div class="inputForm">
            <i class="bi bi-person"></i>
            <input placeholder="Enter your Name" class="input" type="text" name="name" value="{{ old('name') }}">
        </div>
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <div class="flex-column">
            <label>Email</label>
        </div>
        <div class="inputForm">
            <span>@</span>
            <input placeholder="Enter your Email" class="input" type="email" name="email" value="{{ old('email') }}">
        </div>
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        {{-- <div class="flex-column">
                        <label>Username</label>
                    </div>
                    <div class="inputForm">
                        <i class="bi bi-person"></i>
                        <input placeholder="Enter your Username" class="input" type="text" name="username"
                            value="{{ old('username') }}">
                    </div>
                    @error('username')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror --}}
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
        <div class="flex-column">
            <label>Confirm Password</label>
        </div>
        <div class="inputForm">
            <i class="bi bi-lock"></i>
            <input placeholder="Enter your Confirm Password" class="input" type="password" name="password_confirmation">
        </div>
        <button class="button-submit" type="submit">Register</button>
        <p class="p">have an account? <a href="{{ route('login') }}" class="span text-decoration-none">Sign
                in</a>
        </p>
    </form>
@endsection
