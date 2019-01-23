@extends('emails.template')

@section('dear')
	Hello {!! $crt->userDM->name !!} {!! $crt->userDM->lastname !!},
@stop

@section('content')
	Congratulations! Your MFP {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!} has validated the Training Restaurant requirements and approved your CRT for Pre-Certification! You and your CRT will have access to Foundations Trac within 48 hours. Foundations Trac can be accessed via the GURU TRAC® link on BK® Gateway.
	<br /><br />
	Your MFP will be calling soon to schedule the final certification which must happen within 90 days of {!! $crt->precertified_at->format('Y/m/d') !!}.
	Congratulations and happy training!
@stop

@section('debug')
	<?php $lala = __FILE__ . " | " . __LINE__; ?>
	{{ $lala }}
@stop