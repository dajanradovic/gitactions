<?php

namespace App\Services;

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Schema;

class Config
{
	public static function bindWithSettings(): Repository
	{
		$config = config();

		if (app()->runningInConsole() && !Schema::hasTable('settings') || !($appName = setting('app_name'))) {
			return $config;
		}

		$isProduction = app()->isProduction();

		$config->set([
			'app.name' => $appName,
			'app.debug' => setting('debug_mode_active'),

			'session.driver' => setting('session_driver'),
			'session.lifetime' => setting('session_lifetime'),

			'auth.passwords.users.expire' => setting('password_reset_timeout'),
			'auth.passwords.users.throttle' => setting('api_rate_limit_backoff_minutes') * 60,
			'auth.verification.expire' => setting('email_verification_timeout'),

			'cors.allowed_origins' => setting('cors_allowed_origins', ['*']),

			'cache.default' => setting('cache_store'),
			'queue.default' => setting('queue_driver'),

			'telescope.enabled' => setting('telescope_active'),
			'octane.https' => $isProduction,
			'settings.cache.enabled' => $isProduction,
			'clamav.skip_validation' => !$isProduction,

			'mail.mailers.smtp.host' => setting('smtp_host'),
			'mail.mailers.smtp.port' => setting('smtp_port'),
			'mail.mailers.smtp.encryption' => setting('smtp_protocol'),
			'mail.mailers.smtp.username' => setting('smtp_username'),
			'mail.mailers.smtp.password' => setting('smtp_password'),
			'mail.from.address' => setting('noreply_email'),
			'mail.from.name' => $appName,

			'logging.channels.stack.channels' => $isProduction && setting('monitor_slack_webhook') ? ['single', 'slack'] : ['single'],
			'logging.channels.slack.url' => setting('monitor_slack_webhook', ''),
			'logging.channels.slack.username' => $appName,

			'uptime-monitor.notifications.slack.webhook_url' => setting('monitor_slack_webhook', ''),
			'uptime-monitor.notifications.mail.to' => setting('monitor_emails', [setting('app_email')]),
			'uptime-monitor.notifications.date_format' => setting('date_format'),

			'backup.backup.name' => $appName,
			'backup.backup.destination.disks' => setting('backup_disks', [setting('media_storage')]),
			'backup.notifications.mail.to' => setting('monitor_emails', [setting('app_email')]),
			'backup.notifications.mail.from.address' => setting('noreply_email'),
			'backup.notifications.mail.from.name' => $appName,
			'backup.notifications.slack.webhook_url' => setting('monitor_slack_webhook', ''),
			'backup.notifications.slack.username' => $appName,
			'backup.cleanup.default_strategy.keep_all_backups_for_days' => setting('backup_keep_days'),

			'filesystems.default' => setting('media_storage'),
			'filesystems.disks.s3.key' => setting('aws_access_key_id'),
			'filesystems.disks.s3.secret' => setting('aws_secret_access_key'),
			'filesystems.disks.s3.region' => setting('aws_default_region'),
			'filesystems.disks.s3.bucket' => setting('aws_bucket_name'),
			'filesystems.disks.s3.url' => setting('aws_bucket_url'),
		]);

		return $config;
	}
}
