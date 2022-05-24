<div class="card" style="width: 100%">
	<div class="card-body">
		<h5 class="card-title">{{ $card_name }}</h5>
	  	<p class="card-text">@include('layouts.forms.generate_form_fields', ['fields' => $fields_address])</p>
	</div>
</div>
