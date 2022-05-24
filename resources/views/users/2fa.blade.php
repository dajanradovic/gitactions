@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('auth.2fa.title') . ' > ' . $user->name, 'icon' => 'fa fa-user-lock', 'updated_at' => $user->updated_at])
	<div class="card-body">
		<div class="form-group">
			<label>{{ __('users.title-s') }}</label>
			<p class="form-control-plaintext">
				<a href="{{ route('users.edit', $user->user->id) }}">{{ $user->name }}</a>
			</p>
		</div>

		@if($user->hasEnabledTwoFactorAuthentication())
			<div class="form-group">
				<label>{{ __('users.f2a-qr') }}</label>
				<p class="form-control-plaintext">
					<a href="{{ $user->twoFactorQrCodeUrl() }}">
						{!! $user->twoFactorQrCodeSvg() !!}
					</a>
				</p>
			</div>

			<div class="form-group">
				<label>{{ __('users.f2a-recovery-codes') }} <a href="#" data-clipboard="true" data-clipboard-target="#recovery-codes" title="{{ __('global.copy') }}"><i class="la la-copy"></i></a></label>
				<p class="form-control-plaintext">
					<ul id="recovery-codes">
						@foreach($user->recoveryCodes() as $code)
							<li>{{ $code }}</li>
						@endforeach
					</ul>
				</p>
			</div>
		@endif
	</div>

	<div class="card-footer">
		@if($user->hasEnabledTwoFactorAuthentication())
			<div class="form-group d-flex">
				<form class="form form-notify" method="post" action="{{ route('two-factor.disable') }}">
					@csrf
					@method('DELETE')

					<button type="submit" class="btn btn-danger">
						<i class="la la-toggle-off"></i> {{ __('global.disable') }}
					</button>
				</form>

				<form class="form form-notify" method="post" action="{{ url('user/two-factor-recovery-codes') }}">
					@csrf

					<button type="submit" class="btn btn-warning ml-1">
						<i class="la la-refresh"></i> {{ __('users.2fa-regenerate') }}
					</button>
				</form>
			</div>
		@else
			<form class="form form-notify" method="post" action="{{ route('two-factor.enable') }}">
				@csrf

				<button type="submit" class="btn btn-success">
					<i class="la la-toggle-on"></i> {{ __('global.enable') }}
				</button>
			</form>
		@endif
	</div>
@endsection