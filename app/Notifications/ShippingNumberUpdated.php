<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\ShippingProviderInterface;
use Illuminate\Notifications\Messages\MailMessage;

class ShippingNumberUpdated extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(public string $shippingNumber, private ShippingProviderInterface $shippingProviderInterface, public string $orderNumber)
	{
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array
	 */
	public function via(object $notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail(object $notifiable)
	{
		return (new MailMessage)
					->subject('Order updated with shipping data')
					->line("Shipping number for order #$this->orderNumber at Gligora delikatese has been created")
					->line('Shipping number: ' . $this->shippingNumber)
					->action('Track order', url($this->shippingProviderInterface->createTrackingLink($this->shippingNumber)))
					->line('Thank you for using our application!');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array
	 */
	public function toArray(object $notifiable)
	{
		return [

		];
	}
}
