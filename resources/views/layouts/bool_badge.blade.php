<?php

$inverted ??= false;
$state = $inverted ? !$value : $value;
$state = $state ? 'success' : 'danger';
$label = $value ? __('global.yes') : __('global.no');

?>
<span class="label label-{{ $state }} label-inline">{{ $label }}</span>