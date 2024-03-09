@extends('layouts.admin')
@section('css')
    <style>
        th {
            font-family: nunito;
        }

        td {
            font-family: nunito-regular;
        }
    </style>
@endsection
@section('content')
    <div class="py-2 px-4 text-white" style="background: #105082">
        <h4 class="m-0" style="margin-bottom: 3px !important">Support Tickets</h4>
    </div>
    <div class="row my-4 mx-3">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="d-flex justify-content-end">
                <div class="col-md-2 pe-1">
                    <input type="text" id="searchByDate" class="form-control" placeholder="Filter by date..." readonly
                        onclick="openDatePickerModal();">
                </div>
                <div class="col-md-2">
                    @php
                        $selectedStatus = $status ?? '';
                    @endphp
                    <form id="status_form" action="{{ route('admin.support.filter.status') }}" method="POST">
                        @csrf
                        <select name="status" id="statusSelect" class="form-control">
                            <option value="">Filter by status...</option>
                            <option value="Resolved" {{ $selectedStatus == 'Resolved' ? 'selected' : '' }}>Resolved
                            </option>
                            <option value="Pending" {{ $selectedStatus == 'Pending' ? 'selected' : '' }}>
                                Pending</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="text-start h-80 m-3">
        <div class="table-responsive h-100">
            <table id="table" class="table table-bordered table-striped" border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Question</th>
                        <th>Submitted At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($support_tickets as $ticket)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $ticket->user->name ?? 'None' }}</td>
                            <td>{{ $ticket->message ?? 'None' }}</td>
                            <td>{{ $ticket->created_at->format('d M, Y') }}</td>
                            <td>{{ $ticket->status }}</td>
                            <td class="d-flex align-items-center justify-content-center gap-2">
                                <button type="button" title="Delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $ticket->id }}"
                                    class="btn btn-outline-danger btn-sm rounded-circle">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button type="button" title="Reply" data-bs-toggle="modal"
                                    data-bs-target="#supportTicketView{{ $ticket->id }}"
                                    class="btn btn-outline-primary btn-sm rounded-circle">
                                    <i class="bi bi-reply"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('overlay')
    @foreach ($support_tickets as $support)
        {{-- Reply modal --}}
        <div class="modal fade" id="supportTicketView{{ $support->id }}" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 99999">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content p-3  position-relative">
                    <div class="modal-header w-100 position-relative" style="border: 0;--bs-modal-header-padding:0">
                        <div class="w-95  m-auto">
                            <h3>Category : {{ $support->category }}</h3>
                            <p>Ticket #{{ $support->id }} - {{ $support->user->name }}</p>
                        </div>
                        <button type="button" class="btn-close position-absolute top-0" data-bs-dismiss="modal"
                            aria-label="Close" style="right: 0!important;margin:0"></button>
                    </div>
                    <div class="modal-body p-0 m-0 w-100" style="border: none">
                        <hr>
                        <div class="w-95  m-auto">
                            <legend class="bg-light fs-6 fw-bold p-2">
                                {{ $support->user->name . ' - ' . $support->created_at->diffForHumans() }}</legend>
                            <p class="p-2 fs-6">{{ $support->message }}</p>
                        </div>
                        @foreach ($support->replies as $reply)
                            <div class="w-95 m-auto">
                                <div class="w-100 d-flex bg-light">
                                    <div class="w-75 d-flex align-items-center justify-content-center">
                                        <legend class="bg-light fs-6 fw-bold p-2 m-0">{{ $reply->user->name . ' - ' . $reply->created_at->diffForHumans() }}
                                        </legend>
                                    </div>
                                    <div class="w-25 text-end d-flex align-items-center justify-content-center" style="">
                                        @if ($reply->attachment)
                                            <a href="{{ asset($reply->attachment) }}" target="_blank" >View Attachment</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="w-100">
                                    <p class="p-2 fs-6">{{ $reply->message ?? '(empty)' }}</p>
                                </div>
                            </div>
                        @endforeach
                        <form action="{{ route('admin.support.reply', ['parent_id' => $support->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="w-95  m-auto">
                                <input type="checkbox" name="status" id="status" class="" value="Resolved"
                                    @if ($support->status == 'Resolved') checked @endif>
                                <label for="status">Mark as Resolved</label>
                            </div>
                            <div class="w-95  m-auto">
                                <legend>Comment</legend>
                                <textarea name="comment" id="" cols="5" rows="5" class="w-100 px-2 py-1"
                                    placeholder="Write something nice..." required></textarea>
                            </div>
                            <div class="w-95  m-auto">
                                <legend>Attachment</legend>
                                <input type="file" name="attachment"  id="" accept="image/*" class="form-control w-100 p-1 m-0">
                            </div>
                            <div class="w-100 my-3">
                                <button type="submit" class="px-3 m-auto d-block" style="width:200px">Reply</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- delete modal --}}
        <div class="modal fade" id="deleteModal{{ $support->id }}" tabindex="-1"
            aria-labelledby="deleteModal{{ $support->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal{{ $support->id }}Label">Delete Category
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this ticket?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('admin.support.destroy', $support->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Date Picker Modal --}}
    <div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <form action="{{ route('admin.support.filter.date') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="datePickerModalLabel">Select Date
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row gap-2">
                            <div class="col">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" id="startDate" name="startDate" class="form-control">
                            </div>
                            <div class="col">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" id="endDate" name="endDate" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // open date picker modal
        function openDatePickerModal() {
            $('#datePickerModal').modal('show');
        }

        $(document).ready(function() {
            $('#table').DataTable({
                //
            });

            $('#statusSelect').on('change', function() {
                // check if the status is selected
                if ($(this).val() !== '') {
                    $('#status_form').submit();
                }
            })
        });
    </script>
@endsection
