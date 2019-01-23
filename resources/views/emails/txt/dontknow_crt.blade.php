Hello {!! $crt->userDM->name !!} {!! $crt->userDM->lastname !!},

MFP, {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!} ({!! $crt->userMFP->email !!}), has indicated Restaurant #{!! $crt->restNumber !!} is not part of their alignment.

Please check with your supervisor then select a different MFP for Restaurant #{!! $crt->restNumber !!}. You can do so by logging in to the CRT Certification Manager
{!! url('auth/login') !!}