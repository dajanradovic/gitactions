@extends('layouts.public', ['seo' => false])

@section('title', $exception->getStatusCode())

@section('content')
	<div class="d-flex flex-row-fluid flex-column bgi-size-cover bgi-position-center bgi-no-repeat p-10 p-sm-30" style="background-image: url({{ asset('img/error.jpg') }});">
		<h1 class="font-weight-boldest text-dark-75 mt-15">{{ $exception->getStatusCode() }}</h1>
		<p class="font-size-h3 text-muted font-weight-normal">{{ !empty($exception->getMessage()) ? $exception->getMessage() : __('errors.' . $exception->getStatusCode() . '.message') }}</p>
		<a href="{{ route('home') }}" class="text-hover-primary"><i class="fa fa-home"></i> {{ __('global.home') }}</a>
	</div>
@endsection
