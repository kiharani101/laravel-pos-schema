@extends('layouts.master')

@section('content')
  <section class="material-half-bg">
	<div class="cover"></div>
  </section>
  <section class="login-content">
	<div class="logo">
	  <h1>Vali</h1>
	</div>
	<div class="login-box">
	  <form class="login-form" method="POST" id="login-form" action="{{ route('login') }}" autocomplete="false">
		@csrf
		<h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
		<div class="form-group">
		  <label class="control-label" for="email">EMAIL</label>
		  <input name="email" id="email" type="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
			
			@if ($errors->has('email'))
				<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first('email') }}</strong>
				</span>
			@endif
		</div>
		<div class="form-group">
		  <label class="control-label" for="password">PASSWORD</label>
		  <input name="password" id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

		  @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
          @endif
		</div>
		<div class="form-group">
		  <div class="utility">
			<div class="animated-checkbox">
			  <label>
				<input type="checkbox"><span class="label-text">Remember me</span>
			  </label>
			</div>
			<p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
		  </div>
		</div>
		<div class="form-group btn-container">
		  <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
		</div>
	  </form>
	  <form class="forget-form" id="fp-form">
		<h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
		<div class="form-group">
		  <label class="control-label">EMAIL</label>
		  <input class="form-control" type="text" placeholder="Email">
		</div>
		<div class="form-group btn-container">
		  <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
		</div>
		<div class="form-group mt-3">
		  <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
		</div>
	  </form>
	</div>
  </section>

@endsection

@push('scripts')
	<script>
		// Login Page Flipbox control
		$('.login-content [data-toggle="flip"]').click(function() {
		  $('.login-box').toggleClass('flipped');
		  return false;
		});
	</script>
@endpush