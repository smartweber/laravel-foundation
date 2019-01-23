<?
if ($_SERVER["SERVER_ADDR"] == "10.1.3.104")
{
        echo "Root Domain: (DEV) ".$_SERVER["HTTP_HOST"];
        phpinfo();
}
else
{
        echo "Root Domain: ".$_SERVER["HTTP_HOST"];
}
?>
