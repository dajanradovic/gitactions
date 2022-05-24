<?php

namespace App\Services\Csp\Policies;

use Spatie\Csp\Scheme;
use Spatie\Csp\Keyword;
use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Policy as BasePolicy;

class Policy extends BasePolicy
{
	public function configure(): void
	{
		$scripts = setting('csp_allowed_scripts', []);
		$styles = setting('csp_allowed_styles', []);

		if (app()->isLocal() && config('vite.configs.default.dev_server.enabled')) {
			$scripts[] = $styles[] = str_replace('http://', '', config('vite.configs.default.dev_server.url'));
		} else {
			$this->addDirective(Directive::CONNECT, Keyword::SELF);
		}

		$this->addDirective(Directive::BASE, Keyword::SELF)
			->addDirective(Directive::FORM_ACTION, Keyword::SELF)
			->addDirective(Directive::FORM_ACTION, [Keyword::SELF, 'test-wallet.corvuspay.com'])
			->addDirective(Directive::SCRIPT, [Keyword::SELF, 'https://www.paypal.com/sdk/js'])
			->addDirective(Directive::FRAME, [Keyword::SELF, '	https://www.sandbox.paypal.com/'])
			->addDirective(Directive::OBJECT, Keyword::NONE)
			->addDirective(Directive::FRAME, [Keyword::SELF, 'google.com'])
			->addDirective(Directive::FONT, [Keyword::SELF, Scheme::DATA, 'fonts.gstatic.com'])
			->addDirective(Directive::SCRIPT, [Keyword::SELF, Keyword::UNSAFE_INLINE, Keyword::UNSAFE_EVAL, ...$scripts])
			->addDirective(Directive::STYLE, [Keyword::SELF, Keyword::UNSAFE_INLINE, ...$styles]);
	}
}
