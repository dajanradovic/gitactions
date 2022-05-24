<?php

$token = auth()->user()->token();
$start_timestamp = now()->subHours(1);

$fields_daterange = [
	[
		'label' => __('forms.daterange'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'chart-date-range',
			'type' => 'text',
			'value' => formatLocalTimestamp($start_timestamp, 'd/m/Y H:i') . ' - ' . formatLocalTimestamp(null, 'd/m/Y') . ' 23:59',
			'class' => 'chart-daterangepicker',
			'readonly' => true
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'chart-date-range-first',
			'type' => 'hidden',
			'value' => formatLocalTimestamp($start_timestamp, 'Y-m-d H:i')
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'chart-date-range-second',
			'type' => 'hidden',
			'value' => formatLocalTimestamp(null, 'Y-m-d 23:59')
		]
	]
];

$request = request();
$routes = Route::getRoutes();
$pdo = DB::connection()->getPdo();

$db_server_info = $pdo->getAttribute($pdo::ATTR_CONNECTION_STATUS);
$db_driver = $pdo->getAttribute($pdo::ATTR_DRIVER_NAME);
$db_driver_ver = $pdo->getAttribute($pdo::ATTR_SERVER_VERSION);

$cwd = getcwd();
$disk_total = disk_total_space($cwd);
$disk_taken = $disk_total - disk_free_space($cwd);

$disk_taken_perc = round((100 * $disk_taken) / $disk_total, 2);
$disk_taken = round($disk_taken / (1024 * 1024 * 1024), 2);
$disk_total = round($disk_total / (1024 * 1024 * 1024), 2);

if (function_exists('apache_get_modules')) {
	$apache_modules = apache_get_modules();
	$apache_modules_count = count($apache_modules);
	sort($apache_modules);
}

$php_exts = get_loaded_extensions();
$php_exts_count = count($php_exts);
sort($php_exts);

$os = php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('v') . ' ' . php_uname('m');
$opcache = function_exists('opcache_get_status') ? opcache_get_status(false) : null;

$charts = [
	[
		'id' => 'chart-usage-stats-cpu',
		'title' => __('tech-info.usage-stats-cpu'),
		'icon' => 'fa fa-microchip',
		'width' => 'col-sm-6',
		'value_provider' => 'cpu',
		'date_provider' => 'created_at',
		'api' => ['api.charts.usage-stats' => ['token' => $token]]
	],
	[
		'id' => 'chart-usage-stats-storage',
		'title' => __('tech-info.usage-stats-storage'),
		'icon' => 'fa fa-database',
		'width' => 'col-sm-6',
		'value_provider' => 'storage',
		'date_provider' => 'created_at',
		'api' => ['api.charts.usage-stats' => ['token' => $token]]
	]
];

?>

@extends('layouts.master')

@push('scripts')
	@include('layouts.chart_scripts')
@endpush

@section('content')
	@include('layouts.list_header', ['title' => __('tech-info.title'), 'icon' => 'fa fa-info'])
	<div class="card-body">
		<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist" data-active-tab="tab">
			<li class="nav-item">
				<a href="#btabs-basic" class="nav-link active" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-info"></i></span>
            		<span class="nav-text">{{ __('settings.menu-basic') }}</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-opcache" class="nav-link" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-database"></i></span>
            		<span class="nav-text">OPcache</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-octane" class="nav-link" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-server"></i></span>
            		<span class="nav-text">Octane</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-routes" class="nav-link" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-sitemap"></i></span>
            		<span class="nav-text">{{ __('tech-info.routes') }}</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-modules" class="nav-link" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-cogs"></i></span>
            		<span class="nav-text">{{ __('tech-info.modules') }}</span>
				</a>
			</li>
		</ul>
		<div class="tab-content mt-5">
			<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
				<div class="table-responsive">
					<table width="100%" class="table table-head-custom">
						<tbody>
							<tr><td>{{ __('tech-info.ip-address') }}:</td><td>{{ $request->server->get('SERVER_ADDR', '-') }}</td></tr>
							<tr><td>{{ __('tech-info.host-and-proto') }}:</td><td>{{ $request->getHttpHost() }} via {{ $request->server->get('SERVER_PROTOCOL', '-') }}</td></tr>
							<tr><td>{{ __('tech-info.software') }}:</td><td>{{ $request->server->get('SERVER_SOFTWARE', '-') }} via {{ PHP_SAPI }}</td></tr>
							<tr><td>{{ __('tech-info.ssl-version') }}:</td><td>{{ OPENSSL_VERSION_TEXT }}</td></tr>
							<tr><td>{{ __('tech-info.secure-con') }} (HTTPS):</td><td>@include('layouts.bool_badge', ['value' => $request->secure()])</td></tr>
							<tr><td>{{ __('tech-info.cwd') }}:</td><td>{{ $cwd }}</td></tr>
							<tr><td>{{ __('tech-info.php-user') }}:</td><td>{{ get_current_user() }}</td></tr>
							<tr><td>{{ __('tech-info.php-version') }}:</td><td>{{ PHP_VERSION }}</td></tr>
							<tr><td>{{ __('tech-info.framework-version') }}:</td><td>{{ app()->version() }}</td></tr>
							<tr><td>{{ __('tech-info.config-cached') }}:</td><td>@include('layouts.bool_badge', ['value' => app()->configurationIsCached()])</td></tr>
							<tr><td>{{ __('tech-info.routes-cached') }}:</td><td>@include('layouts.bool_badge', ['value' => app()->routesAreCached()])</td></tr>
							<tr><td>{{ __('tech-info.events-cached') }}:</td><td>@include('layouts.bool_badge', ['value' => app()->eventsAreCached()])</td></tr>
							<tr><td>{{ __('tech-info.environment') }}:</td><td>{{ app()->environment() }}</td></tr>
							<tr><td>{{ __('tech-info.debug-mode') }}:</td><td>@include('layouts.bool_badge', ['value' => app()->hasDebugModeEnabled(), 'inverted' => true])</td></tr>
							<tr><td>{{ __('tech-info.db-server') }}:</td><td>{{ $db_server_info }}</td></tr>
							<tr><td>{{ __('tech-info.db-driver') }}:</td><td>{{ $db_driver }} {{ $db_driver_ver }}</td></tr>
							<tr><td>{{ __('tech-info.os') }}:</td><td>{{ $os }}</td></tr>
							<tr><td>{{ __('tech-info.queue') }}:</td><td>{{ $queue_size }}</td></tr>
							<tr><td>{{ __('tech-info.disk-usage') }}:</td><td>{{ $disk_taken }} / {{ $disk_total }} GB ({{ $disk_taken_perc }}%)</td></tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="tab-pane" id="btabs-opcache" role="tabpanel">
				<div class="table-responsive">
					<table width="100%" class="table table-head-custom">
						<tbody>
							@if(!empty($opcache))
								<?php

								$total_memory = $opcache['memory_usage']['used_memory'] + $opcache['memory_usage']['free_memory'];
								$total_memory_perc = round((100 * $opcache['memory_usage']['used_memory']) / $total_memory, 2);
								$used_memory = round($opcache['memory_usage']['used_memory'] / (1024 * 1024), 2);
								$total_memory = round($total_memory / (1024 * 1024), 2);
								$wasted_memory = round($opcache['memory_usage']['wasted_memory'] / (1024 * 1024), 2);
								$current_wasted_percentage = round($opcache['memory_usage']['current_wasted_percentage'], 2);

								$used_memory_strings = round($opcache['interned_strings_usage']['used_memory'] / (1024 * 1024), 2);
								$total_memory_strings = round($opcache['interned_strings_usage']['buffer_size'] / (1024 * 1024), 2);
								$total_memory_strings_perc = round((100 * $opcache['interned_strings_usage']['used_memory']) / $opcache['interned_strings_usage']['buffer_size'], 2);

								$hit_rate = round($opcache['opcache_statistics']['opcache_hit_rate'], 2);
								$start_time = $opcache['opcache_statistics']['last_restart_time'] ? formatLocalTimestamp($opcache['opcache_statistics']['last_restart_time']) : '-';

								?>
								<tr><td>{{ __('opcache.enabled') }}:</td><td>@include('layouts.bool_badge', ['value' => $opcache['opcache_enabled']])</td></tr>
								<tr><td>{{ __('opcache.cache-full') }}:</td><td>@include('layouts.bool_badge', ['value' => $opcache['cache_full'], 'inverted' => true])</td></tr>
								<tr><td>{{ __('opcache.restart-pending') }}:</td><td>@include('layouts.bool_badge', ['value' => $opcache['restart_pending'], 'inverted' => true])</td></tr>
								<tr><td>{{ __('opcache.restart-progress') }}:</td><td>@include('layouts.bool_badge', ['value' => $opcache['restart_in_progress'], 'inverted' => true])</td></tr>
								<tr><td>{{ __('opcache.memory-usage') }}:</td><td>{{ $used_memory }} / {{ $total_memory }} MB ({{ $total_memory_perc }}%)</td></tr>
								<tr><td>{{ __('opcache.wasted-memory') }}:</td><td>{{ $wasted_memory }} MB ({{ $current_wasted_percentage }}%)</td></tr>
								<tr><td>{{ __('opcache.strings-usage') }}:</td><td>{{ $used_memory_strings }} / {{ $total_memory_strings }} MB ({{ $total_memory_strings_perc }}%)</td></tr>
								<tr><td>{{ __('opcache.strings-count') }}:</td><td>{{ $opcache['interned_strings_usage']['number_of_strings'] }}</td></tr>
								<tr><td>{{ __('opcache.scripts-count') }}:</td><td>{{ $opcache['opcache_statistics']['num_cached_scripts'] }}</td></tr>
								<tr><td>{{ __('opcache.keys-count') }}:</td><td>{{ $opcache['opcache_statistics']['num_cached_keys'] }} / {{ $opcache['opcache_statistics']['max_cached_keys'] }}</td></tr>
								<tr><td>{{ __('opcache.cache-hits') }}:</td><td>{{ $opcache['opcache_statistics']['hits'] }}</td></tr>
								<tr><td>{{ __('opcache.cache-misses') }}:</td><td>{{ $opcache['opcache_statistics']['misses'] }}</td></tr>
								<tr><td>{{ __('opcache.hit-rate') }}:</td><td>{{ $hit_rate }}%</td></tr>
								<tr><td>{{ __('opcache.last-run') }}:</td><td>{{ $start_time }}</td></tr>
							@else
								<tr><td>{{ __('opcache.no-data') }}</td></tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>

			<div class="tab-pane" id="btabs-octane" role="tabpanel">
				<div class="table-responsive">
					<table width="100%" class="table table-head-custom">
						<tbody>
							@if(!empty($octane_stats))
								<tr><td>{{ __('tech-info.start-time') }}:</td><td>{{ formatLocalTimestamp($octane_stats['start_time']) }}</td></tr>
								<tr><td>{{ __('tech-info.worker-num') }}:</td><td>{{ $octane_stats['worker_num'] }}</td></tr>
								<tr><td>{{ __('tech-info.total-requests') }}:</td><td>{{ $octane_stats['request_count'] }}</td></tr>
							@else
								<tr><td>{{ __('opcache.no-data') }}</td></tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>

			<div class="tab-pane fade" id="btabs-routes" role="tabpanel">
				<table width="100%" class="table table-head-custom js-datatable" data-export-title="{{ setting('app_name') }} - {{ __('tech-info.routes') }}" data-export-message-top="{{ __('global.base-url') }}: {{ config('app.url') }}">
					<thead>
						<tr>
							<th>{{ __('tech-info.methods') }}</th>
							<th>{{ __('tech-info.uri') }}</th>
							<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export>{{ __('tech-info.validation') }}</th>
							<th>{{ __('tech-info.name') }}</th>
							<th>{{ __('tech-info.protected') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($routes as $route)
							<?php $params = collect($route->signatureParameters()); ?>
							<tr>
								<td>@include('layouts.method_badge', ['methods' => $route->methods()])</td>
								<td>
									{!! preg_replace_callback(
										'%\{(.+?)\}%',
										function ($matches) use ($params) {
											$param = $params
												->filter(function ($value) use ($matches) {
													return $value->getName() == str_replace('?', '', $matches[1]);
												})
												->first();

											if(!$param || !($type = $param->getType())) {
												return '<code class="bg-hover-state-secondary">' . $matches[0] . '</code>';
											}

											return '<code class="bg-hover-state-secondary">{' . $type->getName() . ' ' . $matches[1] . '}</code>';
										},
										$route->uri()
									) !!}
								</td>
								<td>
									<button type="button" title="{{ __('tech-info.validation') }}" class="btn btn-sm btn-outline-primary btn-elevate btn-circle btn-icon" data-container="body" data-toggle="popover" data-trigger="focus" data-html="true" data-placement="top" data-content="@include('layouts.parameters_popover', compact('params'))">
										<i class="fa fa-info"></i>
									</button>
								</td>
								<td>{{ $route->getName() }}</td>
								<td>@include('layouts.bool_badge', ['value' => in_array('auth', $route->gatherMiddleware())])</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="tab-pane fade" id="btabs-modules" role="tabpanel">
				<div class="accordion accordion-light accordion-toggle-arrow" id="accordions">
					@if(function_exists('apache_get_modules'))
						<div class="card">
							<div class="card-header">
								<div class="card-title collapsed" data-toggle="collapse" data-target="#accordionApache" aria-expanded="true" aria-controls="accordionApache">
									<i class="fa fa-server"></i> {{ __('tech-info.apache_modules') }} ({{ $apache_modules_count }})
								</div>
							</div>
							<div id="accordionApache" class="collapse" aria-labelledby="headingOne" data-parent="#accordions">
								<div class="card-body">
									<ul>
										@foreach($apache_modules as $value)
											<li>{{ $value }}</li>
										@endforeach
									</ul>
								</div>
							</div>
						</div>
					@endif
					<div class="card">
						<div class="card-header">
							<div class="card-title collapsed" data-toggle="collapse" data-target="#accordionPhp" aria-expanded="true" aria-controls="accordionPhp">
								<i class="fab fa-php"></i> {{ __('tech-info.php_exts') }} ({{ $php_exts_count }})
							</div>
						</div>
						<div id="accordionPhp" class="collapse" aria-labelledby="headingOne" data-parent="#accordions">
							<div class="card-body">
								<ul>
									@foreach($php_exts as $value)
										<li>{{ !empty($ext_ver = phpversion($value)) && $ext_ver != PHP_VERSION ? $value.' ('.$ext_ver.')' : $value }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
