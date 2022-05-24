@if(isset($path) && $path && auth()->user()->canViewRoute(key($path), true))
	<a href="{{ route(key($path), current($path)) }}" title="{{ __('global.edit') }}" data-container="body" data-toggle="tooltip" data-placement="left" class="btn btn-icon btn-light btn-hover-primary btn-sm">
		<i class="fa fa-edit text-primary"></i>
	</a>
@endif
