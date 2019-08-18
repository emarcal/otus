@extends('layouts.login') 

@section('content')
<div class="content content-fixed content-auth" style="padding:100px 0px">
      <div class="container" style="padding: auto 20%">
        <div class="media align-items-stretch justify-content-center ht-100p pos-relative">
        
          <div class="sign-wrapper mg-lg-l-50 mg-xl-r-60">
            <div class="wd-100p">
              <h3 class="tx-color-01 mg-b-5">Entrar</h3>
              <p class="tx-color-03 tx-16 mg-b-40">Bem vindo, de volta. Entre para continuar.</p>
              <form method="POST" action="{{ route('login') }}">
                @csrf
              <div class="form-group">
                <label>E-mail</label>
                <input id="email" placeholder="E-mail" type="email" class="form-control @error('email') is-invalid @enderror" name="email"  required autocomplete="email" value="demo@otus.cloud" autofocus>
                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
              <div class="form-group">
                <div class="d-flex justify-content-between mg-b-5">
                  <label class="mg-b-0-f">Palavra-Passe</label>
                  <a href="" class="tx-13">Esqueci a minha Passe?</a>
                </div>
                <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" value="123123123">
                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
              </div>
              <button class="btn btn-brand-02 btn-block">Entrar</button>
              </form>
              <!-- <div class="divider-text">or</div>
              <button class="btn btn-outline-facebook btn-block">Sign In With Facebook</button>
              <button class="btn btn-outline-twitter btn-block">Sign In With Twitter</button>
              <div class="tx-13 mg-t-20 tx-center">Don't have an account? <a href="page-signup.html">Create an Account</a></div> -->
            </div>
          </div><!-- sign-wrapper -->
          <div class="media-body align-items-center d-none d-lg-flex">
            <div class="mx-wd-400">
              <img src="assets/img/login.png" class="img-fluid" alt="">
            </div>
            <!-- <div class="pos-absolute b-0 l-0 tx-12 tx-center">
              Workspace design vector is created by <a href="https://www.freepik.com/pikisuperstar" target="_blank">pikisuperstar (freepik.com)</a>
            </div> -->
          </div><!-- media-body -->
        </div><!-- media -->
      </div><!-- container -->
      </div><!-- container -->
 

@endsection
