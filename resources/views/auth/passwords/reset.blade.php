@extends('layouts.master')

@section('content')
<div class="my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 col-sm-6">
            <div class="card border">
                <div class="h3 card-header bg-primary text-white">{{ __('Reset password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email" class="text-md-right">{{ __('Email address') }}</label>

                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password" class="text-md-right">{{ __('Password') }}</label>

                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="text-md-right">{{ __('Confirm password') }}</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('Reset password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
