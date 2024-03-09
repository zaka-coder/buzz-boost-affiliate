{{-- <div id="notificationBody" class="rounded-2 py-3" style="display:none;width:100%;">
    <div class="px-2">
        <h4 style="font-size: 21px">Notifications</h4>
        @forelse ($notifications as $notification)
            <div class="notification-item my-2">
                <h4 class="notification-text d-flex align-items-center m-0">
                    {{ $notification->title ?? 'N/A' }}
                </h4>
                <!-- <p class="notification-time m-0">july 23,2023 at 9:15 PM</p> -->
                <p class="notification-time m-0">
                    {{ $notification->created_at->format('F j, Y, g:i a') }}</p>
            </div>
        @empty
            <div class="notification-item my-2">
                <p class="notification-time m-0">No new notifications.</p>
            </div>
        @endforelse
    </div>
</div> --}}
