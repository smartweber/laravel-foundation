@extends('emails.template')

@section('dear')
	Hello {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!},
@stop

@section('content')
	Pre-Certification status on {!! $crt->name !!} {!! $crt->lastname !!} 
	at BK® {!! $crt->restNumber !!} will expire in 30 days. 
	Please visit the restaurant to complete the Final CRT Certification (section 7 of the CRT/Training Restaurant Assessment) before 
	<b>{!! date("m/d/Y", strtotime("+90 day",$crt->precertified_at->getTimestamp())) !!}</b>.
@stop

@section('debug')
	<?php $lala = __FILE__ . " | " . __LINE__; ?>
	{{ $lala }}
@stop