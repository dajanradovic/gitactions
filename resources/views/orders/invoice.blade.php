<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $company_name }}</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td valign="top"><img src="{{ asset('img/logo.png') }}" alt="" width="150"/></td>
        <td align="right">
            <h3>{{ $company_name }}</h3>
            <pre>
				{{ $company_name }}
                {{ $company_address }}
				{{$company_zip_code }} {{ $company_town }}
				+385 23 698 425
				+385 91 4365 401
                delikatese@gligora.com
            </pre>
        </td>
    </tr>

  </table>

  <table width="100%">
    <tr>
        <td><strong>Order number:</strong> {{$order->order_number}}</td>
        <td><strong>To:</strong> {{$order->getCustomerName()}}</td>
    </tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Description</th>
        <th>Unit Price {{ $order->currency }}</th>
		<th>Discounts {{ $order->currency }}</th>
		<th>Quantity</th>
        <th>Total {{ $order->currency }}</th>
      </tr>
    </thead>
    <tbody>
	  @foreach($order->orderItems as $item)
      <tr>
        <th scope="row">1</th>
        <td>{{$item->product->name}}</td>
        <td align="right">{{number_format($item->price, 2)}}</td>
		<td align="right">{{number_format($item->discount_amount, 2)}}</td>
		<td align="center">{{ $item->quantity }}</td>
        <td align="right">{{number_format($item->total_price, 2)}}</td>
      </tr>
	  @endforeach

    </tbody>

    <tfoot>
        <tr>
            <td colspan="4"></td>
            <td align="right">Subtotal {{ $order->currency }}</td>
            <td align="right">{{ number_format($order->total_price, 2)}}</td>
        </tr>
		@if($order->total_discounts > 0)
		<tr>
            <td colspan="4"></td>
            <td align="right">Discount {{ $order->currency }}</td>
            <td align="right"> - {{number_format($order->total_discounts, 2)}}</td>
        </tr>
		@endif
		@if($order->isShippingOrder())
		<tr>
            <td colspan="4"></td>
            <td align="right">Shipping {{ $order->currency }}</td>
            <td align="right">{{number_format($order->shipping_price, 2)}}</td>
        </tr>
		@endif
		@if($order->sales_tax)
        <tr>
            <td colspan="4"></td>
            <td align="right">Tax {{ $order->currency }}</td>
            <td align="right">{{number_format($order->tax_total, 2)}}</td>
        </tr>
		@endif
        <tr>
            <td colspan="4"></td>
            <td align="right">Total {{ $order->currency }}</td>
            <td align="right" class="gray">{{number_format($order->final_price,2)}}</td>
        </tr>
    </tfoot>
  </table>
  <table width="100%" style="margin-top: 100px;">
    <tr>
        <td><strong><i>Created on:</i></strong><i> {{$order->created_at}}</i></td>
    </tr>

  </table>
</body>
</html>
