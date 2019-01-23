@extends('app')

@section('content')
        <div class="login-wrapper">
            <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">
				<h2 class="loginTitle">Foundations Track Registration Site</h2>
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
                <form name="loginform" id="loginform" action="{{ url('/auth/login') }}" method="post">
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <p>
                        <label for="user_email">Email<br />
                            <input type="text" name="email" id="user_email" class="input" value="{{ old('email') }}" size="20" /></label>
                    </p>
                    <p>
                        <label for="user_pass">Password<br />
                            <input type="password" name="password" id="user_pass" class="input" value="" size="20" /></label>
                    </p>
                    <p class="forgetmenot">
                        <label class="icheck-label form-label" for="rememberme"><input name="remember" type="checkbox" id="rememberme" class="skin-square-blue" > Remember me</label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Sign In" />
                    </p>
                </form>

                <p id="nav" class="row">
                    <a href="{{ url('/password/email') }}" title="Password Lost and Found">Forgot password?</a>
                </p>

                <div id="bottom-links" class="row">
                    <ul class="nav nav-justified">
                        <li><a href="{{url('auth/register/DM')}}">DM Registration</a></li>
                        <li><a href="{{url('auth/register/MFP')}}">MFP Registration</a></li>
                    </ul>
                </div>

            </div>
        </div>
@stop
