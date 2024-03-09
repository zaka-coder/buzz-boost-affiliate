<div class="w-100 h-auto h-md-15">
    <!--different anchor buttons for different pages directing -->
    <div class="w-100 h-auto h-md-100 d-flex flex-md-row flex-column align-items-md-start align-items-center gap-2">
        <a href="{{ route('seller.store.edit', $store->id) }}"
            class="anchor-button rounded-2 @if (Route::currentRouteName() == 'seller.store.edit') active @endif">Store Details</a>
        <a href="{{ route('seller.store.edit-payment', $store->id) }}"
            class="anchor-button rounded-2 @if (Route::currentRouteName() == 'seller.store.edit-payment') active @endif">Payment Details</a>
        <a href="{{ route('seller.store.edit-shipping', $store->id) }}"
            class="anchor-button rounded-2 @if (Route::currentRouteName() == 'seller.store.edit-shipping') active @endif">Shipping Details</a>
        <a href="{{ route('seller.store.compare-plans', $store->id) }}"
            class="anchor-button rounded-2 @if (Route::currentRouteName() == 'seller.store.compare-plans') active @endif">Plan Details</a>
        <a href="{{ route('seller.store.edit-email-notifications', $store->id) }}"
            class="anchor-button rounded-2 @if (Route::currentRouteName() == 'seller.store.edit-email-notifications') active @endif">Email Notifications</a>
        <a href="{{ route('seller.items.index') }}" class="anchor-button rounded-2"
            style="width: 80px !important; background-color: rgba(168,181,190,0.4); color: back">Back</a>
    </div>
</div>
