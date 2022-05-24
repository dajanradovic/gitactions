<?php

$methods = (array) ($methods ?? []);
sort($methods);

$states = [
	'GET' => 'primary',
	'POST' => 'success',
	'PUT' => 'warning',
	'PATCH' => 'info',
	'DELETE' => 'danger',
];

?>

@foreach ($methods as $method)
	<?php $method = strtoupper($method); ?>
	<span class="label label-{{ $states[$method] ?? 'secondary' }} label-inline label-rounded">{{ $method }}</span>
@endforeach