<?php
//----- Haetaan hostname, jos sellainen on saatavilla
if(!$REMOTE_HOST)	//jos hostia ei oo m��ritelty
{
   $hostNimi = gethostbyaddr($REMOTE_ADDR);   // haetaan ip-osoitteelle host,
}
else
{
   $hostNimi = $REMOTE_HOST;
}

$selain = $HTTP_USER_AGENt