<!--begin::Options-->
<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
	@if(setting('facebook_active'))
		<a href="{{ route('login.oauth.redirect', 'facebook') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} Facebook" class="btn btn-outline-primary btn-icon">
			<i class="fab fa-facebook-f"></i>
		</a>
	@endif

	@if(setting('google_active'))
		<a href="{{ route('login.oauth.redirect', 'google') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} Google" class="btn btn-outline-danger btn-icon">
			<i class="fab fa-google"></i>
		</a>
	@endif

	@if(setting('twitter_active'))
		<a href="{{ route('login.oauth.redirect', 'twitter') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} Twitter" class="btn btn-outline-primary btn-icon">
			<i class="fab fa-twitter"></i>
		</a>
	@endif

	@if(setting('linkedin_active'))
		<a href="{{ route('login.oauth.redirect', 'linkedin') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} LinkedIn" class="btn btn-outline-primary btn-icon">
			<i class="fab fa-linkedin-in"></i>
		</a>
	@endif

	@if(setting('github_active'))
		<a href="{{ route('login.oauth.redirect', 'github') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} GitHub" class="btn btn-outline-dark btn-icon">
			<i class="fab fa-github"></i>
		</a>
	@endif

	@if(setting('gitlab_active'))
		<a href="{{ route('login.oauth.redirect', 'gitlab') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} GitLab" class="btn btn-outline-warning btn-icon">
			<i class="fab fa-gitlab"></i>
		</a>
	@endif

	@if(setting('bitbucket_active'))
		<a href="{{ route('login.oauth.redirect', 'bitbucket') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} Bitbucket" class="btn btn-outline-primary btn-icon">
			<i class="fab fa-bitbucket"></i>
		</a>
	@endif

	@if(setting('apple_active'))
		<a href="{{ route('login.oauth.redirect', 'apple') }}" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ __('auth.login.login-with') }} Apple" class="btn btn-outline-dark btn-icon">
			<i class="fab fa-apple"></i>
		</a>
	@endif
</div>
<!--end::Options-->