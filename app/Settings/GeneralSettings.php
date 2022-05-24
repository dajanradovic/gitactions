<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
	public string $app_name = '';
	public ?string $app_scheme = null;
	public ?string $ios_min_version = null;
	public ?string $android_min_version = null;
	public ?string $ios_maintenance_min_version = null;
	public ?string $android_maintenance_min_version = null;
	public ?string $app_description = null;
	public bool $maintenance_active = false;
	public bool $debug_mode_active = false;
	public string $currency_code = '';
	public ?string $google_api_key = null;

	public ?string $app_email = null;
	public ?string $noreply_email = null;
	public ?string $smtp_host = null;
	public ?int $smtp_port = null;
	public ?string $smtp_protocol = null;
	public ?string $smtp_username = null;
	public ?string $smtp_password = null;

	public string $timezone = '';
	public string $date_format = '';
	public string $time_format = '';

	public int $min_pass_len = 0;
	public bool $pass_uppercase_char = false;
	public bool $pass_numeric_char = false;
	public bool $pass_special_char = false;

	public string $jwt_secret_key = '';
	public ?int $jwt_expiration_time = null;

	public string $media_storage = '';
	public int $max_upload_size = 0;
	public int $max_video_length = 0;
	public int $thumb_width = 0;
	public ?array $responsive_images_breakpoints = null;
	public bool $queue_media_conversions = false;
	public int $protected_media_token_valid_until = 0;

	public int $login_max_attempts = 0;
	public int $login_backoff_minutes = 0;
	public int $api_rate_limit = 0;
	public int $api_rate_limit_backoff_minutes = 0;

	public ?array $cors_allowed_origins = null;
	public ?array $csp_allowed_scripts = null;
	public ?array $csp_allowed_styles = null;

	public int $password_reset_timeout = 0;
	public int $email_verification_timeout = 0;
	public bool $registration_active = false;
	public ?string $registration_role_id = null;
	public ?string $registration_api_role_id = null;
	public string $session_driver = '';
	public int $session_lifetime = 0;
	public string $basic_auth_username_field = '';
	public ?int $push_devices_cleanup_days = null;

	public ?string $aws_access_key_id = null;
	public ?string $aws_secret_access_key = null;
	public ?string $aws_default_region = null;
	public ?string $aws_bucket_name = null;
	public ?string $aws_bucket_url = null;

	public string $cache_store = '';
	public string $queue_driver = '';

	public ?string $onesignal_app_id = null;
	public ?string $onesignal_rest_api_key = null;
	public ?string $onesignal_user_auth_key = null;
	public ?int $onesignal_stats_check_days = null;

	public bool $telescope_active = false;
	public bool $telescope_same_ip = false;
	public ?int $telescope_prune_hours = null;

	public bool $monitor_active = false;
	public ?array $backup_disks = null;
	public int $backup_keep_days = 0;
	public ?string $monitor_slack_webhook = null;
	public ?array $monitor_emails = null;

	public bool $paypal_sandbox = false;
	public ?string $paypal_client_id = null;
	public ?string $paypal_client_secret = null;
	public ?string $paypal_sandbox_client_id = null;
	public ?string $paypal_sandbox_client_secret = null;

	public ?string $webhook_scheduler_url = null;
	public ?string $webhook_scheduler_token = null;

	public bool $bitbucket_active = false;
	public ?string $bitbucket_client_id = null;
	public ?string $bitbucket_client_secret = null;

	public bool $github_active = false;
	public ?string $github_client_id = null;
	public ?string $github_client_secret = null;

	public bool $gitlab_active = false;
	public ?string $gitlab_client_id = null;
	public ?string $gitlab_client_secret = null;

	public bool $facebook_active = false;
	public ?string $facebook_client_id = null;
	public ?string $facebook_client_secret = null;

	public bool $twitter_active = false;
	public ?string $twitter_client_id = null;
	public ?string $twitter_client_secret = null;

	public bool $google_active = false;
	public ?string $google_client_id = null;
	public ?string $google_client_secret = null;

	public bool $linkedin_active = false;
	public ?string $linkedin_client_id = null;
	public ?string $linkedin_client_secret = null;

	public bool $apple_active = false;
	public ?string $apple_client_id = null;
	public ?string $apple_client_secret = null;

	public ?string $sms_default_reply = null;

	public ?string $vonage_from_number = null;
	public ?string $vonage_api_key = null;
	public ?string $vonage_api_secret = null;

	public ?string $twilio_from_number = null;
	public ?string $twilio_api_key = null;
	public ?string $twilio_api_secret = null;

	public ?string $infobip_from_number = null;
	public ?string $infobip_api_subdomain = null;
	public ?string $infobip_username = null;
	public ?string $infobip_password = null;

	public ?string $nth_from_number = null;
	public ?string $nth_api_key = null;
	public ?string $nth_api_secret = null;

	public ?string $elks_from_number = null;
	public ?string $elks_api_key = null;
	public ?string $elks_api_secret = null;

	public ?string $erp_client_id = null;
	public ?string $erp_client_secret = null;
	public ?string $erp_api_key = null;
	public ?string $erp_base_url = null;

	public ?float $pdv_default = null;
	public ?int $main_currency = null;
	public ?float $currency_exchange_rate = null;

	public ?int $points_on_referral = null;
	public ?int $points_on_register = null;

	public ?int $gratis_delivery = null;
	public ?int $gratis_delivery_ino = null;

	public ?string $corvus_version = null;
	public ?string $corvus_store_id = null;
	public ?string $corvus_language = null;
	public ?string $corvus_currency = null;
	public ?string $corvus_secret_key = null;
	public bool $corvus_require_complete = false;
	public ?string $corvus_success_url = null;
	public ?string $corvus_cancel_url = null;

	public ?string $iban = null;
	public ?string $model = null;
	public ?string $sifra_namjene = null;
	public ?string $company_name = null;
	public ?string $company_address = null;
	public ?string $company_town = null;
	public ?string $company_zip_code = null;
	public ?string $company_additional_address_info = null;

	public ?string $order_final_amount_discount_limit = null;

	public static function group(): string
	{
		return 'general';
	}
}
