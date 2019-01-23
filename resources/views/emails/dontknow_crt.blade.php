@extends('emails.template')

@section('dear')
	Hello {!! $crt->userDM->name !!} {!! $crt->userDM->lastname !!},
@stop

@section('content')
	MFP, {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!} ({!! $crt->userMFP->email !!}), 
	has indicated Restaurant #{!! $crt->restNumber !!} is not part of their alignment.
	<br /><br />
    Please check with your supervisor then select a different MFP for Restaurant #{!! $crt->restNumber !!}. 
    You can do so by <a href="{!! url('auth/login') !!}">logging in</a> to the CRT Certification Manager
@stop

@section('debug')
	<?php $lala = __FILE__ . " | " . __LINE__; ?>
	{{ $lala }}
@stop