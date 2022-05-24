<?php

namespace App\Models;

use App\Traits\HasStorage;
use App\Contracts\HasMedia;
use App\Observers\OrderObserver;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\Orders\InvoicesAndOffers\GeneralInvoiceMaker;

/**
 * @property array|null $corvus
 * @property array|null $paypal
 * @property string $final_price
 * @property int $delivery_method
 */
class Order extends Model implements HasMedia
{
	use HasStorage, Notifiable, Prunable;

	public const DELIVERY_SHIPPING = 1;
	public const DELIVERY_PICKUP_IN_STORE = 2;

	public const PAYMENT_METHOD_CARD = 1;
	public const PAYMENT_METHOD_POUZECE = 2;
	public const PAYMENT_METHOD_VIRMAN = 3;

	public const STATUS_PENDING = 1;
	public const STATUS_PAID = 2;
	public const STATUS_CANCELED = 3;
	public const STATUS_REFUNDED = 4;
	public const STATUS_OFFER_SENT = 5;

	public const PAYMENT_PROVIDER_PAYPAL = 1;
	public const PAYMENT_PROVIDER_CORVUS = 2;

	protected $casts = [
		'number_of_packages' => 'integer',
		'payment_type' => 'integer',
		'status' => 'integer',
		'ready_for_pickup' => 'boolean',
		'order_details' => 'array',
		'payment_card_provider' => 'integer',
		'delivery_address' => 'array',
		'invoice_address' => 'array',
		'guest_mode' => 'boolean'
	];

	public function scopeIncludes(object $query, ?string $lang = null): Builder
	{
		return $query->with('orderItems', 'shipments');
	}

	public static function deliveryOptions(): array
	{
		return [
			self::DELIVERY_SHIPPING,
			self::DELIVERY_PICKUP_IN_STORE
		];
	}

	public static function availablePaymentMethods(): array
	{
		return [
			self::PAYMENT_METHOD_CARD,
			self::PAYMENT_METHOD_POUZECE,
			self::PAYMENT_METHOD_VIRMAN
		];
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where('status', self::STATUS_PAID)->orWhere('status', self::STATUS_OFFER_SENT);
	}

	public function scopeCmsAvailable(Builder $query): Builder
	{
		return $query->where('status', '<>', self::STATUS_PENDING);
	}

	public function orderItems(): HasMany
	{
		return $this->hasMany(OrderItem::class);
	}

	public function getPaymentMethodName(): ?string
	{
		switch ($this->payment_type) {
			case self::PAYMENT_METHOD_CARD: return 'CARD';

			case self::PAYMENT_METHOD_POUZECE: return 'POUZECE';

			case self::PAYMENT_METHOD_VIRMAN: return 'VIRMAN';
		}

		return null;
	}

	public function getDeliveryMethodName(): string
	{
		return $this->store_id ? 'PICKUP' : 'SHIPPING';
	}

	public function getStatusName(): ?string
	{
		switch ($this->status) {

			case self::STATUS_PENDING: return 'PENDING';

			case self::STATUS_PAID: return 'PAID';

			case self::STATUS_CANCELED: return 'CANCELED';

			case self::STATUS_REFUNDED: return 'REFUNDED';
		}

		return null;
	}

	public function getCardPaymentProviderName(): ?string
	{
		switch ($this->payment_card_provider) {

			case self::PAYMENT_PROVIDER_CORVUS: return 'CORVUS';

			case self::PAYMENT_PROVIDER_PAYPAL: return 'PAYPAL';

		}

		return null;
	}


	public static function getCardProviders(): array
	{
		return [
			self::PAYMENT_PROVIDER_CORVUS,
			self::PAYMENT_PROVIDER_PAYPAL
		];
	}

	public static function getStatuses(): array
	{
		return [
			self::STATUS_PENDING,
			self::STATUS_PAID,
			self::STATUS_CANCELED,
			self::STATUS_REFUNDED,
			self::STATUS_OFFER_SENT
		];
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function mediaConfig(): array
	{
		return [
			'virman' => [
				'max' => 1
			],
			'invoice' => [
				'max' => 1
			],
			'offer' => [
				'max' => 1
			]
		];
	}

	public function shipments(): HasMany
	{
		return $this->hasMany(OrderShipment::class);
	}

	public static function createOrderNumber(): string
	{
		if ($order = Order::whereNotNull('order_number')->latest()->first()) {
			return (string) ((int) $order->order_number + 1);
		}

		return (string) 1;
	}

	public static function createReferenceNumber(): int
	{
		if ($order = Order::latest()->first()) {
			return (int) $order->reference_number + 1;
		}

		return 1;
	}

	public function getCustomerName(): ?string
	{
		if (isset($this->invoice_address['name'])) {
			return $this->invoice_address['name'];
		}

		if (isset($this->delivery_address['name'])) {
			return $this->delivery_address['name'];
		}

		if (isset($this->order_details['customer_name'])) {
			return trim($this->order_details['customer_name'] . ' ' . ($this->order_details['customer_name'] ?? null));
		}

		return null;
	}

	public function isPayPalVerified(): ?string
	{
		return $this->payment_id;
	}

	public function isShippingOrder(): bool
	{
		return $this->number_of_packages > 0;
	}

	public function routeNotificationForMail(mixed $notification): string
	{
		return $this->customer_email;
	}

	public function createInvoicePdf(GeneralInvoiceMaker $invoiceMaker): void
	{
		$pdf = $invoiceMaker->loadView($this);

		$mediaCollectionName = $invoiceMaker->getMediaCollectionName();

		$this->addMediaFromStream($pdf->output())->usingFileName($mediaCollectionName . $this->id . '.pdf')->toMediaCollection($mediaCollectionName, setting('media_storage'));
	}

	public function store(): BelongsTo
	{
		return $this->belongsTo(Store::class);
	}

	protected static function initObservers(): ?string
	{
		return OrderObserver::class;
	}

	public function prunable(): Builder
    {
        return static::where('created_at', '<', now()->subHour())
						->where('status', self::STATUS_PENDING)
						->whereNull('order_number')
						->whereNull('payment_created_at');
    }
}
