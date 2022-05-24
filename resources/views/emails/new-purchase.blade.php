<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ setting('app_name') }} | {{ __('emails.feedback') }}</title>
	<meta charset="utf-8">
    <style>

* {
font-family:Helvetica;
box-sizing: border-box;
color: black;
}

.wrapper{

    width: 100%;
    max-width:600px;
    margin: 0 auto;
}

#inner{
    background-color: white;
    margin: 0 auto;
    padding-top:10px;
    padding-left: 10px;
    padding-right: 10px;
    margin: 5px 5px 5px 5px;
    font-size: 20px;
}
hr{

    border: 0.3 solid black;
}

#logo{
    text-align: center;
    margin-top:20px;
    margin: 0 auto;
    margin-bottom:20px;
    width:100%;
    max-width: 280px;
    height: 115px;
}

.regular-text{

    font-size:14px;
}

table{
    width: 100%;
    text-align: center;
    font-size:16px;
}

.titles{

    font-weight: bolder;
    text-align: left;
}
table tr td{

    padding: 5px 5px 5px 5px;
    text-align: right;
    word-break: break-all;
}

.item{

    text-align: left;
}
.price{

    text-align: right;
}

#item-price-table{
    margin-top: 50px;
    font-size:14px;
    width: 100%;
}

table th{
    padding: 5px 5px 5px 5px;

}

#item-photo{

    text-align: left;
}

.earnings{

    text-align: right;
    font-size:14px;
    font-weight: bolder;
}

#summary tr td{

    font-size:14px;
}

.sales-tax{


    text-align: right;
    font-size:11px;
}

#footer{
    margin-top:20px;
    text-align: center;
    margin-bottom:20px;
}

#footer p{

    margin: 0px;
    font-size:12px;
}
#donation{

    font-size:14px;
}
.calculation{

text-align: left;
}

#main-info{
    width:100%;
    font-size: 12px;
    text-align: center;
}

#main-info tr {

font-size: 12px;
width: 100%;
}

.alignment{
    padding: 0px;
    text-align: left;
}

.alignment tr td{

    padding: 0px !important;
}

#main-order-info{
    margin-top:20px;
    width:100%;
    font-size: 12px;
    text-align: center;

}

#main-order-info tr {

font-size: 12px;
width: 100%;
}

#tracking--order-info{
    margin-top:20px;
    width:100%;
    font-size: 12px;
    text-align: center;

}

#tracking-order-info tr {

font-size: 12px;
width: 100%;
}

.final-words{

	text-align:left;
	font-size:12px;
}

.trackButton{
	font-size: 14px;
    padding: 3px 6px;
    margin-bottom: 0;

    display: inline-block;
    text-decoration: none;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
	color: white;
    background-color: rgb(24, 119, 242);
    border-color: #ccc;
}

.ii a[href] {
    color: #f4f5f7;
}

    </style>
</head>
    <body>
        <div class="wrapper">
            <div id ="logo"><img id="logo" src="{{ asset('img/logo.png') }}"></div>

            <div id="inner">
                    <p>Hello {{$order->getCustomerName() ?? ''}}, thank you for shopping at Gligora Delikatese d.o.o. webshop. </p>
					<p>Your order has been received and is being processed.</p>
					@if($order->payment_type == $order::PAYMENT_METHOD_CARD)
                    	<p class="regular-text">Your order has been paid. Please take a moment to review your order below.</p>
					@elseif($order->payment_type == $order::PAYMENT_METHOD_POUZECE)
						<p class="regular-text">Please take a moment to review your order below. Order will be sent to and you will pay to a delivery service:</p>
					@endif

					<p class="regular-text">If you have any questions, don't hesitate to contact us on <a href="mailto:info@seansoul.com">info@seansoul.com</a></p>
                    <hr>

					@if($order->isShippingOrder())

                    <table id="main-info">
                        <tr>
                          <th class="alignment">Shipping to</th>
                          <th style="text-align: right;"></th>
                        </tr>
                        <tr>
                            <td class="alignment" style="padding-top: 0px;">{{ $order->getCustomerName() }}</td>
							<td></td>
							<td></td>
                        </tr>
                        <tr>
                            <td class="alignment">{{ $order->delivery_address['street'] }}</td>
                            <td></td>
							<td></td>
                       </tr>
                       <tr>
                        <td class="alignment">{{ $order->delivery_address['city'] }}, {{ $order->delivery_address['zip_code'] }}</td>
                        <td> </td>
						<td> </td>
                       </tr>
                       <tr>
                        <td class="alignment">{{ \App\Models\Address::getCountryFullName($order->delivery_address['country_code']) }}</td>
                        <td> </td>
						<td> </td>

                       </tr>
                      </table>
					  @else
					  <p class="regular-text">You have chosen tu pick order in our store: {{$order->store->id}}  Please, be patient, and you will get an email once the
						  order is ready to be picked-up. It usually takes a couple of days.
					  </p>
					  @endif

                      <table id="main-order-info">
                        <tr>
                          <th class="alignment">Order Date</th>
                          <th style="text-align: right;">Order number</th>
                        </tr>
                        <tr>
                             <td class="alignment" style="padding-top: 0px;">{{ formatLocalTimestamp($order->created_at) }}</td>
                             <td style="max-width:40px;">{{$order->order_number}}</td>
                        </tr>

                      </table>

                      </table>

                      <table id="item-price-table">
                        <tr>
                          <th class="item" style="padding-left: 25px;">Item</th>
                          <th class="price">Price</th>

						@foreach($order->orderItems as $item)
						</tr>
                          <td class="item">{{$item->product->name}}</td>
                          <td class="price">{{number_format($item->total_price , 2)}} {{$order->currency}}</td>
                        </tr>
						@endforeach

                      </table>
                      <hr>
					  <table id="summary">
                        <tr>
                            <td class="calculation">Subtotal</td>
                            <td>{{number_format($order->total_price, 2)}} {{$order->currency}}</td>
                        </tr>
						@if($order->total_discounts > 0)

                        <tr>
                            <td class="calculation">Discount</td>
                            <td>- {{number_format($order->total_discounts, 2)}} {{$order->currency}}</td>

                        </tr>
						@endif
						@if($order->isShippingOrder())
                        <tr>
                          <td class="calculation">Shipping</td>
                          <td>{{number_format($order->shipping_price, 2)}} {{$order->currency}}</td>
                        </tr>
						@endif
						<tr>
                            <td class="calculation">Tax</td>
                            <td>{{number_format($order->total_tax, 2)}} {{$order->currency}}</td>
                        </tr>
                        <tr>
                        <td style="font-size:18px; font-weight: bolder; text-align: left;">TOTAL</td>
                        <td style="font-size:18px;"><b>{{number_format($order->final_price, 2)}} {{$order->currency}}</b></td>
                        </tr>
                      </table>
                      <hr>

					@if($order->isShippingOrder())
						<p class="final-words">You will receive email with shipping number once the order is ready for shipping</p>
					@endif
					<hr>
           </div>

           <div id="footer">
            <p>Copyright @ {{\Carbon\Carbon::now()->year}} Gligora Delikatese d.o.o.</p>
           </div>

        </div>

    </body>

</html>
