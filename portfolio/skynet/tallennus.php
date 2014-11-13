<?php
//----- Haetaan hostname, jos sellainen on saatavilla
if(!$REMOTE_HOST)	//jos hostia ei oo mההritelty
{
   $hostNimi = gethostbyaddr($REMOTE_ADDR);   // haetaan ip-osoitteelle host,
}
else
{
   $hostNimi = $REMOTE_HOST;
}

$selain = $HTTP_USER_AGENt