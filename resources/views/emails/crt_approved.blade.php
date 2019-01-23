@extends('emails.template')

@section('dear')
	Hello {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!},
@stop

@section('content')
	{!! $crt->userDM->name !!} {!! $crt->userDM->lastname !!}, has completed sections 1-6 of the CRT Assessment for:
	{!! $crt->name !!} {!! $crt->lastname !!} at {!! $crt->restNumber !!} and has requested Pre-Certification status.
	<br/><br/>
	Please login to the CRT Certification Website and review the assessment. Before clicking “approved” please verify the following:
	<ol>
		<li>CRT has attended / successfully completed the CRT Train-the-Trainer Workshop.</li>
		<li>Training restaurant’s BK® GURU completion rate on the Basics track is 85% or higher.</li>
	</ol>
	<br/>
	Once you approve, the CRT will have access to Foundations Trac - usually within 48 hours.  You will need to visit the CRT and Training Restaurant within 90 days to perform the “Final” Certification (section 7 of the CRT assessment form).
	<br/><br/>
	Thank you!
@stop