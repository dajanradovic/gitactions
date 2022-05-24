<?php

use App\Models\Setting;

$smtp_protocols = [
	'' => __('global.none'),
	'tls' => 'TLS',
	'ssl' => 'SSL'
];

$storages = [
	'public' => __('settings.public'),
	'local' => __('settings.local'),
	's3' => 'AWS S3 bucket'
];

$caches = [
	'file' => __('global.file'),
	'redis' => 'Redis',
	'memcached' => 'Memcached'
];

$queues = [
	'sync' => __('settings.queue-sync'),
	'redis' => 'Redis'
];

$sessions = [
	'file' => __('global.file'),
	'database' => __('global.database'),
	'redis' => 'Redis',
	'memcached' => 'Memcached'
];

$basic_auth_username_fields = [
	'id' => __('forms.id'),
	'email' => __('forms.email')
];

$all_roles = ['' => __('global.none')];

foreach ($roles as $row) {
	$all_roles[$row->id] = $row->name;
}

$currencies = [
	Setting::CURRENCY_KUNA => __('settings.currency-kuna'),
	Setting::CURRENCY_EURO => __('settings.currency-euro')
];

$fields_basic = [
	[
		'label' => __('settings.app-name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'app_name',
			'name' => 'app_name',
			'type' => 'text',
			'value' => $settings->app_name,
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'label' => __('settings.app-description'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'app_description',
			'name' => 'app_description',
			'type' => 'text',
			'value' => $settings->app_description,
			'maxlength' => 500
		]
	],
	[
		'label' => __('settings.money-code'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'currency_code',
			'name' => 'currency_code',
			'type' => 'text',
			'value' => $settings->currency_code,
			'minlength' => 3,
			'maxlength' => 3,
			'required' => true
		]
	],
	[
		'label' => __('settings.google-api'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'google_api_key',
			'name' => 'google_api_key',
			'type' => 'text',
			'value' => $settings->google_api_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('tech-info.debug-mode'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'debug_mode_active',
			'name' => 'debug_mode_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->debug_mode_active
		]
	],
	[
		'label' => __('settings.maintenance-mode'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'maintenance_active',
			'name' => 'maintenance_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->maintenance_active
		]
	]
];

$fields_app = [
	[
		'label' => __('settings.app-scheme'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'app_scheme',
			'name' => 'app_scheme',
			'type' => 'text',
			'value' => $settings->app_scheme,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.min-ios-version'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'ios_min_version',
			'name' => 'ios_min_version',
			'type' => 'number',
			'value' => $settings->ios_min_version,
			'min' => 1
		]
	],
	[
		'label' => __('settings.min-android-version'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'android_min_version',
			'name' => 'android_min_version',
			'type' => 'number',
			'value' => $settings->android_min_version,
			'min' => 1
		]
	],
	[
		'label' => __('settings.min-ios-maintenance-version'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'ios_maintenance_min_version',
			'name' => 'ios_maintenance_min_version',
			'type' => 'number',
			'value' => $settings->ios_maintenance_min_version,
			'min' => 1
		]
	],
	[
		'label' => __('settings.min-android-maintenance-version'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'android_maintenance_min_version',
			'name' => 'android_maintenance_min_version',
			'type' => 'number',
			'value' => $settings->android_maintenance_min_version,
			'min' => 1
		]
	]
];

$fields_mail = [
	[
		'label' => __('settings.app-email'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'app_email',
			'name' => 'app_email',
			'type' => 'email',
			'value' => $settings->app_email,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.noreply-email'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'noreply_email',
			'name' => 'noreply_email',
			'type' => 'email',
			'value' => $settings->noreply_email,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.smtp-user'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'smtp_username',
			'name' => 'smtp_username',
			'type' => 'text',
			'value' => $settings->smtp_username,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.smtp-pass'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'smtp_password',
			'name' => 'smtp_password',
			'type' => 'text',
			'value' => $settings->smtp_password,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.smtp-host'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'smtp_host',
			'name' => 'smtp_host',
			'type' => 'text',
			'value' => $settings->smtp_host,
			'maxlength' => 100
		]
	],
	[
		'label' => __('settings.smtp-port'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'smtp_port',
			'name' => 'smtp_port',
			'type' => 'number',
			'value' => $settings->smtp_port,
			'min' => 1
		]
	],
	[
		'label' => __('settings.smtp-enc'),
		'tag' => 'select',
		'options' => $smtp_protocols,
		'selected' => $settings->smtp_protocol,
		'attributes' => [
			'id' => 'smtp_protocol',
			'name' => 'smtp_protocol'
		]
	]
];

$fields_passwords = [
	[
		'label' => __('settings.pass-min-length'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'min_pass_len',
			'name' => 'min_pass_len',
			'type' => 'number',
			'value' => $settings->min_pass_len,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.pass-uppercase-char'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'pass_uppercase_char',
			'name' => 'pass_uppercase_char',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->pass_uppercase_char
		]
	],
	[
		'label' => __('settings.pass-numeric-char'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'pass_numeric_char',
			'name' => 'pass_numeric_char',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->pass_numeric_char
		]
	],
	[
		'label' => __('settings.pass-special-char'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'pass_special_char',
			'name' => 'pass_special_char',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->pass_special_char
		]
	],
];

$fields_bitbucket = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'bitbucket_active',
			'name' => 'bitbucket_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->bitbucket_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#bitbucket_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'bitbucket_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'bitbucket'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'bitbucket_client_id',
			'name' => 'bitbucket_client_id',
			'type' => 'text',
			'value' => $settings->bitbucket_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'bitbucket_client_secret',
			'name' => 'bitbucket_client_secret',
			'type' => 'text',
			'value' => $settings->bitbucket_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_github = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'github_active',
			'name' => 'github_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->github_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#github_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'github_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'github'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'github_client_id',
			'name' => 'github_client_id',
			'type' => 'text',
			'value' => $settings->github_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'github_client_secret',
			'name' => 'github_client_secret',
			'type' => 'text',
			'value' => $settings->github_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_gitlab = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'gitlab_active',
			'name' => 'gitlab_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->gitlab_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#gitlab_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'gitlab_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'gitlab'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'gitlab_client_id',
			'name' => 'gitlab_client_id',
			'type' => 'text',
			'value' => $settings->gitlab_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'gitlab_client_secret',
			'name' => 'gitlab_client_secret',
			'type' => 'text',
			'value' => $settings->gitlab_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_facebook = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'facebook_active',
			'name' => 'facebook_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->facebook_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#facebook_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'facebook_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'facebook'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'facebook_client_id',
			'name' => 'facebook_client_id',
			'type' => 'text',
			'value' => $settings->facebook_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'facebook_client_secret',
			'name' => 'facebook_client_secret',
			'type' => 'text',
			'value' => $settings->facebook_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_twitter = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'twitter_active',
			'name' => 'twitter_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->twitter_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#twitter_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'twitter_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'twitter'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'twitter_client_id',
			'name' => 'twitter_client_id',
			'type' => 'text',
			'value' => $settings->twitter_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'twitter_client_secret',
			'name' => 'twitter_client_secret',
			'type' => 'text',
			'value' => $settings->twitter_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_google = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'google_active',
			'name' => 'google_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->google_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#google_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'google_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'google'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'google_client_id',
			'name' => 'google_client_id',
			'type' => 'text',
			'value' => $settings->google_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'google_client_secret',
			'name' => 'google_client_secret',
			'type' => 'text',
			'value' => $settings->google_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_linkedin = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'linkedin_active',
			'name' => 'linkedin_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->linkedin_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#linkedin_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'linkedin_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'linkedin'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'linkedin_client_id',
			'name' => 'linkedin_client_id',
			'type' => 'text',
			'value' => $settings->linkedin_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'linkedin_client_secret',
			'name' => 'linkedin_client_secret',
			'type' => 'text',
			'value' => $settings->linkedin_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_apple = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'apple_active',
			'name' => 'apple_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->apple_active
		]
	],
	[
		'label' => __('settings.redirect-url'),
		'tag' => 'input',
		'group' => [
			'left' => 'GET',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#apple_redirect_url" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'apple_redirect_url',
			'type' => 'url',
			'value' => route('login.oauth.callback', 'apple'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'apple_client_id',
			'name' => 'apple_client_id',
			'type' => 'text',
			'value' => $settings->apple_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'apple_client_secret',
			'name' => 'apple_client_secret',
			'type' => 'text',
			'value' => $settings->apple_client_secret,
			'maxlength' => 500
		]
	]
];

$fields_datetime = [
	[
		'label' => __('forms.timezone'),
		'tag' => 'select',
		'options' => renderTimezones(),
		'selected' => $settings->timezone,
		'attributes' => [
			'id' => 'timezone',
			'name' => 'timezone',
			'required' => true
		]
	],
	[
		'label' => __('settings.date-format'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'date_format',
			'name' => 'date_format',
			'type' => 'text',
			'value' => $settings->date_format,
			'maxlength' => 15,
			'required' => true
		]
	],
	[
		'label' => __('settings.time-format'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'time_format',
			'name' => 'time_format',
			'type' => 'text',
			'value' => $settings->time_format,
			'maxlength' => 15,
			'required' => true
		]
	]
];

$fields_login = [
	[
		'label' => __('settings.max-attempts'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'login_max_attempts',
			'name' => 'login_max_attempts',
			'type' => 'number',
			'value' => $settings->login_max_attempts,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.backoff-interval'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes')
		],
		'attributes' => [
			'id' => 'login_backoff_minutes',
			'name' => 'login_backoff_minutes',
			'type' => 'number',
			'value' => $settings->login_backoff_minutes,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.session-driver'),
		'tag' => 'select',
		'options' => $sessions,
		'selected' => $settings->session_driver,
		'attributes' => [
			'id' => 'session_driver',
			'name' => 'session_driver',
			'required' => true
		]
	],
	[
		'label' => __('settings.session-lifetime'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes')
		],
		'attributes' => [
			'id' => 'session_lifetime',
			'name' => 'session_lifetime',
			'type' => 'number',
			'value' => $settings->session_lifetime,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.password-reset-timeout'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes')
		],
		'attributes' => [
			'id' => 'password_reset_timeout',
			'name' => 'password_reset_timeout',
			'type' => 'number',
			'value' => $settings->password_reset_timeout,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.email-verification-timeout'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes')
		],
		'attributes' => [
			'id' => 'email_verification_timeout',
			'name' => 'email_verification_timeout',
			'type' => 'number',
			'value' => $settings->email_verification_timeout,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.basic-auth-username-field'),
		'tag' => 'select',
		'options' => $basic_auth_username_fields,
		'selected' => $settings->basic_auth_username_field,
		'attributes' => [
			'id' => 'basic_auth_username_field',
			'name' => 'basic_auth_username_field',
			'required' => true
		]
	],
	[
		'label' => __('settings.push-devices-cleanup-days'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.days')
		],
		'attributes' => [
			'id' => 'push_devices_cleanup_days',
			'name' => 'push_devices_cleanup_days',
			'type' => 'number',
			'value' => $settings->push_devices_cleanup_days,
			'min' => 1
		]
	]
];

$fields_api = [
	[
		'label' => __('settings.regenerate-secret-key'),
		'tag' => 'checkbox',
		'message' => __('settings.regenerate-secret-key-message'),
		'attributes' => [
			'id' => 'jwt_regenerate_secret_key',
			'name' => 'jwt_regenerate_secret_key',
			'value' => 1,
			'type' => 'checkbox'
		]
	],
	[
		'label' => __('settings.expiration-time'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes')
		],
		'attributes' => [
			'id' => 'jwt_expiration_time',
			'name' => 'jwt_expiration_time',
			'type' => 'number',
			'value' => $settings->jwt_expiration_time,
			'min' => 1
		]
	],
	[
		'label' => __('settings.api-rate-limit'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.requests-per-minute')
		],
		'attributes' => [
			'id' => 'api_rate_limit',
			'name' => 'api_rate_limit',
			'type' => 'number',
			'value' => $settings->api_rate_limit,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.backoff-interval'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes')
		],
		'attributes' => [
			'id' => 'api_rate_limit_backoff_minutes',
			'name' => 'api_rate_limit_backoff_minutes',
			'type' => 'number',
			'value' => $settings->api_rate_limit_backoff_minutes,
			'min' => 1,
			'required' => true
		]
	]
];

$fields_security = [
	[
		'label' => __('settings.cors-allowed-origins'),
		'tag' => 'textarea',
		'value' => implode("\n", $settings->cors_allowed_origins ?? []),
		'attributes' => [
			'id' => 'cors_allowed_origins',
			'name' => 'cors_allowed_origins',
			'maxlength' => 250,
			'rows' => 5
		]
	],
	[
		'label' => __('settings.csp-allowed-scripts'),
		'tag' => 'textarea',
		'value' => implode("\n", $settings->csp_allowed_scripts ?? []),
		'attributes' => [
			'id' => 'csp_allowed_scripts',
			'name' => 'csp_allowed_scripts',
			'maxlength' => 250,
			'rows' => 5
		]
	],
	[
		'label' => __('settings.csp-allowed-styles'),
		'tag' => 'textarea',
		'value' => implode("\n", $settings->csp_allowed_styles ?? []),
		'attributes' => [
			'id' => 'csp_allowed_styles',
			'name' => 'csp_allowed_styles',
			'maxlength' => 250,
			'rows' => 5
		]
	]
];

$fields_registration = [
	[
		'label' => __('settings.active-web'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'registration_active',
			'name' => 'registration_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->registration_active
		]
	],
	[
		'label' => __('settings.registration-web'),
		'tag' => 'select',
		'options' => $all_roles,
		'selected' => $settings->registration_role_id,
		'attributes' => [
			'id' => 'registration_role_id',
			'name' => 'registration_role_id'
		]
	],
	[
		'label' => __('settings.registration-api'),
		'tag' => 'select',
		'options' => $all_roles,
		'selected' => $settings->registration_api_role_id,
		'attributes' => [
			'id' => 'registration_api_role_id',
			'name' => 'registration_api_role_id'
		]
	]
];

$fields_media = [
	[
		'label' => __('settings.storage'),
		'tag' => 'select',
		'options' => $storages,
		'selected' => $settings->media_storage,
		'attributes' => [
			'id' => 'media_storage',
			'name' => 'media_storage',
			'required' => true
		]
	],
	[
		'label' => __('settings.max-upload-size'),
		'tag' => 'input',
		'group' => [
			'right' => 'MB'
		],
		'attributes' => [
			'id' => 'max_upload_size',
			'name' => 'max_upload_size',
			'type' => 'number',
			'value' => $settings->max_upload_size / (1024 * 1024),
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.max-video-length'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.seconds')
		],
		'attributes' => [
			'id' => 'max_video_length',
			'name' => 'max_video_length',
			'type' => 'number',
			'value' => $settings->max_video_length,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.protected-media-token-valid-until'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes')
		],
		'attributes' => [
			'id' => 'protected_media_token_valid_until',
			'name' => 'protected_media_token_valid_until',
			'type' => 'number',
			'value' => $settings->protected_media_token_valid_until,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.thumb-width'),
		'tag' => 'input',
		'group' => [
			'right' => 'px'
		],
		'attributes' => [
			'id' => 'thumb_width',
			'name' => 'thumb_width',
			'type' => 'number',
			'value' => $settings->thumb_width,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('settings.queue-media-conversions'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'queue_media_conversions',
			'name' => 'queue_media_conversions',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->queue_media_conversions
		]
	],
	[
		'label' => __('settings.responsive-images-breakpoints'),
		'tag' => 'textarea',
		'value' => implode("\n", $settings->responsive_images_breakpoints ?? []),
		'attributes' => [
			'id' => 'responsive_images_breakpoints',
			'name' => 'responsive_images_breakpoints',
			'maxlength' => 100,
			'rows' => 10
		]
	]
];

$fields_cache = [
	[
		'label' => __('settings.cache-driver'),
		'tag' => 'select',
		'options' => $caches,
		'selected' => $settings->cache_store,
		'attributes' => [
			'id' => 'cache_store',
			'name' => 'cache_store',
			'required' => true
		]
	],
	[
		'label' => __('settings.queue-driver'),
		'tag' => 'select',
		'options' => $queues,
		'selected' => $settings->queue_driver,
		'attributes' => [
			'id' => 'queue_driver',
			'name' => 'queue_driver',
			'required' => true
		]
	],
];

$fields_aws = [
	[
		'label' => __('settings.access-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'aws_access_key_id',
			'name' => 'aws_access_key_id',
			'type' => 'text',
			'value' => $settings->aws_access_key_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.secret-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'aws_secret_access_key',
			'name' => 'aws_secret_access_key',
			'type' => 'text',
			'value' => $settings->aws_secret_access_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.default-region'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'aws_default_region',
			'name' => 'aws_default_region',
			'type' => 'text',
			'value' => $settings->aws_default_region,
			'maxlength' => 20
		]
	],
	[
		'label' => __('settings.bucket-name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'aws_bucket_name',
			'name' => 'aws_bucket_name',
			'type' => 'text',
			'value' => $settings->aws_bucket_name,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.bucket-url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'aws_bucket_url',
			'name' => 'aws_bucket_url',
			'type' => 'url',
			'value' => $settings->aws_bucket_url,
			'maxlength' => 100
		]
	]
];

$fields_monitor = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'monitor_active',
			'name' => 'monitor_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->monitor_active
		]
	],
	[
		'label' => __('settings.backup-keep-days'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.days')
		],
		'attributes' => [
			'id' => 'backup_keep_days',
			'name' => 'backup_keep_days',
			'type' => 'number',
			'value' => $settings->backup_keep_days,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('settings.backup-disks'),
		'tag' => 'select',
		'message' => __('settings.backup-disks-message'),
		'options' => $storages,
		'selected' => $settings->backup_disks,
		'attributes' => [
			'id' => 'backup_disks',
			'name' => 'backup_disks[]',
			'multiple' => true
		]
	],
	[
		'label' => __('settings.monitor-slack-webhook'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'monitor_slack_webhook',
			'name' => 'monitor_slack_webhook',
			'type' => 'url',
			'value' => $settings->monitor_slack_webhook,
			'maxlength' => 100
		]
	],
	[
		'label' => __('settings.monitor-emails'),
		'tag' => 'textarea',
		'value' => implode("\n", $settings->monitor_emails ?? []),
		'attributes' => [
			'id' => 'monitor_emails',
			'name' => 'monitor_emails',
			'maxlength' => 250,
			'rows' => 5
		]
	]
];

$fields_onesignal = [
	[
		'label' => __('settings.app-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'onesignal_app_id',
			'name' => 'onesignal_app_id',
			'type' => 'text',
			'value' => $settings->onesignal_app_id,
			'minlength' => 36,
			'maxlength' => 36
		]
	],
	[
		'label' => __('settings.rest-api-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'onesignal_rest_api_key',
			'name' => 'onesignal_rest_api_key',
			'type' => 'text',
			'value' => $settings->onesignal_rest_api_key,
			'maxlength' => 64
		]
	],
	[
		'label' => __('settings.user-auth-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'onesignal_user_auth_key',
			'name' => 'onesignal_user_auth_key',
			'type' => 'text',
			'value' => $settings->onesignal_user_auth_key,
			'maxlength' => 64
		]
	],
	[
		'label' => __('settings.onesignal-stats-check'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.days')
		],
		'attributes' => [
			'id' => 'onesignal_stats_check_days',
			'name' => 'onesignal_stats_check_days',
			'type' => 'number',
			'value' => $settings->onesignal_stats_check_days,
			'min' => 1
		]
	]
];

$fields_telescope = [
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'telescope_active',
			'name' => 'telescope_active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->telescope_active
		]
	],
	[
		'label' => __('settings.telecope-prune'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.hours')
		],
		'attributes' => [
			'id' => 'telescope_prune_hours',
			'name' => 'telescope_prune_hours',
			'type' => 'number',
			'value' => $settings->telescope_prune_hours,
			'min' => 1
		]
	],
	[
		'label' => __('settings.telecope-same-ip'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'telescope_same_ip',
			'name' => 'telescope_same_ip',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->telescope_same_ip
		]
	]
];

$fields_paypal = [
	[
		'label' => __('settings.paypal-sandbox'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'paypal_sandbox',
			'name' => 'paypal_sandbox',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->paypal_sandbox
		]
	],
	[
		'label' => __('settings.paypal-client-id-sandbox'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'paypal_sandbox_client_id',
			'name' => 'paypal_sandbox_client_id',
			'type' => 'text',
			'value' => $settings->paypal_sandbox_client_id,
			'maxlength' => 100
		]
	],
	[
		'label' => __('settings.paypal-client-secret-sandbox'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'paypal_sandbox_client_secret',
			'name' => 'paypal_sandbox_client_secret',
			'type' => 'text',
			'value' => $settings->paypal_sandbox_client_secret,
			'maxlength' => 100
		]
	],
	[
		'label' => __('settings.paypal-client-id-production'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'paypal_client_id',
			'name' => 'paypal_client_id',
			'type' => 'text',
			'value' => $settings->paypal_client_id,
			'maxlength' => 100
		]
	],
	[
		'label' => __('settings.paypal-client-secret-production'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'paypal_client_secret',
			'name' => 'paypal_client_secret',
			'type' => 'text',
			'value' => $settings->paypal_client_secret,
			'maxlength' => 100
		]
	]
];

$fields_webhook_scheduler = [
	[
		'label' => __('settings.api-base-url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'webhook_scheduler_url',
			'name' => 'webhook_scheduler_url',
			'type' => 'url',
			'value' => $settings->webhook_scheduler_url,
			'maxlength' => 100
		]
	],
	[
		'label' => __('settings.token'),
		'tag' => 'input',
		'message' => __('settings.token-use') . ' <code>Authorization: Bearer {token}</code>',
		'attributes' => [
			'id' => 'webhook_scheduler_token',
			'name' => 'webhook_scheduler_token',
			'type' => 'text',
			'value' => $settings->webhook_scheduler_token,
			'maxlength' => 500
		]
	]
];

$fields_sms_basic = [
	[
		'label' => __('settings.sms-default-reply'),
		'tag' => 'textarea',
		'value' => $settings->sms_default_reply,
		'attributes' => [
			'id' => 'sms_default_reply',
			'name' => 'sms_default_reply',
			'maxlength' => 160,
			'rows' => 5
		]
	]
];

$fields_vonage = [
	[
		'label' => __('settings.incoming-sms-webhook'),
		'tag' => 'input',
		'group' => [
			'left' => 'POST',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#vonage_incoming_webhook" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'vonage_incoming_webhook',
			'type' => 'url',
			'value' => route('api.webhooks.sms.vonage.incoming'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.from-number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'vonage_from_number',
			'name' => 'vonage_from_number',
			'type' => 'tel',
			'value' => $settings->vonage_from_number,
			'maxlength' => 20
		]
	],
	[
		'label' => __('settings.api-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'vonage_api_key',
			'name' => 'vonage_api_key',
			'type' => 'text',
			'value' => $settings->vonage_api_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.api-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'vonage_api_secret',
			'name' => 'vonage_api_secret',
			'type' => 'text',
			'value' => $settings->vonage_api_secret,
			'maxlength' => 50
		]
	],
];

$fields_twilio = [
	[
		'label' => __('settings.incoming-sms-webhook'),
		'tag' => 'input',
		'group' => [
			'left' => 'POST',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#twilio_incoming_webhook" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'twilio_incoming_webhook',
			'type' => 'url',
			'value' => route('api.webhooks.sms.twilio.incoming'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.from-number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'twilio_from_number',
			'name' => 'twilio_from_number',
			'type' => 'tel',
			'value' => $settings->twilio_from_number,
			'maxlength' => 20
		]
	],
	[
		'label' => __('settings.api-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'twilio_api_key',
			'name' => 'twilio_api_key',
			'type' => 'text',
			'value' => $settings->twilio_api_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.api-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'twilio_api_secret',
			'name' => 'twilio_api_secret',
			'type' => 'text',
			'value' => $settings->twilio_api_secret,
			'maxlength' => 50
		]
	],
];

$fields_infobip = [
	[
		'label' => __('settings.incoming-sms-webhook'),
		'tag' => 'input',
		'group' => [
			'left' => 'POST',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#infobip_incoming_webhook" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'infobip_incoming_webhook',
			'type' => 'url',
			'value' => route('api.webhooks.sms.infobip.incoming'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.from-number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'infobip_from_number',
			'name' => 'infobip_from_number',
			'type' => 'tel',
			'value' => $settings->infobip_from_number,
			'maxlength' => 20
		]
	],
	[
		'label' => __('settings.infobip-api-subdomain'),
		'tag' => 'input',
		'group' => [
			'left' => 'https://',
			'right' => '.api.infobip.com'
		],
		'attributes' => [
			'id' => 'infobip_api_subdomain',
			'name' => 'infobip_api_subdomain',
			'type' => 'text',
			'value' => $settings->infobip_api_subdomain,
			'maxlength' => 50
		]
	],
	[
		'label' => __('forms.username'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'infobip_username',
			'name' => 'infobip_username',
			'type' => 'email',
			'value' => $settings->infobip_username,
			'maxlength' => 50
		]
	],
	[
		'label' => __('forms.password'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'infobip_password',
			'name' => 'infobip_password',
			'type' => 'text',
			'value' => $settings->infobip_password,
			'maxlength' => 50
		]
	]
];

$fields_nth = [
	[
		'label' => __('settings.incoming-sms-webhook'),
		'tag' => 'input',
		'group' => [
			'left' => 'POST',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#nth_incoming_webhook" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'nth_incoming_webhook',
			'type' => 'url',
			'value' => route('api.webhooks.sms.nth.incoming'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.from-number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'nth_from_number',
			'name' => 'nth_from_number',
			'type' => 'tel',
			'value' => $settings->nth_from_number,
			'maxlength' => 20
		]
	],
	[
		'label' => __('settings.api-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'nth_api_key',
			'name' => 'nth_api_key',
			'type' => 'text',
			'value' => $settings->nth_api_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.api-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'nth_api_secret',
			'name' => 'nth_api_secret',
			'type' => 'text',
			'value' => $settings->nth_api_secret,
			'maxlength' => 50
		]
	],
];

$fields_elks = [
	[
		'label' => __('settings.incoming-sms-webhook'),
		'tag' => 'input',
		'group' => [
			'left' => 'POST',
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#elks_incoming_webhook" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'elks_incoming_webhook',
			'type' => 'url',
			'value' => route('api.webhooks.sms.elks.incoming'),
			'readonly' => true
		]
	],
	[
		'label' => __('settings.from-number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'elks_from_number',
			'name' => 'elks_from_number',
			'type' => 'tel',
			'value' => $settings->elks_from_number,
			'maxlength' => 20
		]
	],
	[
		'label' => __('settings.api-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'elks_api_key',
			'name' => 'elks_api_key',
			'type' => 'text',
			'value' => $settings->elks_api_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.api-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'elks_api_secret',
			'name' => 'elks_api_secret',
			'type' => 'text',
			'value' => $settings->elks_api_secret,
			'maxlength' => 50
		]
	],
];

$fields_erp = [
	[
		'label' => __('settings.erp-client-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'erp_client_id',
			'name' => 'erp_client_id',
			'type' => 'text',
			'value' => $settings->erp_client_id,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.erp-client-secret'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'erp_client_secret',
			'name' => 'erp_client_secret',
			'type' => 'text',
			'value' => $settings->erp_client_secret,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.erp-api-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'erp_api_key',
			'name' => 'erp_api_key',
			'type' => 'text',
			'value' => $settings->erp_api_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.erp-base-url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'erp_base_url',
			'name' => 'erp_base_url',
			'type' => 'text',
			'value' => $settings->erp_base_url,
			'maxlength' => 100
		]
	],
];

$fields_money_and_currency = [
	[
		'label' => __('settings.pdv-default'),
		'tag' => 'input',
		'group' => [
			'right' => '%'
		],
		'attributes' => [
			'id' => 'pdv_default',
			'name' => 'pdv_default',
			'type' => 'number',
			'value' => $settings->pdv_default,
			'min' => 1,
			'max' => 100,
			'required' => true
		]
	],
	[
		'label' => __('settings.currency-exchange-rate'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'currency_exchange_rate',
			'name' => 'currency_exchange_rate',
			'type' => 'number',
			'value' => $settings->currency_exchange_rate,
			'step' => '0.01',
			'min' => 0,
			'max' => 100,
			'required' => true
		]
	],
	[
		'label' => __('settings.main-currency'),
		'tag' => 'select',
		'options' => $currencies,
		'selected' => $settings->main_currency,
		'attributes' => [
			'id' => 'main_currency',
			'name' => 'main_currency'
		]
	]

];

$fields_cheese_club = [
	[
		'label' => __('cheese-club.points-on-referral'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'points_on_referral',
			'name' => 'points_on_referral',
			'type' => 'number',
			'value' => $settings->points_on_referral,
			'min' => 0,
		]
	],

	[
		'label' => __('cheese-club.points-on-register'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'points_on_register',
			'name' => 'points_on_register',
			'type' => 'number',
			'value' => $settings->points_on_register,
			'min' => 0,
		]
	],
];

$fields_discounts = [
	[
		'label' => __('orders.final-amount-discount-limit'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'order_final_amount_discount_limit',
			'name' => 'order_final_amount_discount_limit',
			'type' => 'number',
			'value' => $settings->order_final_amount_discount_limit,
			'min' => 200,
		]
	],
];

$fields_delivery = [
	[
		'label' => __('settings.gratis-delivery'),
		'tag' => 'input',
		'group' => [
			'right' => $settings->main_currency == Setting::CURRENCY_KUNA ? __('settings.currency-kuna') : __('settings.currency-euro')
		],
		'attributes' => [
			'id' => 'gratis_delivery',
			'name' => 'gratis_delivery',
			'type' => 'number',
			'value' => $settings->gratis_delivery,
			'min' => 0,
			'max' => 10000,
		]
	],
	[
		'label' => __('settings.gratis-delivery-ino'),
		'tag' => 'input',
		'group' => [
			'right' => $settings->main_currency == Setting::CURRENCY_KUNA ? __('settings.currency-kuna') : __('settings.currency-euro')
		],
		'attributes' => [
			'id' => 'gratis_delivery_ino',
			'name' => 'gratis_delivery_ino',
			'type' => 'number',
			'value' => $settings->gratis_delivery_ino,
			'min' => 0,
			'max' => 10000,
		]
	],

];

$fields_corvus_pay = [
	[
		'label' => __('settings.corvus-version'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'corvus_version',
			'name' => 'corvus_version',
			'type' => 'text',
			'value' => $settings->corvus_version,
			'maxlength' => 10
		]
	],
	[
		'label' => __('settings.corvus-store-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'corvus_store_id',
			'name' => 'corvus_store_id',
			'type' => 'text',
			'value' => $settings->corvus_store_id,
			'maxlength' => 10
		]
	],
	[
		'label' => __('settings.corvus-language'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'corvus_language',
			'name' => 'corvus_language',
			'type' => 'text',
			'value' => $settings->corvus_language,
			'maxlength' => 2
		]
	],
	[
		'label' => __('settings.corvus-currency'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'corvus_currency',
			'name' => 'corvus_currency',
			'type' => 'text',
			'value' => $settings->corvus_currency,
			'maxlength' => 3
		]
	],
	[
		'label' => __('settings.corvus-secret-key'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'corvus_secret_key',
			'name' => 'corvus_secret_key',
			'type' => 'text',
			'value' => $settings->corvus_secret_key,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.corvus-require-complete'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'corvus_require_complete',
			'name' => 'corvus_require_complete',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $settings->corvus_require_complete
		]
	],
	[
		'label' => __('settings.corvus-webhook-success-url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'corvus_success_url',
			'name' => 'corvus_success_url',
			'type' => 'text',
			'value' => $settings->corvus_success_url,
			'maxlength' => 100
		]
	],
	[
		'label' => __('settings.corvus-webhook-cancel-url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'corvus_cancel_url',
			'name' => 'corvus_cancel_url',
			'type' => 'text',
			'value' => $settings->corvus_cancel_url,
			'maxlength' => 100
		]
	],
];

$fields_company_info = [
	[
		'label' => __('settings.iban'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'iban',
			'name' => 'iban',
			'type' => 'text',
			'value' => $settings->iban,
			'maxlength' => 21
		]
	],
	[
		'label' => __('settings.model'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'model',
			'name' => 'model',
			'type' => 'text',
			'value' => $settings->model,
			'maxlength' => 10
		]
	],
	[
		'label' => __('settings.sifra-namjene'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'sifra_namjene',
			'name' => 'sifra_namjene',
			'type' => 'text',
			'value' => $settings->sifra_namjene,
			'maxlength' => 5
		]
	],
	[
		'label' => __('settings.company-name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'company_name',
			'name' => 'company_name',
			'type' => 'text',
			'value' => $settings->company_name,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.company-address'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'company_address',
			'name' => 'company_address',
			'type' => 'text',
			'value' => $settings->company_address,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.company-town'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'company_town',
			'name' => 'company_town',
			'type' => 'text',
			'value' => $settings->company_town,
			'maxlength' => 50
		]
	],
	[
		'label' => __('settings.company-zip-code'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'company_zip_code',
			'name' => 'company_zip_code',
			'type' => 'text',
			'value' => $settings->company_zip_code,
			'maxlength' => 5
		]
	],
	[
		'label' => __('settings.company-additional-address-info'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'company_additional_address_info',
			'name' => 'company_additional_address_info',
			'type' => 'text',
			'value' => $settings->company_additional_address_info,
			'maxlength' => 50
		]
	],

];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('settings.general.title'), 'icon' => 'fa fa-cogs', 'save_and_return' => false])
	<form class="form form-notify" action="{{ route('settings.general.update') }}" method="post" autocomplete="off" id="main-form">
		@csrf
		<div class="card-body">
			<div class="row">
				<div class="col-3">
					<ul class="nav nav-pills flex-column" role="tablist" data-active-tab="tab">
						<li class="nav-item">
							<a href="#btabs-basic" class="nav-link active" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-info"></i></span>
								<span class="nav-text">{{ __('settings.menu-basic') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-app" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-mobile-alt"></i></span>
								<span class="nav-text">{{ __('settings.menu-app') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-mail" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-envelope"></i></span>
								<span class="nav-text">{{ __('settings.menu-mail') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-datetime" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-calendar-alt"></i></span>
								<span class="nav-text">{{ __('settings.menu-datetime') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-registration" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-user-plus"></i></span>
								<span class="nav-text">{{ __('settings.menu-login') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-api" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-user-lock"></i></span>
								<span class="nav-text">API</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-security" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-ban"></i></span>
								<span class="nav-text">{{ __('settings.menu-security') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-monitor" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-clock"></i></span>
								<span class="nav-text">{{ __('settings.menu-monitor') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-media" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-photo-video"></i></span>
								<span class="nav-text">{{ __('settings.menu-media') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-cache" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-database"></i></span>
								<span class="nav-text">{{ __('settings.menu-cache') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-aws" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-database"></i></span>
								<span class="nav-text">AWS</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-onesignal" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-broadcast-tower"></i></span>
								<span class="nav-text">OneSignal</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-telescope" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-search"></i></span>
								<span class="nav-text">Telescope</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-paypal" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fab fa-cc-paypal"></i></span>
								<span class="nav-text">PayPal</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-corvuspay" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fab fa-amazon-pay"></i></span>
								<span class="nav-text">{{ __('settings.corvus-pay') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-company-info" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fab fa-amazon-pay"></i></span>
								<span class="nav-text">{{ __('settings.company-info') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-webhook-scheduler" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-clock"></i></span>
								<span class="nav-text">{{ __('settings.menu-webhook-scheduler') }}</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-sms" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-comments"></i></span>
								<span class="nav-text">SMS</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-erp" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-comments"></i></span>
								<span class="nav-text">Erp</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-currency-and-vat" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-comments"></i></span>
								<span class="nav-text">Currencies and vat</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-delivery" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-comments"></i></span>
								<span class="nav-text">Delivery</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#btabs-discounts" class="nav-link" data-toggle="tab">
								<span class="nav-icon"><i class="fa fa-comments"></i></span>
								<span class="nav-text">Discounts</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="col-9">
					<div class="tab-content">
						<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
						</div>
						<div class="tab-pane fade" id="btabs-app" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_app])
						</div>
						<div class="tab-pane fade" id="btabs-mail" role="tabpanel">
							@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('settings.mail-info')])
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_mail])
						</div>
						<div class="tab-pane fade" id="btabs-datetime" role="tabpanel">
							@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => 'For help with date/time formats check <strong><a href="https://php.net/manual/en/datetime.format.php" target="_blank">official documentation</a></strong>.'])
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_datetime])
						</div>
						<div class="tab-pane fade" id="btabs-registration" role="tabpanel">
							@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('settings.oauth-note')])
							<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist">
								<li class="nav-item">
									<a href="#btabs-login-sub" class="nav-link active" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-sign-in-alt"></i></span>
										<span class="nav-text">{{ __('settings.login') }}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-registration-sub" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-user-plus"></i></span>
										<span class="nav-text">{{ __('settings.registration') }}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-passwords-sub" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-user-lock"></i></span>
										<span class="nav-text">{{ __('settings.menu-passwords') }}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-bitbucket" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-bitbucket"></i></span>
										<span class="nav-text">Bitbucket</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-github" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-github"></i></span>
										<span class="nav-text">GitHub</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-gitlab" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-gitlab"></i></span>
										<span class="nav-text">GitLab</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-facebook" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-facebook-f"></i></span>
										<span class="nav-text">Facebook</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-twitter" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-twitter"></i></span>
										<span class="nav-text">Twitter</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-google" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-google"></i></span>
										<span class="nav-text">Google</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-linkedin" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-linkedin-in"></i></span>
										<span class="nav-text">LinkedIn</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-apple" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fab fa-apple"></i></span>
										<span class="nav-text">Apple</span>
									</a>
								</li>
							</ul>
							<div class="tab-content mt-5">
								<div class="tab-pane fade show active" id="btabs-login-sub" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_login])
								</div>
								<div class="tab-pane fade" id="btabs-registration-sub" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_registration])
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_cheese_club])
								</div>
								<div class="tab-pane fade" id="btabs-passwords-sub" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_passwords])
								</div>
								<div class="tab-pane fade" id="btabs-bitbucket" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_bitbucket])
								</div>
								<div class="tab-pane fade" id="btabs-github" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_github])
								</div>
								<div class="tab-pane fade" id="btabs-gitlab" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_gitlab])
								</div>
								<div class="tab-pane fade" id="btabs-facebook" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_facebook])
								</div>
								<div class="tab-pane fade" id="btabs-twitter" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_twitter])
								</div>
								<div class="tab-pane fade" id="btabs-google" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_google])
								</div>
								<div class="tab-pane fade" id="btabs-linkedin" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_linkedin])
								</div>
								<div class="tab-pane fade" id="btabs-apple" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_apple])
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="btabs-api" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_api])
						</div>
						<div class="tab-pane fade" id="btabs-security" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_security])
						</div>
						<div class="tab-pane fade" id="btabs-monitor" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_monitor])
						</div>
						<div class="tab-pane fade" id="btabs-media" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_media])
						</div>
						<div class="tab-pane fade" id="btabs-cache" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_cache])
						</div>
						<div class="tab-pane fade" id="btabs-aws" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_aws])
						</div>
						<div class="tab-pane fade" id="btabs-onesignal" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_onesignal])
						</div>
						<div class="tab-pane fade" id="btabs-telescope" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_telescope])
						</div>
						<div class="tab-pane fade" id="btabs-paypal" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_paypal])
						</div>
						<div class="tab-pane fade" id="btabs-corvuspay" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_corvus_pay])
						</div>
						<div class="tab-pane fade" id="btabs-company-info" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_company_info])
						</div>
						<div class="tab-pane fade" id="btabs-webhook-scheduler" role="tabpanel">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_webhook_scheduler])
						</div>
						<div class="tab-pane fade" id="btabs-sms" role="tabpanel">
							<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist">
								<li class="nav-item">
									<a href="#btabs-sms-basic" class="nav-link active" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-info"></i></span>
										<span class="nav-text">{{ __('settings.menu-basic') }}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-vonage" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-comments"></i></span>
										<span class="nav-text">Vonage</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-twilio" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-comments"></i></span>
										<span class="nav-text">Twilio</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-infobip" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-comments"></i></span>
										<span class="nav-text">InfoBip</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-nth" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-comments"></i></span>
										<span class="nav-text">NTH</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#btabs-elks" class="nav-link" data-toggle="tab">
										<span class="nav-icon"><i class="fa fa-comments"></i></span>
										<span class="nav-text">46elks</span>
									</a>
								</li>
							</ul>
							<div class="tab-content mt-5">
								<div class="tab-pane fade show active" id="btabs-sms-basic" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_sms_basic])
								</div>
								<div class="tab-pane fade show" id="btabs-vonage" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_vonage])
								</div>
								<div class="tab-pane fade" id="btabs-twilio" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_twilio])
								</div>
								<div class="tab-pane fade" id="btabs-infobip" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_infobip])
								</div>
								<div class="tab-pane fade" id="btabs-nth" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_nth])
								</div>
								<div class="tab-pane fade" id="btabs-elks" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_elks])
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="btabs-erp" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_erp])
						</div>
						<div class="tab-pane fade" id="btabs-currency-and-vat" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_money_and_currency])
						</div>
						<div class="tab-pane fade" id="btabs-delivery" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_delivery])
						</div>
						<div class="tab-pane fade" id="btabs-discounts" role="tabpanel">
									@include('layouts.forms.generate_form_fields', ['fields' => $fields_discounts])
						</div>
					</div>
				</div>
			</div>
		</div>
		@include('layouts.submit_button', ['save_and_return' => false])
	</form>

	@include('layouts.modals.activity')
@endsection
