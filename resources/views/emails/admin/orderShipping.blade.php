<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	    <h2>{!! $title !!}</h2>
	    <p>{!! $messages !!}</p>
	    <?php if($title == "Shipping Detail") {?>
		<p>Shipping Detail: {!! $shippingDetail !!}</p>
		<?php }else if($title =="Order Status Update"){?>
		<p>Order Status: {!! $shippingDetail !!}</p>
		<?php }?>
		<p>Order Detail: {!! $url !!}</p>
		<p>From Only Muscle.co.uk support team</p>
	</body>
</html>
