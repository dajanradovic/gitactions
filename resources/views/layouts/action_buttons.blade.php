<?php

if (isset($actions)) {
	$action_types = [
		'remove' => [
			'label' => __('action-buttons.remove'),
			'method' => 'DELETE',
			'state_class' => 'danger',
			'icon_class' => 'la la-trash'
		],
		'cancel' => [
			'label' => __('action-buttons.cancel'),
			'method' => 'DELETE',
			'state_class' => 'danger',
			'icon_class' => 'la la-ban'
		],
		'activate' => [
			'label' => __('action-buttons.activate'),
			'method' => 'POST',
			'state_class' => 'warning',
			'icon_class' => 'la la-toggle-on'
		],
		'deactivate' => [
			'label' => __('action-buttons.deactivate'),
			'method' => 'POST',
			'state_class' => 'warning',
			'icon_class' => 'la la-toggle-off'
		],
		'sessions' => [
			'url' => true,
			'label' => __('users.sessions'),
			'method' => 'GET',
			'state_class' => 'info',
			'icon_class' => 'la la-user-lock'
		],
		'2fa' => [
			'url' => true,
			'label' => __('users.2fa'),
			'method' => 'GET',
			'state_class' => 'warning',
			'icon_class' => 'la la-user-lock'
		],
		'test-api' => [
			'url' => true,
			'target' => '_blank',
			'label' => __('action-buttons.test-api'),
			'method' => 'GET',
			'state_class' => 'primary',
			'icon_class' => 'la la-external-link'
		],
		'truncate' => [
			'label' => __('action-buttons.truncate'),
			'method' => 'DELETE',
			'state_class' => 'danger',
			'icon_class' => 'la la-undo-alt'
		],
		'roles-modal' => [
			'label' => __('roles.title-m'),
			'method' => 'POST',
			'state_class' => 'primary',
			'icon_class' => 'la la-ban',
			'attributes' => [
				'data-toggle' => 'modal',
				'data-target' => '#roles-modal'
			]
		],
		'activity-modal' => [
			'label' => __('activities.activity'),
			'method' => 'GET',
			'state_class' => 'info',
			'icon_class' => 'la la-clock-o',
			'attributes' => [
				'data-toggle' => 'modal',
				'data-target' => '#activity-modal'
			]
		],
		'likes-modal' => [
			'label' => __('blogs.likes'),
			'method' => 'GET',
			'state_class' => 'primary',
			'icon_class' => 'la la-thumbs-up',
			'attributes' => [
				'data-toggle' => 'modal',
				'data-target' => '#likes-modal'
			]
		],
		'comments-modal' => [
			'label' => __('blogs.comments'),
			'method' => 'GET',
			'state_class' => 'primary',
			'icon_class' => 'la la-comments',
			'attributes' => [
				'data-toggle' => 'modal',
				'data-target' => '#comments-modal'
			]
		]
	];

	foreach ($actions as $action) {
		if (!($action['condition'] ?? true)) {
			continue;
		}

		$type = trim($action['type']);

		if (!array_key_exists($type, $action_types)) {
			continue;
		}

		$is_modal = isset($action_types[$type]['attributes']);
		$method = $action_types[$type]['method'];

		if (is_string($action['action'])) {
			$route = $action['action'];
			$form_action = $is_modal ? $route : route($route);
		} else {
			$route = key($action['action']);
			$form_action = route($route, current($action['action']));
		}

		if (
			!$is_modal
			&& (!auth()->user()->hasAllowedMethod($method)
				|| !auth()->user()->canViewRoute($route, true))
			) {
			continue;
		}

		$is_url = isset($action_types[$type]['url']) && $action_types[$type]['url'];
		$target = $action_types[$type]['target'] ?? '_self';
		$state_class = $action_types[$type]['state_class'];
		$icon_class = $action_types[$type]['icon_class'];
		$label = $action_types[$type]['label'];
		$data = $action['data'] ?? [];
		$message = $action['message'] ?? ($action_types[$type]['message'] ?? '');
		$form_id = mt_rand();

		if ($is_modal) {
			$attributes = $action_types[$type]['attributes'];
			$attributes['data-modal-data'] = $form_action;

			foreach ($attributes as $key => &$value) {
				if (is_bool($value)) {
					if ($value) {
						$value = $key;
					} else {
						continue;
					}
				} else {
					$value = $key . "='" . $value . "'";
				}
			}

			$attributes = implode(' ', $attributes);
		} else {
			$attributes = 'form="form-' . $form_id . '" data-action-buttons-form';
		}

		if ($is_url) {
			?>
			<a href="{{ $form_action }}" target="{{ $target }}" class="dropdown-item">
				<i class="{{ $icon_class }}"></i>&nbsp;
				<span class="text-{{ $state_class }}">{{ $label }}</span>
			</a>
			<?php

			continue;
		} ?>
		<button type="button" {!! $attributes !!} class="dropdown-item">
			<i class="{{ $icon_class }}"></i>&nbsp;
			<span class="text-{{ $state_class }}">{{ $label }}</span>
			<?php

			if (!$is_modal) {
				?>
				<form action="{{ $form_action }}" data-submit-message="{{ $message }}" method="post" target="{{ $target }}" id="form-{{ $form_id }}" hidden>
					@csrf
					@method($method)
					<?php

					foreach ($data as $key => $values) {
						$values = (array) $values;

						foreach ($values as $value) {
							?>
							<input type="hidden" name="{{ $key }}" value="{{ $value }}">
							<?php
						}
					} ?>
				</form>
				<?php
			} ?>
		</button>
		<?php
	}
}

?>
