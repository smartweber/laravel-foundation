@extends('emails.template')

@section('dear')
	Hello {!! $user->name !!} {!! $user->lastname !!},
@stop

@section('content')
	@if(isset($message->introducingEmail) && $message->introducingEmail == true)
		Congrats! Your CRT Certification Manager account is ready for verification. 
		Please click <a href="{!! url('password/reset/'.$token) !!}">this link</a> to approve your registration and set your password.
	@else
		Click here to reset your password: {{ url('password/reset/'.$token) }}
	@endif
@stop

@section('debug')
	<?php $lala = __FILE__ . " | " . __LINE__; ?>
	{{ $lala }}
@stop

