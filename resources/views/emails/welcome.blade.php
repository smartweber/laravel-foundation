@extends('emails.template')

@section('dear')
    Hello {!! $user->name !!} {!! $user->lastname !!},
@stop

@section('content')
    Welcome!
    <br/><br/>
    Your CRT Certification Site account has been created.<br>
    You will use this site to approve Pre-Certification for your CRTs. You will also enter Final Certification results in this site.<br>
    Please see the MFP CRT Certification User Guide attached for more information.<br>
    Please click on the link below and enter the following account information to login:<br>
    <br/>
    {!! url('auth/login') !!}
    <br/>
    Email: {!! $user->email !!}<br/>
    Password: {!! $user->text_password !!}
@stop

@section('debug')
<?php $lala = __FILE__ . " | " . __LINE__; ?>
{{ $lala }}
@stop