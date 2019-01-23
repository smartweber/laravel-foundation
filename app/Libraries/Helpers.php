<?php namespace App\Libraries;

class Helpers
{
    public static function mail($emailAddr)
    {

       	return 'rkomatz@originalimpressions.com';

        if(env('MAIL_DEV', true))
        	return 'rkomatz@oiondemand.com';
        else
        	return $emailAddr;
    }
}
