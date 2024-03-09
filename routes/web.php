<?php

use App\Notifications\WinningAuctionNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*  Authentication Routes... */

Auth::routes();

/*  Email Verification Routes... */
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent! Please check your email.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
/* End Email Verification Routes... */

Route::post('/update/password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'updateForgotPassword'])->name('update.forgot.password');

Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return "Optimized Clear.........!!! ";
});

/*  Guest Routes... */
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/switch/role', [App\Http\Controllers\SwitchRoleController::class, 'index'])->name('switch.role');
Route::get('categories/{category_id}/products', [App\Http\Controllers\Buyer\ProductController::class, 'productsByCategory'])->name('categories.products');
Route::get('products/{id}/show', [App\Http\Controllers\Buyer\ProductController::class, 'show'])->name('products.show');
Route::get('/cart', function () {
    return view('buyer.cart.cart');
})->name('cart');
Route::get('/stores/{search?}', [App\Http\Controllers\StoreController::class, 'index'])->name('stores');
Route::get('/stores/{id}/show', [App\Http\Controllers\StoreController::class, 'show'])->name('stores.show');
Route::get('search/{search?}', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::get('auctions/no-reserve', [App\Http\Controllers\NoReserveItemsController::class, 'index'])->name('auctions.no-reserve');


/*  Common Auth Routes... */
Route::middleware(['auth', 'verified'])->group(function () {
    /*  Profile Routes... */
    Route::get('buyer/profile/create', [App\Http\Controllers\Buyer\ProfileController::class, 'create'])->name('buyer.profile.create');
    Route::post('buyer/profile/store', [App\Http\Controllers\Buyer\ProfileController::class, 'store'])->name('buyer.profile.store');

    /*  Change Password Routes... */
    Route::get('/profile/change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'index'])->name('password.change');
    Route::put('/profile/update-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'update'])->name('password.update');

    /*  Support Routes... */
    Route::get('/{role}/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
    Route::post('/{role}/support/store', [App\Http\Controllers\SupportController::class, 'store'])->name('support.store');
    Route::post('/{role}/support/{parent_id}/reply/store', [App\Http\Controllers\SupportController::class, 'storeReply'])->name('support.reply.store');

    /*  Chats Routes... */
    Route::get('/{role}/messages', [App\Http\Controllers\ChatController::class, 'index'])->name('chats.index');
    Route::get('/{role}/messages/{receiver_id}/show', [App\Http\Controllers\ChatController::class, 'show'])->name('chats.show');
    Route::post('/{role}/messages/store', [App\Http\Controllers\ChatController::class, 'store'])->name('chats.store');
    Route::post('/{role}/messages/{receiver_id}/reply', [App\Http\Controllers\ChatController::class, 'reply'])->name('chats.reply');
    Route::post('/{role}/messages/search', [App\Http\Controllers\ChatController::class, 'search'])->name('chats.search');

    /*  Notification Routes... */
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
});

/*  Buyer Routes... */
Route::prefix("buyer")->as("buyer.")->middleware(['auth', 'verified', 'buyer'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Buyer\DashboardController::class, 'index'])->name('dashboard');
    // /*  Profile Routes... */
    // Route::get('/profile/create', [App\Http\Controllers\Buyer\ProfileController::class, 'create'])->name('profile.create');
    // Route::post('/profile/store', [App\Http\Controllers\Buyer\ProfileController::class, 'store'])->name('profile.store');


    /*  Cart Routes V2... */
    Route::get('cart', [App\Http\Controllers\Buyer\CartController::class, 'index'])->name('cart');
    Route::get('cart/item/{id}/add', [App\Http\Controllers\Buyer\CartController::class, 'store'])->name('cart.item.add');
    Route::get('cart/item/{id}/delete', [App\Http\Controllers\Buyer\CartController::class, 'delete'])->name('cart.item.delete');

    Route::post('checkout/create', [App\Http\Controllers\Buyer\CartController::class, 'create'])->name('checkout.create');
    /*  End Cart Routes V2... */

    /*  Checkout Routes... */
    Route::get('cart/product/{id}/checkout/{order_id?}', [App\Http\Controllers\Buyer\CheckoutController::class, 'checkout'])->name('checkout');

    /*  Shipping Address Routes... */
    Route::get('cart/product/{id}/shipping-address/create', [App\Http\Controllers\Buyer\ShippingAddressController::class, 'create'])->name('shipping.address.create');
    Route::post('cart/product/{id}/shipping-address/store', [App\Http\Controllers\Buyer\ShippingAddressController::class, 'store'])->name('shipping.address.store');

    /*  Bids Routes... */
    Route::get('bids/{status?}', [App\Http\Controllers\Buyer\BidController::class, 'index'])->name('bids.index');
    Route::get('ajax/bids', [App\Http\Controllers\Buyer\BidController::class, 'getAllBids']);
    Route::post('bids/store', [App\Http\Controllers\Buyer\BidController::class, 'store'])->name('bids.store');
    Route::get('item/{id}/bids', [App\Http\Controllers\Buyer\BidController::class, 'itemBids'])->name('bids.item');

    /*  Offers Routes... */
    Route::get('offers/{status?}', [App\Http\Controllers\Buyer\OfferController::class, 'index'])->name('offers.index');
    Route::post('offers/store', [App\Http\Controllers\Buyer\OfferController::class, 'store'])->name('offers.store');
    Route::get('item/{id}/offers', [App\Http\Controllers\Buyer\OfferController::class, 'itemOffers'])->name('offers.item');

    /*  Order Routes... */
    Route::get('orders/{status?}', [App\Http\Controllers\Buyer\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [App\Http\Controllers\Buyer\OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/store', [App\Http\Controllers\Buyer\OrderController::class, 'store'])->name('orders.store');
    Route::get('thanks', [App\Http\Controllers\Buyer\OrderController::class, 'thanks'])->name('orders.thanks');

    /*  Request Audit Routes... */
    Route::get('items/{id}/request/audit', [App\Http\Controllers\Buyer\ProductController::class, 'requestAudit'])->name('items.request.audit');

    /*  Wishlist Routes... */
    Route::get('wishlist', [App\Http\Controllers\Buyer\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist/store', [App\Http\Controllers\Buyer\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('wishlist/{id}/destroy', [App\Http\Controllers\Buyer\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    /*  Feedback Routes... */
    Route::get('feedbacks', [App\Http\Controllers\Buyer\FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('feedbacks/awaiting', [App\Http\Controllers\Buyer\FeedbackController::class, 'awaitingFeedbackProducts'])->name('feedbacks.awaiting');
    Route::post('feedbacks/store', [App\Http\Controllers\Buyer\FeedbackController::class, 'store'])->name('feedbacks.store');

    /*  Settings Routes... */
    Route::get('settings', [App\Http\Controllers\Buyer\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/profile/update', [App\Http\Controllers\Buyer\SettingsController::class, 'updateProfile'])->name('settings.update.profile');
    Route::get('settings/shiping-address', [App\Http\Controllers\Buyer\SettingsController::class, 'shippingAddress'])->name('settings.shipping');
    Route::put('settings/shiping-address/update', [App\Http\Controllers\Buyer\SettingsController::class, 'updateShippingAddress'])->name('settings.update.shipping');
    Route::get('settings/credit-card', [App\Http\Controllers\Buyer\SettingsController::class, 'creditCard'])->name('settings.credit-card');
    Route::post('settings/credit-card/store', [App\Http\Controllers\Buyer\SettingsController::class, 'storeCreditCard'])->name('settings.store.credit-card');
    Route::post('settings/credit-card/delete', [App\Http\Controllers\Buyer\SettingsController::class, 'deleteCreditCard'])->name('settings.delete.credit-card');
    Route::post('settings/credit-card/{id}/set-default', [App\Http\Controllers\Buyer\SettingsController::class, 'setDefaultCreditCard'])->name('settings.default.credit-card');

    /*  Stripe Payments Routes... */
    Route::post('stripe/payment', [App\Http\Controllers\StripePaymentController::class, 'payment'])->name('stripe.payment');

    Route::post('stripe/store', [App\Http\Controllers\StripePaymentController::class, 'store'])->name('stripe.store');
});

/*  Seller Routes... */
Route::prefix("seller")->as("seller.")->middleware(['auth', 'verified', 'seller'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
    /*  Store Routes... */
    Route::get('/store/create', [App\Http\Controllers\Seller\StoreController::class, 'create'])->name('store.create');
    Route::post('/store/store', [App\Http\Controllers\Seller\StoreController::class, 'store'])->name('store.store');
    Route::get('/store/thanks', [App\Http\Controllers\Seller\StoreController::class, 'thanks'])->name('store.thanks');
    Route::get('/store/wait-to-be-approved', [App\Http\Controllers\Seller\StoreController::class, 'waitToBeApproved'])->name('store.wait-to-be-approved');
    Route::get('store/{id}/store-details', [App\Http\Controllers\Seller\StoreController::class, 'edit'])->name('store.edit');
    Route::put('store/{id}/update', [App\Http\Controllers\Seller\StoreController::class, 'update'])->name('store.update');
    Route::post('store/{id}/image/update', [App\Http\Controllers\Seller\StoreController::class, 'updateStoreImage'])->name('store.image.update');

    /*  Store Email Notifications Setup Routes... */
    Route::get('store/{id}/email-notifications', [App\Http\Controllers\Seller\EmailNotificationsSetupController::class, 'edit'])->name('store.edit-email-notifications');
    Route::put('store/{id}/email-notifications', [App\Http\Controllers\Seller\EmailNotificationsSetupController::class, 'update'])->name('store.update-email-notifications');

    /*  Store Discounts Routes... */
    // Route::get('store/{id}/discounts', [App\Http\Controllers\Seller\DiscountController::class, 'edit'])->name('store.edit-discounts');
    // Route::put('store/{id}/discounts', [App\Http\Controllers\Seller\DiscountController::class, 'update'])->name('store.update-discounts');

    /*  Store Plans Routes... */
    Route::get('store/{id}/compare-plans', [App\Http\Controllers\Seller\StoreController::class, 'comparePlans'])->name('store.compare-plans');
    Route::post('store/{id}/select-plan', [App\Http\Controllers\Seller\StorePlanController::class, 'update'])->name('store.plans.update');

    /*  Store Payment Methods Routes... */
    Route::get('store/{id}/payment-details', [App\Http\Controllers\Seller\StorePaymentMethodController::class, 'edit'])->name('store.edit-payment');
    Route::put('store/{id}/payment-details', [App\Http\Controllers\Seller\StorePaymentMethodController::class, 'update'])->name('store.update-payment');
    Route::post('store/{id}/payment-details/reset', [App\Http\Controllers\Seller\StorePaymentMethodController::class, 'reset'])->name('store.reset-payment-method');


    /*  Shipping Preference Routes... */
    Route::get('/shipping-preference/create', [App\Http\Controllers\Seller\ShippingPreferenceController::class, 'create'])->name('shipping-preference.create');
    Route::post('/shipping-preference/store', [App\Http\Controllers\Seller\ShippingPreferenceController::class, 'store'])->name('shipping-preference.store');
    Route::get('store/{id}/shipping-details', [App\Http\Controllers\Seller\ShippingPreferenceController::class, 'edit'])->name('store.edit-shipping');
    Route::put('store/{id}/shipping-details', [App\Http\Controllers\Seller\ShippingPreferenceController::class, 'update'])->name('store.update-shipping');

    /*  Payment Method Routes... */
    Route::get('/payment-method/create', [App\Http\Controllers\Seller\PaymentMethodController::class, 'create'])->name('payment-method.create');
    Route::post('/payment-method/store', [App\Http\Controllers\Seller\PaymentMethodController::class, 'store'])->name('payment-method.store');

    /*  Products Routes... */
    Route::resource('items', App\Http\Controllers\Seller\ProductController::class);
    Route::get('items/{type}/create', [App\Http\Controllers\Seller\ProductController::class, 'createProduct'])->name('items.create.type');

    /*  Sales Routes... */
    Route::get('sales/{status?}', [App\Http\Controllers\Seller\SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/{id}/update/status/{status}', [App\Http\Controllers\Seller\SaleController::class, 'updateStatus'])->name('sales.update.status');
    Route::get('sales/{id}/customer-profile', [App\Http\Controllers\Seller\SaleController::class, 'customerProfile'])->name('sales.customer-profile');
    Route::post('sales/{id}/update/status/shipped', [App\Http\Controllers\Seller\SaleController::class, 'updateStatusShipped'])->name('sales.update.shipped');
    Route::get('sales/{id}/summary', [App\Http\Controllers\Seller\SaleController::class, 'summary'])->name('sales.summary');

    /*  Offers Routes... */
    Route::get('offers/{status?}', [App\Http\Controllers\Seller\OfferController::class, 'index'])->name('offers.index');
    Route::post('offers/{id}/update', [App\Http\Controllers\Seller\OfferController::class, 'update'])->name('offers.update');

    /*  Feedback Routes... */
    Route::get('feedbacks', [App\Http\Controllers\Seller\FeedbackController::class, 'index'])->name('feedbacks.index');

    /*  Settings Routes... */
    Route::get('settings', [App\Http\Controllers\Seller\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/profile/update', [App\Http\Controllers\Seller\SettingsController::class, 'updateProfile'])->name('settings.update.profile');

    /*  Block Users Routes... */
    Route::get('users/blocked', [App\Http\Controllers\Seller\BlockUserController::class, 'index'])->name('users.blocked.index');
    Route::post('users/{id}/block', [App\Http\Controllers\Seller\BlockUserController::class, 'block'])->name('users.block');
    Route::post('users/{id}/unblock', [App\Http\Controllers\Seller\BlockUserController::class, 'unblock'])->name('users.unblock');
});

/*  Admin Routes... */
Route::prefix("admin")->as("admin.")->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    /*  Stores Routes... */
    Route::get('/stores', [App\Http\Controllers\Admin\StoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/{id}/show', [App\Http\Controllers\Admin\StoreController::class, 'show'])->name('stores.show');
    Route::get('/stores/{id}/approve', [App\Http\Controllers\Admin\StoreController::class, 'approve'])->name('stores.approve');
    Route::post('stores/filter-by-status', [App\Http\Controllers\Admin\StoreController::class, 'filterByStatus'])->name('stores.filter.status');

    /*  Shipping Providers Routes... */
    Route::resource('shipping-providers', App\Http\Controllers\Admin\ShippingProviderController::class);
    /*  Categories Routes... */
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    /*  Audits Routes... */
    Route::get('audits', [App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audits.index');
    Route::get('audits/{id}/response', [App\Http\Controllers\Admin\AuditController::class, 'response'])->name('audits.response');
    Route::put('audits/{id}/response/store', [App\Http\Controllers\Admin\AuditController::class, 'storeResponse'])->name('audits.response.store');
    Route::get('audits/{id}/user', [App\Http\Controllers\Admin\AuditController::class, 'showUser'])->name('audits.user');
    Route::get('audits/{id}/item', [App\Http\Controllers\Admin\AuditController::class, 'showProduct'])->name('audits.product');
    Route::post('audits/filter-by-status', [App\Http\Controllers\Admin\AuditController::class, 'filterByStatus'])->name('audits.filter.status');

    /*  Sales Routes... */
    Route::get('sales', [App\Http\Controllers\Admin\SaleController::class, 'index'])->name('sales.index');

    /*  Sales Filter Routes... */
    Route::post('sales/filter-by-id', [App\Http\Controllers\Admin\SaleController::class, 'filterByItemId'])->name('sales.filter.id');
    Route::post('sales/filter-by-item-name', [App\Http\Controllers\Admin\SaleController::class, 'filterByItemName'])->name('sales.filter.item');
    Route::post('sales/filter-by-store-name', [App\Http\Controllers\Admin\SaleController::class, 'filterByStoreName'])->name('sales.filter.store');
    Route::post('sales/filter-by-customer-name', [App\Http\Controllers\Admin\SaleController::class, 'filterByCustomerName'])->name('sales.filter.customer');
    Route::post('sales/filter-by-date', [App\Http\Controllers\Admin\SaleController::class, 'filterByDate'])->name('sales.filter.date');
    Route::post('sales/filter-by-status', [App\Http\Controllers\Admin\SaleController::class, 'filterByStatus'])->name('sales.filter.status');

    /*  Products Routes... */
    Route::get('items', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('items/{id}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    Route::post('items/{id}/update/status', [App\Http\Controllers\Admin\ProductController::class, 'updateStatus'])->name('products.update.status');

    /*  Products Filter Routes... */
    Route::post('items/filter-by-id', [App\Http\Controllers\Admin\ProductController::class, 'filterByItemId'])->name('items.filter.id');
    Route::post('items/filter-by-item-name', [App\Http\Controllers\Admin\ProductController::class, 'filterByItemName'])->name('items.filter.item');
    Route::post('items/filter-by-store-name', [App\Http\Controllers\Admin\ProductController::class, 'filterByStoreName'])->name('items.filter.store');
    Route::post('items/filter-by-customer-name', [App\Http\Controllers\Admin\ProductController::class, 'filterByCustomerName'])->name('items.filter.customer');
    Route::post('items/filter-by-date', [App\Http\Controllers\Admin\ProductController::class, 'filterByDate'])->name('items.filter.date');
    Route::post('items/filter-by-status', [App\Http\Controllers\Admin\ProductController::class, 'filterByStatus'])->name('items.filter.status');
    Route::post('items/filter-by-saleType', [App\Http\Controllers\Admin\ProductController::class, 'filterBySaleType'])->name('items.filter.saleType');

    /*  Support tickets Routes... */
    Route::get('/support-tickets', [App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
    Route::post('/support/{parent_id}/reply', [App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('support.reply');
    Route::delete('/support/{id}/destroy', [App\Http\Controllers\Admin\SupportController::class, 'destroy'])->name('support.destroy');

    Route::post('support-tickets/filter-by-date', [App\Http\Controllers\Admin\SupportController::class, 'filterByDate'])->name('support.filter.date');
    Route::post('support-tickets/filter-by-status', [App\Http\Controllers\Admin\SupportController::class, 'filterByStatus'])->name('support.filter.status');

    /*  Transactions Routes... */
    Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');

    /*  Users Routes... */
    Route::get('users', [App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
    Route::get('users/{id}/show', [App\Http\Controllers\Admin\UsersController::class, 'show'])->name('users.show');
});


Route::get('/category', function () {
    return view('guest.categories.category');
});
Route::get('/categories', function () {
    return view('guest.categories.index');
});

Route::get('/coming-soon', function () {
    return view('guest.coming-soon.index');
});
Route::get('/forget-password', function () {
    return view('auth.new-pages.forget');
});

Route::get('/test', function () {
    print('testing route...!!! ');
});
