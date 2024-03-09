@extends('layouts.' . $role, ['title' => 'Messages'])
@section('css')
    <style>
        .chat_search_wrapper input[type='search'] {
            background: #F1F1F1 0% 0% no-repeat padding-box;
            opacity: 1;
            border: none;
            height: 34px;
            margin: auto;
            display: block;
            padding-left: 40px;
            padding-right: 15px;
            border-radius: 7px;
            font-size: 14px
        }

        .chat_search_wrapper input[type='search']::placeholder {
            font-size: 14px;
            opacity: 0.65
        }

        .chat-search {
            position: absolute;
            left: 15px;
            top: 6px;
            background-color: white;
            padding: 0px 4px;
            border-radius: 5px;
            font-size: 14px
        }

        .connected_user_profile {
            background: #F1F1F1 0% 0% no-repeat padding-box;
            opacity: 1;
            width: 90%;
            margin: auto;
            border-radius: 7px;
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .connected_username {
            font-size: 17px;
            font-family: nunito;
            margin: 0
        }

        .connected_user-img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            border: 2px solid #ea008b;
        }

        .connected_user-img_big {
            border-radius: 50%;
            width: 60px;
            height: 60px;
            border: 2px solid #ea008b;
        }

        .single__connected__user {}

        .running_chat {
            background-color: #F8F7FA;
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: #3498db4f #ecf0f1;
        }

        .running_chat::-webkit-scrollbar,
        .connected__user::-webkit-scrollbar {
            width: 2px;
        }

        .running_chat::-webkit-scrollbar-thumb,
        .connected__user::-webkit-scrollbar-thumb {
            background-color: #3498db4f;
        }

        .running_chat::-webkit-scrollbar-track,
        .connected__user::-webkit-scrollbar-track {
            background-color: #ecf0f1;
        }

        .connected__user {
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: #3498db4f #ecf0f1;
        }

        .messege {
            background-color: white;
            padding: 20px 15px;
            border-radius: 12px;
            position: relative;
            font-family: nunito-regular
        }

        .messege span {
            font-family: nunito-regular;
            font-size: 13px
        }

        .time {
            position: absolute;
            right: 8px;
            bottom: 0
        }

        .conversation_wrapper {
            background-color: #F8F7FA;
        }

        .conversation {
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: 0px 3px 6px #00000029;
            border-radius: 7px;
            opacity: 1;
            border: none;
            padding-left: 15px;
            padding-right: 45px;
            height: 40px;
            width: 100%;
            font-family: nunito-regular;
        }

        .conversation:active,
        .conversation:focus {
            outline: none
        }

        .sendButton {
            width: 25px;
            height: 25px;
            background-color: #105182;
            border-radius: 5px;
            position: absolute;
            right: 15px;
            top: 7px;
        }
    </style>
@endsection
@section('content')
    <!-- Main section -->
    <div class="bids-main-container w-100 h-100 d-flex">
        <div class="connected__user w-20 py-3 h-100">
            <ul id="connected_users" class="m-0 p-0">
                <li class="position-relative chat_search_wrapper">
                    <i class="bi bi-search chat-search"></i>
                    <input type="search" name="search" id="search_recepient" class="w-90" placeholder="Search" value="">
                </li>
                @forelse ($receiversData as $receiver)
                    <li class="my-4">
                        <a href="{{ route('chats.show', ['role' => $role, 'receiver_id' => $receiver->id]) }}"
                            class="connected_user_profile text-dark">
                            <img src="{{ asset($receiver->profile->image ?? 'assets/buyer-assets/user-icon.png') }}" alt=""
                                class="connected_user-img">
                            <p class="connected_username" style="overflow: hidden">{{ strtolower(explode(" ", $receiver->name)[0]) }}</p>
                        </a>
                    </li>
                @empty
                    <!-- ***** when there is no connected user then this will also show ***** -->
                    <li class="d-flex align-items-center justify-content-center mt-4">
                        <img src="{{ asset('assets/buyer-assets/nochat.png') }}" alt="" width="80px"
                            height="80px">
                    </li>
                @endforelse
            </ul>
        </div>
        @if ($recepient != null)
            <div class="single__connected__user w-80 h-100 ">
                <div class="connected_user_info w-100 h-15 bg-white d-flex align-items-center gap-2 ps-3">
                    <img src="{{ asset($recepient->profile->image ?? 'assets/buyer-assets/user-icon.png') }}" alt=""
                        class="connected_user-img_big">
                    <p class="connected_username"><span>Chat with</span><span class="fs-4">
                            {{ strtolower(explode(" ", $receiver->name)[0]) }}</span></p>
                </div>
                <div class="running_chat h-75 w-100 p-3">
                    {{-- if there is no messeges --}}
                    @if ($messages->count() == 0)
                        <p class="text-center connected_username">No Messages</p>
                    @else
                        @foreach ($messages as $message)
                            <div class="">
                                @if ($message->sender_id == $recepient->id)
                                    {{-- recepient messege --}}
                                    <div class="my-3 w-80">
                                        <p class="connected_username">
                                            {{ strtolower(str_replace(' ', '', $recepient->name)) }}</p>
                                        <div class="messege">
                                            {{ $message->message }}
                                            <span class="time">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @elseif ($message->sender_id == auth()->user()->id)
                                    {{-- sender messege --}}
                                    <div class="my-3 w-80 ms-auto">
                                        <p class="connected_username">Me</p>
                                        <div class="messege">
                                            {{ $message->message }}
                                            <span class="time">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                {{-- reply form  --}}
                <div class="w-100 conversation_wrapper h-10">
                    <form id="reply_form"
                        action="{{ route('chats.reply', ['role' => $role, 'receiver_id' => $recepient->id]) }}"
                        class="h-100 w-95 m-auto position-relative" method="post">
                        @csrf
                        <input type="text" class="conversation" id="message_input" name="message">
                        <button type="button" id="send-btn" class="border-0 sendButton shadow-sm">
                            <i class="bi bi-send" style="color: white"></i>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- **************** if there is no chat then this div will be show *************** -->
            <div class="w-80 h-100 d-flex align-items-center justify-content-center" style="background-color:#F8F7FA">
                <img src="{{ asset('assets/buyer-assets/nochat.png') }}" alt="" width="200px" height="200px">
            </div>
        @endif

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#send-btn').on('click', function() {
                var message = $('#message_input').val();
                // validate the message so that it can't be empty and can't be null or undefined or empty string without spaces
                if (message === '' || message === null || message === undefined || message.trim() === '') {
                    toastr.error('Message cannot be empty');
                } else {
                    // send the message by submitting the form
                    $('#reply_form').submit();
                }
            });


            // send the message by pressing enter
            $('#search_recepient').on('keyup', function(e) {
                if (e.keyCode === 13) {
                    var search = $('#search_recepient').val();
                    // validate the search so that it can't be empty and can't be null or undefined or empty string without spaces
                    if (search === '' || search === null || search === undefined || search.trim() === '') {
                        toastr.error('Search cannot be empty');
                        return;
                    } else {
                        // submitting the form
                        // $('#search_form').submit();

                        // use ajax request to perform the search
                        $.ajax({
                            url: "{{ route('chats.search', ['role' => $role]) }}",
                            method: 'POST',
                            data: {
                                search: $('#search_recepient').val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                // handle the response
                                // console.log(response);

                                if (response.search) {
                                    $("#connected_users li:not(:first)").remove();

                                    var users = response.users;
                                    var usersArray = [];
                                    // Check if 'users' is an object and convert it to an array if needed
                                    if (typeof users === 'object' && users !== null) {
                                        // Convert the object to an array
                                        var usersArray = Object.values(users);
                                    } else {
                                        usersArray = users;
                                    }
                                    usersArray.forEach(function(user) {
                                        $("#connected_users").append(
                                            `<li class="my-4">
                                            <a href="{{ url('/${response.role}/messages/${user.id}/show') }}"
                                                class="connected_user_profile text-dark">
                                                <img src="{{ asset('assets/buyer-assets/user-icon.png') }}" alt=""
                                                    class="connected_user-img">
                                                <p class="connected_username">${user.name}</p>
                                            </a>
                                        </li>`
                                        );
                                    });
                                }
                                else {
                                    return;
                                }

                            },
                            error: function(error) {
                                // handle the error
                                console.log(error.responseText);
                            }

                        });
                    }
                }
            });

        });
    </script>
@endsection
