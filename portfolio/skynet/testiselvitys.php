<?php
if(!$REMOTE_HOST)
{
  $hostNimi = gethostbyaddr($REMOTE_ADDR);
}
else
{
  $hostNimi = $REMOTE_HOST;
}
echo("$HTTP_USER_AGENT <br>");
echo("$REMOTE_ADDR <br>");
echo("$hostNimi <br>");
echo("$HTTP_REFERER <br>");
?>

