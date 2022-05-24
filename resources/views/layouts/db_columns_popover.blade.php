<?php

$primary = $table->getPrimaryKey();
$primary = $primary ? $primary->getColumns() : [];

?>

<ul>
	@foreach($table->getColumns() as $column)
		<?php

		$name = in_array($column->getName(), $primary) ? '<strong>' . $column->getName() . '</strong>' : $column->getName();

		?>
		<li>{!! $name !!} => <code>{{ $column->getType()->getName() }} | {{ $column->getNotnull() ? __('db.not-null') : __('db.null') }}</code></li>
	@endforeach
</ul>