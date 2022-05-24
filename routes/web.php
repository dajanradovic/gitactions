<?php

use App\Models\Order;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DbController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BannerController;

use App\Http\Controllers\FilterController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\CheeseClubController;
use App\Http\Controllers\SmsMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DeliveryPriceController;
use App\Http\Controllers\UptimeMonitorController;
use App\Http\Controllers\Settings\GeneralController;
use App\Http\Controllers\IncomingSmsMessageController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// FORTIFY ROUTES
if (config('fortify.views')) {
	Route::middleware('auth')->get('email/verify', EmailVerificationPromptController::class)->name('verification.notice');
}

// LOGIN ROUTES
Route::controller(AuthController::class)->prefix('login')->name('login.')->group(function (Router $route): void {
	// GET
	$route->middleware('signed')->get('silent/{user}', 'silent')->name('silent');

	// OAUTH
	$route->middleware('guest')->name('oauth.')->group(function (Router $route): void {
		// GET
		$route->get('{driver}/callback', 'oAuthCallback')->name('callback');
		$route->get('{driver}', 'oAuthRedirect')->name('redirect');
	});
});

// PAYPAL ROUTES
Route::controller(PayPalController::class)->prefix('orders')->name('orders.')->group(function (Router $route): void {
	$route->get('{id}/pay', 'pay')->name('pay');
});

// PROTECTED ROUTES
Route::middleware(['auth', 'verified', 'check_role_permissions', 'change_locale'])->group(function (Router $route): void {
	Route::get('/testform', function () {
		return view('corvus.test-form');
	});

	// MISC ROUTES
	$route->controller(MiscController::class)->group(function (Router $route): void {
		// GET
		$route->get('', 'dashboard')->name('home');
		$route->get('dashboard', 'dashboard')->name('dashboard');
		$route->get('tech-info', 'techinfo')->name('tech-info');
		$route->get('telescope-auth', 'telescope')->name('telescope-auth');
		$route->get('horizon-auth', 'horizon')->name('horizon-auth');
		$route->get('locale', 'changeLocale')->name('change-locale');

		// POST
		$route->post('feedback', 'feedback')->name('feedback');
		$route->post('invalidate-sessions', 'invalidateSessions')->name('invalidate-sessions');
	});

	// SETTINGS ROUTES
	$route->controller(GeneralController::class)->prefix('settings')->name('settings.')->group(function (Router $route): void {

		// GENERAL
		$route->prefix('general')->name('general.')->group(function (Router $route): void {
			// GET
			$route->get('', 'edit')->name('edit');

			// POST
			$route->post('', 'update')->name('update');
		});
	});

	// STORAGE ROUTES
	$route->controller(StorageController::class)->prefix('media-storage')->name('storage.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
	});

	// DB TABLE ROUTES
	$route->controller(DbController::class)->prefix('db')->name('db.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('{table}', 'show')->name('show');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('truncate', 'multiTruncate')->name('truncate-multi');
		$route->delete('{table}/columns', 'multiRemoveColumns')->name('remove-columns-multi');
	});

	// MONITOR ROUTES
	$route->controller(UptimeMonitorController::class)->prefix('monitors')->name('monitors.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('activate', 'multiActivate')->name('activate');
		$route->post('deactivate', 'multiDeactivate')->name('deactivate');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// BLOG ROUTES
	$route->controller(BlogController::class)->prefix('blogs')->name('blogs.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('search', 'search')->name('search');
		$route->get('add', 'create')->name('add');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// USER ROUTES
	$route->controller(UserController::class)->prefix('users')->name('users.')->group(function (Router $route): void {
		// GET
		$route->get('profile', 'profile')->name('profile');
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('{id}/sessions', 'sessions')->name('sessions');
		$route->get('{id}/2fa', 'toggle2FAForm')->name('2fa');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('activate', 'multiActivate')->name('activate');
		$route->post('deactivate', 'multiDeactivate')->name('deactivate');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('sessions', 'removeSessions')->name('remove-sessions');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// CUSTOMER ROUTES
	$route->controller(CustomerController::class)->prefix('customers')->name('customers.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// CHEESE CLUB ROUTES
	$route->controller(CheeseClubController::class)->prefix('cheese-club')->name('cheese-club.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('import', 'createImport')->name('add-import');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('import', 'import')->name('import');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// REVIEW ROUTES
	$route->controller(ReviewController::class)->prefix('reviews')->name('reviews.')->group(function (Router $route): void {

		// GET
		$route->get('', 'index')->name('list');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');

		$route->post('activate', 'multiActivate')->name('activate');
		$route->post('deactivate', 'multiDeactivate')->name('deactivate');
	});

	// NOTIFICATION ROUTES
	$route->controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('{id}', 'show')->name('show');

		// POST
		$route->post('', 'store')->name('store');

		// DELETE
		$route->delete('cancel', 'multiCancel')->name('cancel-multi');
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}/cancel', 'cancel')->name('cancel');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// SMS MESSAGE ROUTES
	$route->prefix('sms-messages')->name('sms-messages.')->group(function (Router $route): void {

		// INCOMING ROUTES
		$route->controller(IncomingSmsMessageController::class)->prefix('incoming')->name('incoming.')->group(function (Router $route): void {
			// GET
			$route->get('', 'index')->name('list');
			$route->get('search', 'search')->name('search');
			$route->get('{id}', 'show')->name('show');

			// DELETE
			$route->delete('', 'multiRemove')->name('remove-multi');
			$route->delete('{id}', 'destroy')->name('remove');
		});

		$route->controller(SmsMessageController::class)->group(function (Router $route): void {
			// GET
			$route->get('', 'index')->name('list');
			$route->get('search', 'search')->name('search');
			$route->get('add', 'create')->name('add');
			$route->get('{id}', 'show')->name('show');

			// POST
			$route->post('', 'store')->name('store');

			// DELETE
			$route->delete('', 'multiRemove')->name('remove-multi');
			$route->delete('{id}', 'destroy')->name('remove');
		});
	});

	// ROLE ROUTES
	$route->controller(RoleController::class)->prefix('roles')->name('roles.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('users', 'storeUsers')->name('store-users');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// CATEGORY ROUTES
	$route->controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function ($route): void {

		// GET
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('activate', 'multiActivate')->name('activate');
		$route->post('deactivate', 'multiDeactivate')->name('deactivate');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// PRODUCT ROUTES
	$route->controller(ProductController::class)->prefix('products')->name('products.')->group(function ($route): void {

		// GET
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('search', 'search')->name('search');
		$route->get('import', 'createImport')->name('add-import');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('import', 'import')->name('import');
		$route->post('activate', 'multiActivate')->name('activate');
		$route->post('deactivate', 'multiDeactivate')->name('deactivate');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// BANNER ROUTES
	$route->controller(BannerController::class)->prefix('banners')->name('banners.')->group(function ($route): void {

		// GET
		$route->get('', 'index')->name('list');
		$route->get('add', 'create')->name('add');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// DELIVERY PRICES ROUTES
	$route->controller(DeliveryPriceController::class)->prefix('delivery-prices')->name('delivery-prices.')->group(function ($route): void {

		// GET
		$route->get('', 'index')->name('list');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// FILTER ROUTES
	$route->controller(FilterController::class)->prefix('filters')->name('filters.')->group(function (Router $route): void {

		// GET
		$route->get('', 'index')->name('list');
		// $route->get('search', 'search')->name('search');
		$route->get('add', 'create')->name('add');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('activate', 'multiActivate')->name('activate');
		$route->post('deactivate', 'multiDeactivate')->name('deactivate');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// DISCOUNT ROUTES
	$route->controller(DiscountController::class)->prefix('discounts')->name('discounts.')->group(function (Router $route): void {

		// GET
		$route->get('', 'index')->name('list');
		// $route->get('search', 'search')->name('search');
		$route->get('add', 'create')->name('add');
		$route->get('add-coupons', 'createCoupons')->name('add-coupons');
		$route->get('{id}/coupons', 'editCoupons')->name('edit-coupons');
		$route->get('{id}/above-set-price', 'editAboveSetPriceDiscount')->name('edit-above-set-price-discount');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('', 'store')->name('store');
		$route->post('store-coupons', 'storeCoupons')->name('store-coupons');
		$route->post('activate', 'multiActivate')->name('activate');
		$route->post('deactivate', 'multiDeactivate')->name('deactivate');
		$route->post('{id}/update-coupons', 'updateCoupons')->name('update-coupons');
		$route->post('{id}/update-above-set-price-discount', 'updateAboveSetPriceDiscount')->name('update-above-set-price-discount');
		$route->post('{id}', 'update')->name('update');

		// DELETE
		$route->delete('', 'multiRemove')->name('remove-multi');
		$route->delete('{id}', 'destroy')->name('remove');
	});

	// ORDER ROUTES
	$route->controller(OrderController::class)->prefix('orders')->name('orders.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('{id}', 'edit')->name('edit');

		// POST
		$route->post('{id}', 'update')->name('update');
	});
});
