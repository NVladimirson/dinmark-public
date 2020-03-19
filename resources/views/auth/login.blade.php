@extends('layouts.empty', ['paceTop' => true])

@section('title', 'Login Page')

@section('content')
	<!-- begin login-cover -->
	<div class="login-cover">
		<div class="login-cover-image" style="background-image: url(/assets/img/login-bg/login-bg-17.jpg)" data-id="login-cover-image"></div>
		<div class="login-cover-bg"></div>
	</div>
	<!-- end login-cover -->
	
	<!-- begin login -->
	<div class="login login-v2" data-pageload-addclass="animated fadeIn">
		<!-- begin brand -->
		<div class="login-header">
			<div class="brand">
				<img src="{{asset('assets/img/logo/logo.png')}}" alt="Dinmark" height="40">
			</div>
			<div class="icon">
				<i class="fa fa-lock"></i>
			</div>
		</div>
		<!-- end brand -->
		<!-- begin login-content -->
		<div class="login-content">
			<form action="{{ route('login') }}" method="POST" class="margin-bottom-0">
				@csrf
				<div class="form-group m-b-20">
					<input name="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="@lang('auth.placeholder_email')" required value="{{old('email')}}"/>
					@error('email')
					<span class="invalid-feedback " role="alert">
                         <strong>{{ $message }}</strong>
                    </span>
					@enderror
				</div>
				<div class="form-group m-b-20">
					<input name="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="@lang('auth.placeholder_password')" required />
					@error('password')
					<span class="invalid-feedback " role="alert">
                         <strong>{{ $message }}</strong>
                    </span>
					@enderror
				</div>
				<div class="login-buttons">
					<button type="submit" class="btn btn-success btn-block btn-lg">@lang('auth.submit')</button>
				</div>
			</form>
		</div>
		<!-- end login-content -->
	</div>
@endsection

@push('scripts')
	<script src="/assets/js/demo/login-v2.demo.js"></script>
@endpush
