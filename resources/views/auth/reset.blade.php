@extends('app')

@section('content')
        <div class="login-wrapper">
            <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">
				<h2 class="loginTitle">Reset Password</h2>
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
                
                <form role="form" method="POST" action="{{ url('/password/reset') }}">
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="token" value="{{ $token }}">
                    <p>
                        <label>Email Address<br />
                            <input type="text" name="email" id="user_email" class="input" value="{{ old('email') }}" size="20" /></label>
                    </p>
                    <p>
                        <label>Password<br />
                            <input type="password" name="password" class="input" size="20" /></label>
                    </p>
                    <p>
                        <label>Confirm Password<br />
                            <input type="password" name="password_confirmation" class="input" size="20" /></label>
                    </p>

                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Reset Password" />
                    </p>
                </form>

                <p id="nav">
                    <a class="pull-right" href="{{ url('/auth/login') }}" title="Sign Up">Sign In</a>
                </p>
                <div class="clearfix"></div>
            </div>
        </div>
@stop
