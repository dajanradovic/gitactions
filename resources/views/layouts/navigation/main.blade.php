<ul class="menu-nav">
	<?php

	$tab = Route::currentRouteName();
	$navbar_schema = config('navbar.main');
	$user = auth()->user();

	foreach ($navbar_schema as $page) {
		$type = $title = $icon_class = '';
		$extra_routes = [];

		if (isset($page['type'])) {
			$type = strtolower(trim($page['type']));
		}

		if (isset($page['title'])) {
			$title = __(trim($page['title']));
		}

		if (isset($page['icon_class'])) {
			$icon_class = trim($page['icon_class']);
		}

		if (isset($page['extra_routes'])) {
			$extra_routes = (array) $page['extra_routes'];
		}

		if ($type == 'divider') {
			?>
			<li class="menu-section">
				<h4 class="menu-text">{{ $title }}</h4>
			</li>
			<?php
		} elseif (!isset($page['items']) || !count($page['items'])) {
			$route = trim($page['route']);

			if (!$user->canViewRoute($route, true)) {
				continue;
			}

			$extra_routes[] = $route;

			$is_active_class = in_array($tab, $extra_routes) ? 'menu-item-active' : '';
			$target = $page['target'] ?? ''; ?>
			<li class="menu-item {{ $is_active_class }}" aria-haspopup="true">
				<a href="{{ route($route) }}" target="{{ $target }}" class="menu-link">
					<i class="menu-icon {{ $icon_class }}"></i>
					<span class="menu-text">{{ $title }}</span>
					@if($route == $tab && count($errors))
						<span class="menu-label">
							<span class="label label-rounded label-danger">{{ count($errors) }}</span>
						</span>
					@endif
				</a>
			</li>
			<?php
		} else {
			$items = $page['items'];
			$items_check = false;
			$is_open_class = '';

			foreach ($items as $item) {
				$route = trim($item['route']);

				if (!$user->canViewRoute($route, true)) {
					continue;
				}

				$item_extra_routes = isset($item['extra_routes']) ? (array) $item['extra_routes'] : [];
				$curr_extra_routes = array_merge($extra_routes, $item_extra_routes, [$route]);

				if (in_array($tab, $curr_extra_routes)) {
					$is_open_class = 'menu-item-open';
				}
				$items_check = true;
			}

			if (!$items_check) {
				continue;
			} ?>
			<li class="menu-item menu-item-submenu {{ $is_open_class }}" aria-haspopup="true" data-menu-toggle="hover">
				<a href="javascript:;" class="menu-link menu-toggle">
					<i class="menu-icon {{ $icon_class }}"></i>
					<span class="menu-text">{{ $title }}</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="menu-submenu">
					<i class="menu-arrow"></i>
					<ul class="menu-subnav">
						<li class="menu-item menu-item-parent" aria-haspopup="true">
							<span class="menu-link">
								<span class="menu-text">{{ $title }}</span>
							</span>
						</li>
						<?php

						foreach ($items as $item) {
							$route = trim($item['route']);

							if (!$user->canViewRoute($route, true)) {
								continue;
							}

							$extra_routes = isset($item['extra_routes']) ? (array) $item['extra_routes'] : [];
							$extra_routes[] = $route;

							$title = __(trim($item['title']));
							$is_active_class = in_array($tab, $extra_routes) ? 'menu-item-active' : '';
							$target = $item['target'] ?? ''; ?>
							<li class="menu-item {{ $is_active_class }}" aria-haspopup="true">
								<a href="{{ route($route) }}" target="{{ $target }}" class="menu-link">
									<i class="menu-bullet menu-bullet-dot"><span></span></i>
									<span class="menu-text">{{ $title }}</span>
									@if($route == $tab && count($errors))
										<span class="menu-label">
											<span class="label label-rounded label-danger">{{ count($errors) }}</span>
										</span>
									@endif
								</a>
							</li>
							<?php
						} ?>
					</ul>
				</div>
			</li>
			<?php
		}
	}

	?>
	<li class="menu-section">
		<h4 class="menu-text">{{ $user->name }}</h4>
	</li>
	<li class="menu-item menu-item" aria-haspopup="true">
		<a href="javascript:;" class="menu-link logout-action">
			<i class="menu-icon fa fa-sign-out-alt"></i>
			<span class="menu-text">{{ __('global.logout') }}</span>
		</a>
	</li>
</ul>