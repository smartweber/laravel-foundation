@extends('emails.template')

@section('dear')
	Hello {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!},
@stop

@section('content')
	It’s almost that time! Yearly Re-Certification needs to be performed on {!! $crt->name !!} {!! $crt->lastname !!} at BK®  {!! $crt->restNumber !!} before 
	<b>{!! date("m/d/Y", strtotime("+1 year",$crt->certified_at->getTimestamp())) !!}</b>.  
	<br /> <br />
	As a reminder, re-certification process is as follows:
	<br /> <br />
	<ol>
	<li>ARL &amp; CRT complete sections 1-6 of the CRT Assessment Form. The ARL then enters the results in the CRT Certification Website.</li>
	<li>MFP reviews the assessment in the CRT Certification Website, confirms Training Restaurant requirements are met, then approves Pre-Certification status if appropriate.</li>
	<li>MFP visits the CRT at the Training Restaurant to complete section 7 of the CRT Assessment Form for Final (yearly) Certification. MFP enters results into the CRT Certification Website.</li>
	</ol>
	<br />
	If the CRT Certification expires, he/she will not have access to Foundations Trac. This will be your only reminder, Please start the certification process soon!
@stop

@section('debug')
	<?php $lala = __FILE__ . " | " . __LINE__; ?>
	{{ $lala }}
@stop