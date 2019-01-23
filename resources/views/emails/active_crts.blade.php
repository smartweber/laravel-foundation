@extends('emails.template')

@section('dear')
	Hello {!! $user->name !!} {!! $user->lastname !!},
@stop

@section('content')
	The following CRT Candidate has been added to the CRT Certification Website by his/her Above Restaurant Leader, {!! $user->userDM_name !!} {!! $user->userDM_lastname !!}:
	<br /><br />
	{!! $user->active_crts !!} at {!! $user->restNumber !!}
	<br /><br />
	No action is needed by you at this time. You will be notified when the Above Restaurant Leader completes the CRT Assessment form and requests Pre-Certification status for this CRT Candidate.
	<br /><br />
	Thank you!
@stop

@section('debug')
<?php $lala = __FILE__ . " | " . __LINE__; ?>
{{ $lala }}
@stop