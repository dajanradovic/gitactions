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
				<table style="max-width:696px;width:100%;padding:0 0 0 0;line-height:1.3;font-family:Arial,sans-serif;font-size:16px" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="white" align="center">
					<tbody>
						<tr>
							<td style="font-size:18px;color:#2c3e50;font-weight:normal;vertical-align:middle;padding:14px 0 10px 0;font-family:Arial,sans-serif;line-height:1.3" align="center">
								Zaprimili smo vašu uplatu
							</td>
						</tr>
						<tr>
							<td style="padding:10px 0px 10px 0px;background-color:#ffffff;line-height:1.3;font-size:14px" bgcolor="#ffffff" align="left">
								<p style="Margin:5px;text-align:left;color:#000f49;font-size:16px" align="center">Pozdrav,<br><br>
									<span style="font-weight:bold">Vaša narudžba br. {{ $order->reference_number }} za {{ number_format($order->final_price,2) }} {{$order->currency }} je uspješno uplaćena. Hvala vam.</span><br><br>
											Obavijestit ćemo vas što je prije moguće o daljnjoj obradi vaše narudžbe.
								</p>
							</td>
						</tr>
					</tbody>
				</table>
           </div>
           <div id="footer">
            <p>Copyright @ {{\Carbon\Carbon::now()->year}} Gligora Delikatese d.o.o.</p>
           </div>

        </div>

    </body>

</html>
