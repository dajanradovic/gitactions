<?php

use Illuminate\Support\Str;
use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
	public function up(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$isLocal = app()->isLocal();

			$blueprint->add('app_name', config('app.name'));
			$blueprint->add('app_scheme');
			$blueprint->add('ios_min_version');
			$blueprint->add('android_min_version');
			$blueprint->add('ios_maintenance_min_version');
			$blueprint->add('android_maintenance_min_version');
			$blueprint->add('app_description');
			$blueprint->add('maintenance_active', false);
			$blueprint->add('debug_mode_active', $isLocal);
			$blueprint->add('currency_code', 'USD');
			$blueprint->add('google_api_key', 'AIzaSyALIWQWRzkuSQ8SE6qLDOHDZEOycM27_3c');

			$blueprint->add('app_email', 'weare@lloyds-digital.com');
			$blueprint->add('noreply_email', 'noreply@lloyds-design.hr');
			$blueprint->add('smtp_host', 'mail.lloyds-design.hr');
			$blueprint->add('smtp_port', 465);
			$blueprint->add('smtp_protocol', 'ssl');
			$blueprint->add('smtp_username', 'noreply@lloyds-design.hr');
			$blueprint->add('smtp_password', 'x5LV%x1z@,Bp');

			$blueprint->add('timezone', 'UTC');
			$blueprint->add('date_format', 'd/m/Y');
			$blueprint->add('time_format', 'H:i');

			$blueprint->add('min_pass_len', 6);
			$blueprint->add('pass_uppercase_char', false);
			$blueprint->add('pass_numeric_char', false);
			$blueprint->add('pass_special_char', false);

			$blueprint->add('jwt_secret_key', Str::random(32));
			$blueprint->add('jwt_expiration_time', 60 * 24 * 365);

			$blueprint->add('media_storage', $isLocal ? 'public' : 's3');
			$blueprint->add('max_upload_size', 5 * 1024 * 1024);
			$blueprint->add('max_video_length', 30);
			$blueprint->add('thumb_width', 1242);
			$blueprint->add('responsive_images_breakpoints', [400]);
			$blueprint->add('queue_media_conversions', false);
			$blueprint->add('protected_media_token_valid_until', 60);

			$blueprint->add('login_max_attempts', 5);
			$blueprint->add('login_backoff_minutes', 1);
			$blueprint->add('api_rate_limit', 60);
			$blueprint->add('api_rate_limit_backoff_minutes', 1);

			$blueprint->add('cors_allowed_origins');
			$blueprint->add('csp_allowed_scripts', ['maps.googleapis.com', 'amcharts.com']);
			$blueprint->add('csp_allowed_styles', ['fonts.googleapis.com', 'amcharts.com']);

			$blueprint->add('password_reset_timeout', 60);
			$blueprint->add('email_verification_timeout', 60);
			$blueprint->add('registration_active', true);
			$blueprint->add('registration_role_id');
			$blueprint->add('registration_api_role_id');
			$blueprint->add('session_driver', 'database');
			$blueprint->add('session_lifetime', 120);
			$blueprint->add('basic_auth_username_field', 'email');
			$blueprint->add('push_devices_cleanup_days', 30);

			$blueprint->add('aws_access_key_id', 'AKIAID6H5BG32NO4E6LQ');
			$blueprint->add('aws_secret_access_key', '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');
			$blueprint->add('aws_default_region', 'eu-central-1');
			$blueprint->add('aws_bucket_name', 'lloyds-backend');
			$blueprint->add('aws_bucket_url', 'https://lloyds-backend.s3.amazonaws.com');

			$blueprint->add('cache_store', $isLocal ? 'file' : 'redis');
			$blueprint->add('queue_driver', $isLocal ? 'sync' : 'redis');

			$blueprint->add('onesignal_app_id');
			$blueprint->add('onesignal_rest_api_key');
			$blueprint->add('onesignal_user_auth_key');
			$blueprint->add('onesignal_stats_check_days', 1);

			$blueprint->add('telescope_active', false);
			$blueprint->add('telescope_same_ip', true);
			$blueprint->add('telescope_prune_hours', 24);

			$blueprint->add('monitor_active', false);
			$blueprint->add('backup_disks');
			$blueprint->add('backup_keep_days', 0);
			$blueprint->add('monitor_slack_webhook');
			$blueprint->add('monitor_emails');

			$blueprint->add('paypal_sandbox', true);
			$blueprint->add('paypal_client_id');
			$blueprint->add('paypal_client_secret');
			$blueprint->add('paypal_sandbox_client_id');
			$blueprint->add('paypal_sandbox_client_secret');

			$blueprint->add('webhook_scheduler_url');
			$blueprint->add('webhook_scheduler_token');

			$blueprint->add('bitbucket_active', false);
			$blueprint->add('bitbucket_client_id');
			$blueprint->add('bitbucket_client_secret');

			$blueprint->add('github_active', false);
			$blueprint->add('github_client_id');
			$blueprint->add('github_client_secret');

			$blueprint->add('gitlab_active', false);
			$blueprint->add('gitlab_client_id');
			$blueprint->add('gitlab_client_secret');

			$blueprint->add('facebook_active', false);
			$blueprint->add('facebook_client_id');
			$blueprint->add('facebook_client_secret');

			$blueprint->add('twitter_active', false);
			$blueprint->add('twitter_client_id');
			$blueprint->add('twitter_client_secret');

			$blueprint->add('google_active', false);
			$blueprint->add('google_client_id');
			$blueprint->add('google_client_secret');

			$blueprint->add('linkedin_active', false);
			$blueprint->add('linkedin_client_id');
			$blueprint->add('linkedin_client_secret');

			$blueprint->add('apple_active', false);
			$blueprint->add('apple_client_id');
			$blueprint->add('apple_client_secret');

			$blueprint->add('sms_default_reply');

			$blueprint->add('vonage_from_number');
			$blueprint->add('vonage_api_key');
			$blueprint->add('vonage_api_secret');

			$blueprint->add('twilio_from_number');
			$blueprint->add('twilio_api_key');
			$blueprint->add('twilio_api_secret');

			$blueprint->add('infobip_from_number');
			$blueprint->add('infobip_api_subdomain');
			$blueprint->add('infobip_username');
			$blueprint->add('infobip_password');

			$blueprint->add('nth_from_number');
			$blueprint->add('nth_api_key');
			$blueprint->add('nth_api_secret');

			$blueprint->add('elks_from_number');
			$blueprint->add('elks_api_key');
			$blueprint->add('elks_api_secret');
		});
	}
};
