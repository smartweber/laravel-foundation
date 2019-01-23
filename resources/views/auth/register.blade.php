@extends('app')

@section('content')
        <div class="register-wrapper">
            <div id="register" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">
                <h2 class="loginTitle">{{ $userType }} Register</h2>
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
                
                <form name="loginform" id="loginform" role="form" method="POST" action="{{ url('/auth/register') }}">
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                	<input type="hidden" name="type" value="{{ $userType }}">
                    <p>
                        <label for="user_login">First Name<br />
                            <input type="text" name="name" id="user_name" class="input" value="{{ old('name') }}" size="20" /></label>
                    </p>
                    <p>
                        <label for="user_login">Last Name<br />
                            <input type="text" name="lastname" id="user_lastname" class="input" value="{{ old('lastname') }}" size="20" /></label>
                    </p>
                    <p>
                        <label for="user_login">Email<br />
                            <input type="text" name="email" id="user_email" class="input" value="{{ old('email') }}" size="20" /></label>
                    </p>
                    <p>
                        <label for="user_pass">Password<br />
                            <input type="password" name="password" id="user_pass" class="input" size="20" /></label>
                    </p>
                    <p>
                        <label for="user_pass">Confirm Password<br />
                            <input type="password" name="password_confirmation" id="user_pass1" class="input" size="20" /></label>
                    </p>

                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Sign Up" />
                    </p>
                </form>

                <p id="nav">
                    <a class="pull-right" href="{{ url('/auth/login') }}" title="Sign Up">Sign In</a>
                </p>
                <div class="clearfix"></div>


            </div>
        </div>
@stop
