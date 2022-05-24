@extends('layouts.public', ['seo' => false])

@section('title', __('auth.verification.title'))

@section('content')
	<div class="login login-2 login-signin-on d-flex flex-row-fluid">
		<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url({{ asset('img/login.jpg') }});">
			<div class="login-form text-center p-7 position-relative overflow-hidden">
				<!--begin::Login Header-->
				<div class="d-flex flex-center mb-5">
					<a href="{{ route('home') }}">
						<img src="{{ asset('img/logo-black.png') }}" class="max-h-100px" alt="Logo">
					</a>
				</div>
				<!--end::Login Header-->

				<!--begin::Body-->
				<div class="login-signin">
					<div class="mb-10">
						<h3>{{ __('auth.verification.title') }}</h3>
						<div class="text-muted font-weight-bold">
							<small>{{ __('global.powered-by') }} <a href="{{ config('custom.dev_url') }}" rel="author" target="_blank">{{ config('custom.dev_name') }}</a></small>
						</div>
					</div>

					<div class="card-body">
						@if(session('resent'))
							@include('layouts.alert', ['icon' => 'fa fa-envelope', 'state' => 'success', 'text' => __('auth.verification.fresh-link')])
						@else
							@include('layouts.alert', ['icon' => 'fa fa-envelope', 'state' => 'warning', 'text' => __('auth.verification.before-proceeding')])
						@endif

						<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
							<form method="post" action="{{ route('logout') }}">
								@csrf

								<button type="submit" class="btn btn-secondary btn-elevate">
									<i class="fa fa-chevron-left"></i> {{ __('global.back') }}
								</button>
							</form>

							<form method="post" action="{{ route('verification.send') }}">
								@csrf

								<button type="submit" class="btn btn-success btn-elevate">
									<i class="fa fa-envelope"></i> {{ __('auth.verification.request-another') }}
								</button>
							</form>
						</div>
					</div>
				</div>
				<!--end::Body-->
			</div>
		</div>
	</div>
@endsection