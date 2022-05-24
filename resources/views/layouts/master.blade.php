<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		@include('layouts.header')

		<link rel="stylesheet" type="text/css" href="{{ asset('metronic/css/themes/layout/header/base/light.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('metronic/css/themes/layout/header/menu/light.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('metronic/css/themes/layout/brand/dark.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('metronic/css/themes/layout/aside/dark.css') }}">

		<link rel="stylesheet" type="text/css" href="{{ asset('metronic/plugins/custom/datatables/datatables.bundle.css') }}">
		<script defer src="{{ asset('metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>

		@stack('scripts')
	</head>
	<body class="header-fixed header-mobile-fixed aside-enabled aside-fixed aside-minimize-hoverable">
		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
			<a href="{{ route('home') }}">
				<img alt="Logo" loading="lazy" decoding="async" src="{{ asset('img/logo.png') }}" width="150">
			</a>

			<div class="d-flex align-items-center">
				<button type="button" class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
					<span></span>
				</button>

				<button type="button" class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle">
					<span></span>
				</button>

				<button type="button" class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
					<i class="flaticon-more"></i>
				</button>
			</div>
		</div>
		<!-- end:: Header Mobile -->

		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-row flex-column-fluid page">
				<!-- begin:: Aside -->
				<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
					<!--begin::Brand-->
					<div class="brand flex-column-auto" id="kt_brand">
						<a href="{{ route('home') }}" class="brand-logo">
							<img alt="Logo" loading="lazy" decoding="async" src="{{ asset('img/logo.png') }}" width="150">
						</a>

						<button type="button" class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
							<span class="svg-icon svg-icon-xl">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<polygon points="0 0 24 0 24 24 0 24"/>
										<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "/>
										<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "/>
									</g>
								</svg>
							</span>
						</button>
					</div>
					<!--end::Brand-->

					<!-- begin:: Aside Menu -->
					<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
						<div id="kt_aside_menu" class="aside-menu my-4" data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
							@include('layouts.navigation.main')
						</div>
					</div>
					<!-- end:: Aside Menu -->
				</div>
				<!-- end:: Aside -->

				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
					<!-- begin:: Header -->
					<div id="kt_header" class="header header-fixed">

						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-stretch justify-content-between">
							<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
								<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
									@include('layouts.navigation.header')
								</div>
							</div>

							<div class="topbar">
								@if(setting('maintenance_active'))
									<div class="topbar-item">
										<button type="button" title="{{ __('global.under-maintenance') }}" data-container="body" data-toggle="tooltip" data-placement="bottom" class="btn btn-icon btn-light-danger pulse pulse-danger mr-5">
											<i class="la la-warning"></i>
											<span class="pulse-ring"></span>
										</button>
									</div>
								@endif

								<?php

								$user = auth()->user();
								$locales = config('custom.locales');

								if (!empty($locales) && count($locales) > 1) {
									sort($locales);
									$curr_locale = app()->getLocale(); ?>
									<div class="dropdown">
										<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
											<div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
												<img class="h-20px w-20px rounded-sm" loading="lazy" decoding="async" src="{{ asset('img/flags/'.$curr_locale.'.svg') }}" alt="{{ strtoupper($curr_locale) }}">
											</div>
										</div>
										<div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
											<ul class="navi navi-hover py-4">
												@foreach($locales as $locale)
													<li class="navi-item">
														<a href="{{ route('change-locale', ['code' => $locale]) }}" class="navi-link">
															<span class="symbol symbol-20 mr-3">
																<img loading="lazy" decoding="async" src="{{ asset('img/flags/'.$locale.'.svg') }}" alt="{{ strtoupper($locale) }}">
															</span>
															<span class="navi-text">{{ strtoupper($locale) }}</span>
														</a>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
									<?php
								}

								?>

								<!--begin::User-->
								<div class="topbar-item">
									<div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
										<span class="text-muted font-weight-bold font-size-base d-md-inline mr-1">{{ __('global.hi') }},</span>
										<span class="text-dark-50 font-weight-bolder font-size-base d-md-inline mr-3">{{ $user->name }}</span>
										@if($user->getAvatar())
											<div class="symbol symbol-35">
												<img loading="lazy" decoding="async" src="{{ $user->getAvatar() }}">
											</div>
										@else
											<span class="symbol symbol-35 symbol-light-success">
												<span class="symbol-label font-size-h5 font-weight-bold">{{ strtoupper($user->name[0]) }}</span>
											</span>
										@endif
									</div>
								</div>
								<!--end::User-->
							</div>
						</div>
						<!--end::Container-->
					</div>
					<!-- end:: Header -->

					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<div class="d-flex flex-column-fluid">
							<div class="container-fluid">
								<div class="card card-custom card-sticky" id="kt_page_sticky_card">
									@yield('content')
								</div>
							</div>
						</div>
					</div>
					<!-- end:: Content -->

					<!-- begin:: Footer -->
					<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
							<div class="text-dark order-2 order-md-1">
								<span class="text-muted font-weight-bold mr-2">{{ __('global.powered-by') }}</span>
								<a href="{{ config('custom.dev_url') }}" class="text-dark-75 text-hover-primary" target="_blank" rel="author">{{ config('custom.dev_name') }}</a>
							</div>
							@if(count($errors))
								<a href="javascript:;" title="{{ __('global.errors') }}" class="display-errors order-2 order-md-1" data-container="body" data-toggle="tooltip" data-placement="top">
									<span class="label label-danger label-md">{{ count($errors) }}</span>
								</a>
								<span id="error-messages" hidden>@json($errors->all())</span>
							@endif
						</div>
					</div>
					<!-- end:: Footer -->
				</div>

			</div>
		</div>

		<!-- begin::User Panel-->
		<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
			<!--begin::Header-->
			<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
				<h3 class="font-weight-bold m-0">{{ __('global.user-profile') }}</h3>
				<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close" title="{{ __('global.close') }}">
					<i class="ki ki-close icon-xs text-muted"></i>
				</a>
			</div>
			<!--end::Header-->

			<!--begin::Content-->
			<div class="offcanvas-content pr-5 mr-n5">
				<!--begin::Header-->
				<div class="d-flex align-items-center mt-5">
					<a href="{{ $user->user->profileRoute() }}">
						@if($user->getAvatar())
							<div class="symbol symbol-100 mr-5">
								<div class="symbol-label" style="background-image:url('{{ $user->getAvatar() }}');"></div>
								<i class="symbol-badge bg-success"></i>
							</div>
						@else
							<span class="symbol symbol-100 symbol-light-success mr-5">
								<span class="symbol-label font-size-h1 font-weight-bold">{{ strtoupper($user->name[0]) }}</span>
								<i class="symbol-badge bg-success"></i>
							</span>
						@endif
					</a>
					<div class="d-flex flex-column">
						<a href="{{ $user->user->profileRoute() }}" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $user->name }}</a>
						<div class="navi mt-2">
							<a href="mailto:{{ $user->email }}" class="navi-item">
								<span class="navi-link p-0 pb-2">
									<span class="navi-icon mr-1">
										<i class="fa fa-envelope"></i>
									</span>
									<span class="navi-text text-muted text-hover-primary">{{ $user->email }}</span>
								</span>
							</a>

							<button type="submit" form="logout-form" class="btn btn-sm btn-light-danger font-weight-bolder py-2 px-5">
								<i class="fa fa-sign-out-alt"></i> {{ __('global.logout') }}
							</button>
						</div>
					</div>
				</div>
				<!--end::Header-->

				<!--begin::Separator-->
				<div class="separator separator-solid mt-8 mb-5"></div>
				<!--end::Separator-->

				<!--begin::Nav-->
				<div class="navi navi-hover navi-spacer-x-0 p-0">
					<!--begin::Item-->
					<a href="{{ $user->user->profileRoute() }}" class="navi-item">
						<div class="navi-link">
							<div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<i class="fa fa-user-edit text-success"></i>
								</div>
							</div>
							<div class="navi-text">
								<div class="font-weight-bold">{{ __('global.profile') }}</div>
								<div class="text-muted">{{ __('global.personal-info') }}</div>
							</div>
							<span class="navi-arrow"></span>
						</div>
					</a>
					<!--end:Item-->

					@if($user->canViewRoute('invalidate-sessions'))
						<!--begin::Item-->
						<a href="javascript:;" class="navi-item" data-toggle="modal" data-target="#invalidate-sessions-modal">
							<div class="navi-link">
								<div class="symbol symbol-40 bg-light mr-3">
									<div class="symbol-label">
										<i class="fa fa-user-times text-danger"></i>
									</div>
								</div>
								<div class="navi-text">
									<div class="font-weight-bold">{{ __('global.invalidate-sessions') }}</div>
									<div class="text-muted">{{ __('global.invalidate-sessions-desc') }}</div>
								</div>
							</div>
						</a>
						<!--end:Item-->
					@endif

					@if($user->canViewRoute('feedback'))
						<!--begin::Item-->
						<a href="javascript:;" class="navi-item" data-toggle="modal" data-target="#feedback-modal">
							<div class="navi-link">
								<div class="symbol symbol-40 bg-light mr-3">
									<div class="symbol-label">
										<i class="fa fa-envelope text-warning"></i>
									</div>
								</div>
								<div class="navi-text">
									<div class="font-weight-bold">{{ __('global.feedback') }}</div>
									<div class="text-muted">{{ __('global.feedback-contact') }}</div>
								</div>
							</div>
						</a>
						<!--end:Item-->
					@endif
				</div>
			</div>
		</div>
		<!-- end::User Panel-->

		<div id="kt_scrolltop" class="scrolltop" title="{{ __('global.back-to-top') }}" data-container="body" data-toggle="tooltip" data-placement="top">
			<i class="la la-arrow-up"></i>
		</div>

		@include('layouts.modals.feedback')
		@include('layouts.modals.invalidate_sessions')

		<form id="logout-form" action="{{ route('logout') }}" method="post" hidden>
			@csrf
		</form>

		<span id="request-successful" hidden>{{ session('request_successful') }}</span>
	</body>
</html>
