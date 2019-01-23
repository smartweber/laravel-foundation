@extends('emails.template')

@section('dear')
	Hello {!! $crt->userDM->name !!} {!! $crt->userDM->lastname !!},
@stop

@section('content')
	Congratulations! 
	Your MFP {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!} has completed the FINAL CERTIFICATION on {!! $crt->name !!} {!! $crt->lastname !!} 
	at BK® {!! $crt->restNumber !!}. 
	This restaurant and CRT are now certified for one year. You, the MFP, and your CRT will be notified approximately 60 days before this certification expires.  As a reminder, the re-certification process follows the same steps as your initial certification.  If you have any questions, please contact your MFP. 
	<br /> <br />
	Once again, congratulations!
@stop

@section('debug')
	<?php $lala = __FILE__ . " | " . __LINE__; ?>
	{{ $lala }}
@stop