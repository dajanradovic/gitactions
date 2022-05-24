function init() {
	initPayPalWebView();
}

function initPayPalWebView(selector = 'paypal-button-container') {
	const orderData = document.getElementById('paypal-order-data');
	if (!orderData) return;

	return !orderData
		? null
		: paypal
				.Buttons({
					createOrder(_data, actions) {
						return actions.order.create({
							purchase_units: [
								{
									amount: {
										value: orderData.dataset.amount,
										currency : 'EUR'
									}
								}
							],
							application_context: {
								shipping_preference: 'NO_SHIPPING'
							}
						});
					},
					onApprove(data, actions) {
						return actions.order
							.capture()
							.then(() => {
								window.location.replace(orderData.dataset.verifyUrl + '?paypal_bill_id=' + data.orderID);
							})
							.catch(error => {
								window.location.replace(orderData.dataset.errorUrl);
							});
					}
				})
				.render('#' + selector);
}

export { init };
