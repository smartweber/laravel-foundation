@extends('emails.template')

@section('dear')
	Hello {!! $user->name !!} {!! $user->lastname !!},
@stop

@section('content')
	The following CRT Candidate has been added to the CRT Certification Website by his/her Above Restaurant Leader, {!! $user->userDM_name !!} {!! $user->userDM_lastname !!}:

	{!! $user->active_crts !!} at {!! $user->restNumber !!}

	No action is needed by you at this time. You will be notified when the Above Restaurant Leader completes the CRT Assessment form and requests Pre-Certification status for this CRT Candidate.

	Thank you!
@stop