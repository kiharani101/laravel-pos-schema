@extends('layouts.master')

@section('content')
  
  <section class="material-half-bg">
    <div class="cover"></div>
  </section>
  <section class="login-content row">
    <div class="logo">
      <h1>Vali</h1>
    </div>
    <div class="login-box col-md-6 mx-auto">
        <span id="success-message"></span>
      <form class="login-form" id="register-form" action="{{ route('register') }}">
        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN UP</h3>
        <div class="form-group form-row">
          <div class="col-sm-6">
            <label class="control-label" for="fname">FIRST NAME</label>
              <input class="form-control" name="fname" id="fname" type="text" autofocus>
          </div>
          <div class="col-sm-6">
            <label class="control-label" for="lname">LAST NAME</label>
              <input class="form-control" name="lname" id="lname" type="text">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label" for="email">EMAIL</label>
          <input class="form-control" name="email" id="email" type="email">
        </div>
        <div class="form-group form-row">
            <div class="col-sm-6">
              <label class="control-label" for="password">PASSWORD</label>
              <input class="form-control" name="password" id="password" type="password">
          </div>
          <div class="col-sm-6">
              <label class="control-label" for="cpassword">PASSWORD</label>
              <input class="form-control" name="cpassword" id="cpassword" type="password">
          </div>
        </div>
        <div class="form-group btn-container  mb-4">
          <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN UP</button>
        </div>
      </form>
    </div>
  </section>
@endsection

@push('scripts')
    {{-- expr --}}
@endpush