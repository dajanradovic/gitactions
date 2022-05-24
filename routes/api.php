<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\CorvusController;
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\CheeseClubController;
use App\Http\Controllers\Api\PushDeviceController;
use App\Http\Controllers\Api\SmsWebhookController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// FORTIFY ROUTES
Route::middleware('throttle:' . config('fortify.limiters.verification'))->controller(AuthController::class)->prefix('auth/email')->name('verification.')->group(function (Router $route): void {
	// GET
	$route->middleware('signed')->get('verify/{id}/{hash}', 'verifyEmail')->name('verify');

	// POST
	$route->middleware('auth')->post('verification-notification', 'resendEmailVerificationNotification')->name('send');
});

Route::name('api.')->group(function (Router $route): void {

	// CONFIG ROUTE
	$route->get('config', [ConfigController::class, 'config'])->name('config');

	// AUTH ROUTES
	$route->controller(AuthController::class)->prefix('auth')->name('auth.')->group(function (Router $route): void {

		// REGISTER ROUTES
		$route->prefix('register')->name('register.')->group(function (Router $route): void {
			// POST
			$route->post('', 'register')->name('standard');
		});

		// PASSWORD ROUTES
		$route->prefix('password')->name('password.')->group(function (Router $route): void {
			// POST
			$route->post('request-reset', 'requestPasswordReset')->name('request-reset');
			$route->post('reset', 'passwordReset')->name('reset');
		});

		// LOGIN ROUTES
		$route->prefix('login')->name('login.')->group(function (Router $route): void {
			// POST
			$route->post('', 'login')->name('standard');
			$route->post('{driver}', 'oauthLogin')->name('oauth');
		});
	});

	// BLOG ROUTES
	$route->controller(BlogController::class)->prefix('blogs')->name('blogs.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('{id}/activities', 'activities')->name('activities');
		$route->get('{id}', 'single')->name('single');
	});

	// CATEGORIES ROUTES
	$route->controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function ($route) {
		// GET
		$route->get('', 'index')->name('list');
		$route->get('filters', 'filters')->name('filters');
		$route->get('{category}', 'single')->name('single');
	});

	// PRODUCT ROUTES
	$route->controller(ProductController::class)->prefix('products')->name('products.')->group(function ($route) {

		// GET
		$route->get('', 'index')->name('list');
		$route->get('filters', 'filters')->name('filters');
		$route->get('{id}/upsell', 'upsell')->name('upsell');
		$route->get('{id}/reviews', 'getReviews')->name('get-reviews');
		$route->get('{product}', 'single')->name('single');

		// POST
		$route->post('{id}/inquiry', 'inquiry')->name('inquiry');
	});

	// TAG ROUTES
	$route->controller(TagController::class)->prefix('tags')->name('tags.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
	});

	// BANNER ROUTES
	$route->controller(BannerController::class)->prefix('banners')->name('banners.')->group(function (Router $route): void {
		// GET
		$route->get('', 'index')->name('list');
	});

	// ORDER ROUTES
	$route->controller(OrderController::class)->prefix('orders')->name('orders.')->group(function (Router $route): void {

		// POST
		$route->post('guest-calculate', 'guestCalculate')->name('guest-calculate');
		$route->post('calculate', 'calculate')->name('calculate');
		$route->post('', 'create')->name('create');
	});

	// REVIEW ROUTES
	$route->controller(ReviewController::class)->prefix('reviews')->name('reviews.')->group(function (Router $route): void {

		// POST
		$route->get('', 'index')->name('reviews');
	});

	// MEDIA ROUTES
	$route->middleware('signed')->controller(MediaController::class)->prefix('media')->name('media.')->group(function (Router $route): void {
		// GET
		$route->get('{id}', 'getMediaUrl')->name('url');
	});

	// WEBHOOK ROUTES
	$route->prefix('webhooks')->name('webhooks.')->group(function (Router $route): void {
		$route->controller(CorvusController::class)->prefix('corvus')->name('corvus.')->group(function ($route) {
			// POST
			$route->post('success', 'success')->name('success');
			$route->post('cancel', 'cancel')->name('cancel');
		});

		$route->controller(PayPalController::class)->prefix('paypal')->name('paypal.')->group(function ($route) {
			$route->get('{id}/verify', 'verify')->name('verify');
		});

		// SMS
		$route->controller(SmsWebhookController::class)->prefix('sms')->name('sms.')->group(function (Router $route): void {
			// VONAGE
			$route->prefix('vonage')->name('vonage.')->group(function (Router $route): void {
				$route->match(['get', 'post'], 'incoming', 'vonageIncoming')->name('incoming');
				$route->match(['get', 'post'], '{id}', 'vonageReport')->name('report');
			});

			// TWILIO
			$route->prefix('twilio')->name('twilio.')->group(function (Router $route): void {
				$route->match(['get', 'post'], 'incoming', 'twilioIncoming')->name('incoming');
				$route->post('{id}', 'twilioReport')->name('report');
			});

			// INFOBIP
			$route->prefix('infobip')->name('infobip.')->group(function (Router $route): void {
				$route->post('incoming', 'infobipIncoming')->name('incoming');
				$route->post('', 'infobipReport')->name('report');
			});

			// NTH
			$route->prefix('nth')->name('nth.')->group(function (Router $route): void {
				$route->post('incoming', 'nthIncoming')->name('incoming');
				$route->post('{id}', 'nthReport')->name('report');
			});

			// 46elks
			$route->prefix('elks')->name('elks.')->group(function (Router $route): void {
				$route->post('incoming', 'elksIncoming')->name('incoming');
				$route->post('{id}', 'elksReport')->name('report');
			});
		});
	});

	// JWT PROTECTED ROUTES
	$route->middleware(['auth', 'verified', 'check_role_permissions'])->group(function (Router $route): void {

		// PROFILE ROUTES
		$route->controller(ProfileController::class)->prefix('me')->name('me.')->group(function (Router $route): void {
			// GET
			$route->get('', 'me')->name('get');
			$route->get('sessions', 'sessions')->name('sessions');
			$route->get('wishlist', 'wishlist')->name('wishlist');
			$route->get('activities', 'activities')->name('activities');
			$route->get('addresses', 'addresses')->name('addresses');
			$route->get('referrals', 'referrals')->name('referrals');
			$route->get('orders', 'orders')->name('orders');

			// POST
			$route->post('', 'update')->name('update');
			$route->post('password', 'updatePassword')->name('update-password');
			$route->post('avatar', 'updateAvatar')->name('update-avatar');
			$route->post('logout', 'logout')->name('logout');
			$route->post('create-address', 'createAddress')->name('create-address');

			// DELETE
			$route->delete('', 'remove')->name('remove');
			$route->delete('sessions/{id}', 'removeSession')->name('remove-session');

			// OAUTH ROUTES
			$route->prefix('oauth')->name('oauth.')->group(function (Router $route): void {
				// POST
				$route->post('{driver}', 'connectOAuth')->name('connect');

				// DELETE
				$route->delete('{driver}', 'disconnectOAuth')->name('disconnect');
			});
		});

		// LOCATION ROUTES
		$route->controller(LocationController::class)->prefix('location')->name('location.')->group(function (Router $route): void {
			// GET
			$route->get('countries', 'countries')->name('countries');
			$route->get('address', 'address')->name('address');
			$route->get('lat-lng', 'latLng')->name('lat-lng');
		});

		// NOTIFICATION ROUTES
		$route->controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function (Router $route): void {
			// GET
			$route->get('', 'index')->name('list');
			$route->get('{id}', 'single')->name('single');

			// POST
			$route->post('seen', 'seenAll')->name('seen-all');

			// DELETE
			$route->delete('{id}', 'remove')->name('remove');
		});

		// USER DEVICES ROUTES
		$route->controller(PushDeviceController::class)->prefix('devices')->name('devices.')->group(function (Router $route): void {
			// GET
			$route->get('', 'index')->name('list');

			// POST
			$route->post('', 'store')->name('store');

			// DELETE
			$route->delete('', 'removeAll')->name('remove-all');
			$route->delete('{id:device_id}', 'remove')->name('remove');
		});

		// CHART ROUTES
		$route->controller(ChartController::class)->prefix('charts')->name('charts.')->group(function (Router $route): void {
			// POST
			$route->post('notifications', 'notifications')->name('notifications');
			$route->post('devices', 'devices')->name('devices');
		});

		// PRODUCT ROUTES
		$route->controller(ProductController::class)->prefix('products')->name('products.')->group(function ($route) {

			// POST
			$route->post('{id}/review', 'review')->name('review');
			$route->post('{id}/like', 'like')->name('like');
		});

		// CHEESE CLUB ROUTES
		$route->controller(CheeseClubController::class)->prefix('cheese-club')->name('cheese-club.')->group(function ($route) {
			// GET
			$route->get('', 'index')->name('list');
			$route->get('{id}', 'single')->name('single');
		});

		// REVIEW ROUTES
		$route->controller(ReviewController::class)->prefix('reviews')->name('reviews.')->group(function (Router $route): void {

			// POST
			$route->post('', 'review')->name('store');
		});

		// REFERRAL ROUTES
		$route->controller(ReferralController::class)->prefix('referrals')->name('referrals.')->group(function (Router $route): void {
			// POST
			$route->post('', 'create')->name('create');
		});
	});
});
