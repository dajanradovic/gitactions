<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\Setting;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralSettings extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$caches = array_keys(config('cache.stores'));
		$queues = array_keys(config('queue.connections'));
		$filesystems = array_keys(config('filesystems.disks'));
		$sessions = ['file', 'cookie', 'database', 'apc', 'memcached', 'redis', 'dynamodb', 'array'];

		return [
			'app_name' => ['required', 'string', 'max:50'],
			'app_scheme' => ['nullable', 'string', 'max:50'],
			'ios_min_version' => ['nullable', 'integer', 'min:1'],
			'android_min_version' => ['nullable', 'integer', 'min:1'],
			'app_description' => ['nullable', 'string', 'max:500'],
			'maintenance_active' => ['boolean'],
			'debug_mode_active' => ['boolean'],
			'currency_code' => ['required', 'string', 'size:3'],
			'google_api_key' => ['nullable', 'string', 'max:50'],

			'app_email' => ['nullable', 'email', 'max:50'],
			'noreply_email' => ['nullable', 'email', 'max:50'],
			'smtp_host' => ['nullable', 'string', 'max:100'],
			'smtp_port' => ['nullable', 'integer', 'min:1'],
			'smtp_username' => ['nullable', 'string', 'max:50'],
			'smtp_password' => ['nullable', 'string', 'max:50'],
			'smtp_protocol' => ['nullable', 'string', 'in:tls,ssl'],

			'timezone' => ['required', 'timezone'],
			'date_format' => ['required', 'string', 'max:15'],
			'time_format' => ['required', 'string', 'max:15'],

			'min_pass_len' => ['required', 'integer', 'min:1'],
			'pass_uppercase_char' => ['boolean'],
			'pass_numeric_char' => ['boolean'],
			'pass_special_char' => ['boolean'],

			'jwt_regenerate_secret_key' => ['boolean'],
			'jwt_expiration_time' => ['nullable', 'integer', 'min:1'],

			'media_storage' => ['required', 'string', Rule::in($filesystems)],
			'max_upload_size' => ['required', 'integer', 'min:1'],
			'max_video_length' => ['required', 'integer', 'min:1'],
			'thumb_width' => ['required', 'integer', 'min:1'],
			'responsive_images_breakpoints' => ['array'],
			'responsive_images_breakpoints.*' => ['required', 'integer', 'min:1'],
			'queue_media_conversions' => ['boolean'],
			'protected_media_token_valid_until' => ['required', 'integer', 'min:1'],

			'login_max_attempts' => ['required', 'integer', 'min:1'],
			'login_backoff_minutes' => ['required', 'integer', 'min:1'],
			'api_rate_limit' => ['required', 'integer', 'min:1'],
			'api_rate_limit_backoff_minutes' => ['required', 'integer', 'min:1'],

			'cors_allowed_origins' => ['array'],
			'cors_allowed_origins.*' => ['required', 'string', 'max:50'],
			'csp_allowed_scripts' => ['array'],
			'csp_allowed_scripts.*' => ['required', 'string', 'max:50'],
			'csp_allowed_styles' => ['array'],
			'csp_allowed_styles.*' => ['required', 'string', 'max:50'],

			'password_reset_timeout' => ['required', 'integer', 'min:1'],
			'email_verification_timeout' => ['required', 'integer', 'min:1'],

			'cache_store' => ['required', 'string', Rule::in($caches)],
			'queue_driver' => ['required', 'string', Rule::in($queues)],

			'registration_active' => ['boolean'],
			'registration_role_id' => ['nullable', 'uuid', 'exists:' . Role::class . ',id'],
			'registration_api_role_id' => ['nullable', 'uuid', 'exists:' . Role::class . ',id'],
			'session_driver' => ['required', 'string', Rule::in($sessions)],
			'session_lifetime' => ['required', 'integer', 'min:1'],
			'basic_auth_username_field' => ['required', 'string', 'in:id,email'],
			'push_devices_cleanup_days' => ['nullable', 'integer', 'min:1'],

			'aws_access_key_id' => ['nullable', 'string', 'max:50'],
			'aws_secret_access_key' => ['nullable', 'string', 'max:50'],
			'aws_default_region' => ['nullable', 'string', 'max:20'],
			'aws_bucket_name' => ['nullable', 'string', 'max:50'],
			'aws_bucket_url' => ['nullable', 'url', 'max:100'],

			'onesignal_app_id' => ['nullable', 'uuid'],
			'onesignal_rest_api_key' => ['nullable', 'required_with:onesignal_app_id', 'string', 'max:64'],
			'onesignal_user_auth_key' => ['nullable', 'required_with:onesignal_app_id', 'string', 'max:64'],
			'onesignal_stats_check_days' => ['nullable', 'integer', 'min:1'],

			'telescope_active' => ['boolean'],
			'telescope_same_ip' => ['boolean'],
			'telescope_prune_hours' => ['nullable', 'integer', 'min:1'],

			'monitor_active' => ['boolean'],
			'backup_disks' => ['array'],
			'backup_disks.*' => ['required', 'string', Rule::in($filesystems)],
			'monitor_slack_webhook' => ['nullable', 'url', 'max:100'],
			'monitor_emails' => ['array'],
			'monitor_emails.*' => ['required', 'email', 'max:50'],
			'backup_keep_days' => ['required', 'integer', 'min:0'],

			'paypal_sandbox' => ['boolean'],
			'paypal_client_id' => ['nullable', 'string', 'max:200'],
			'paypal_client_secret' => ['nullable', 'string', 'max:200'],
			'paypal_sandbox_client_id' => ['nullable', 'string', 'max:200'],
			'paypal_sandbox_client_secret' => ['nullable', 'string', 'max:200'],

			'webhook_scheduler_url' => ['nullable', 'url', 'max:100'],
			'webhook_scheduler_token' => ['nullable', 'string', 'max:500'],

			'bitbucket_active' => ['boolean'],
			'bitbucket_client_id' => ['nullable', 'string', 'max:50'],
			'bitbucket_client_secret' => ['nullable', 'string', 'max:500'],

			'github_active' => ['boolean'],
			'github_client_id' => ['nullable', 'string', 'max:50'],
			'github_client_secret' => ['nullable', 'string', 'max:500'],

			'gitlab_active' => ['boolean'],
			'gitlab_client_id' => ['nullable', 'string', 'max:50'],
			'gitlab_client_secret' => ['nullable', 'string', 'max:500'],

			'facebook_active' => ['boolean'],
			'facebook_client_id' => ['nullable', 'string', 'max:50'],
			'facebook_client_secret' => ['nullable', 'string', 'max:500'],

			'twitter_active' => ['boolean'],
			'twitter_client_id' => ['nullable', 'string', 'max:50'],
			'twitter_client_secret' => ['nullable', 'string', 'max:500'],

			'google_active' => ['boolean'],
			'google_client_id' => ['nullable', 'string', 'max:50'],
			'google_client_secret' => ['nullable', 'string', 'max:500'],

			'linkedin_active' => ['boolean'],
			'linkedin_client_id' => ['nullable', 'string', 'max:50'],
			'linkedin_client_secret' => ['nullable', 'string', 'max:500'],

			'apple_active' => ['boolean'],
			'apple_client_id' => ['nullable', 'string', 'max:50'],
			'apple_client_secret' => ['nullable', 'string', 'max:500'],

			'sms_default_reply' => ['nullable', 'string', 'max:160'],

			'vonage_from_number' => ['nullable', 'string', 'max:20'],
			'vonage_api_key' => ['nullable', 'required_with:vonage_from_number', 'string', 'max:50'],
			'vonage_api_secret' => ['nullable', 'required_with:vonage_from_number', 'string', 'max:50'],

			'twilio_from_number' => ['nullable', 'string', 'max:20'],
			'twilio_api_key' => ['nullable', 'required_with:twilio_from_number', 'string', 'max:50'],
			'twilio_api_secret' => ['nullable', 'required_with:twilio_from_number', 'string', 'max:50'],

			'infobip_from_number' => ['nullable', 'string', 'max:20'],
			'infobip_api_subdomain' => ['nullable', 'required_with:infobip_from_number', 'string', 'max:50'],
			'infobip_username' => ['nullable', 'required_with:infobip_from_number', 'email', 'max:50'],
			'infobip_password' => ['nullable', 'required_with:infobip_from_number', 'string', 'max:50'],

			'nth_from_number' => ['nullable', 'string', 'max:20'],
			'nth_api_key' => ['nullable', 'required_with:nth_from_number', 'string', 'max:50'],
			'nth_api_secret' => ['nullable', 'required_with:nth_from_number', 'string', 'max:50'],

			'elks_from_number' => ['nullable', 'string', 'max:20'],
			'elks_api_key' => ['nullable', 'required_with:elks_from_number', 'string', 'max:50'],
			'elks_api_secret' => ['nullable', 'required_with:elks_from_number', 'string', 'max:50'],

			'erp_client_id' => ['nullable', 'string', 'max:50'],
			'erp_client_secret' => ['nullable', 'string', 'max:50'],
			'erp_api_key' => ['nullable', 'string', 'max:50'],
			'erp_base_url' => ['nullable', 'url', 'max:100'],

			'pdv_default' => ['required', 'numeric', 'min:0', 'max:100'],
			'main_currency' => ['required', 'integer', Rule::in(Setting::currencies())],
			'currency_exchange_rate' => ['required', 'numeric', 'min:0', 'max:100'],

			'points_on_referral' => ['nullable', 'integer', 'min:0'],
			'points_on_register' => ['nullable', 'integer', 'min:0'],

			'gratis_delivery' => ['nullable', 'integer', 'min:0', 'max:10000'],
			'gratis_delivery_ino' => ['nullable', 'integer', 'min:0', 'max:10000'],

			'corvus_version' => ['nullable', 'string', 'max:10'],
			'corvus_store_id' => ['nullable', 'string', 'max:10'],
			'corvus_language' => ['nullable', 'string', 'max:2', Rule::in(['en', 'hr'])],
			'corvus_currency' => ['nullable', 'string', 'max:3', Rule::in(['HRK'])],
			'corvus_secret_key' => ['nullable', 'string', 'max:50'],
			'corvus_require_complete' => ['boolean'],
			'corvus_success_url' => ['nullable', 'string', 'max:100'],
			'corvus_cancel_url' => ['nullable', 'string', 'max:100'],

			'iban' => ['nullable', 'string', 'size:21'],
			'model' => ['nullable', 'string', 'max:10'],
			'sifra_namjene' => ['nullable', 'string', 'max:5'],
			'company_name' => ['nullable', 'string', 'max:50'],
			'company_address' => ['nullable', 'string', 'max:50'],
			'company_town' => ['nullable', 'string', 'max:30'],
			'company_zip_code' => ['nullable', 'string', 'size:5'],
			'company_additional_address_info' => ['nullable', 'string', 'max:50'],

			'order_final_amount_discount_limit' => ['nullable', 'integer', 'min:200']

		];
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'cors_allowed_origins' => $this->cors_allowed_origins ? preg_split('/\s+/', $this->cors_allowed_origins) : [],
			'csp_allowed_scripts' => $this->csp_allowed_scripts ? preg_split('/\s+/', $this->csp_allowed_scripts) : [],
			'csp_allowed_styles' => $this->csp_allowed_styles ? preg_split('/\s+/', $this->csp_allowed_styles) : [],
			'monitor_emails' => $this->monitor_emails ? preg_split('/\s+/', $this->monitor_emails) : [],
			'responsive_images_breakpoints' => $this->responsive_images_breakpoints ? preg_split('/\s+/', $this->responsive_images_breakpoints) : [],
		]);
	}
}
