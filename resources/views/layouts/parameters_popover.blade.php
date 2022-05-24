<?php

$rules = $params->filter(function ($value) {
	$value = $value->getType();

	return $value && is_subclass_of($value->getName(), \Illuminate\Foundation\Http\FormRequest::class);
})
->map(function ($rules) {
	$rules = $rules->getType()->getName();
	$rules = new $rules;

	return method_exists($rules, 'rules') ? $rules->rules() : [];
})
->first();

?>

<ul>
	@foreach($rules ?? [] as $key => $rule)
		<li>{{ $key }} => <code>{{ is_array($rule) ? implode(' | ', $rule) : $rule }}</code></li>
	@endforeach
</ul>
