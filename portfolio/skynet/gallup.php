<?php
########### g a l l u p ##########
########### by: snakari ##########
##### http://snakari.cjb.net #####
##################################
#     säilytä tekijän nimi!      #
##################################
# tee tiedosto "kysymykset.txt"  #
# ja anna sille oikat 777        #
##################################
#  tiedosto missä kysymykset on  #
$tiedosto="gallup_kysym.txt";
##################################
#   tee tiedoston sisällöksi:    #
#   kysymys                      #
#   vastaus 1|0                  #
#   vastaus 2|0                  #
#   ...                          #
##################################

$f=@file($tiedosto);

if (!$vastaus)
{
  if ($HTTP_COOKIE_VARS["gallup_vastaus"]!==trim($f[0]))
  {
   # tulostetaan kysymykset
   echo '<b>'. trim($f[0]) .'</b><br>';
   for($rivi=1; $rivi<count($f); $rivi++)
   {
       $pilko=explode("|", trim($f[$rivi]));
       echo '<img src="images/fuckyou.gif" border="0">&raquo; <a class="galluplink" href="'. $PHP_SELF .'?vastaus='. $rivi .'">'. $pilko[0] .'</a><br>';
   }
  }
  else
  {
    # tulostetaan vastaukset
    echo '<b>'. trim($f[0]) .'</b><br>';
    for ($rivi=1; $rivi<count($f); $rivi++)
    {
      $pilko=explode("|", trim($f[$rivi]));
      $yht=$yht+$pilko[1];
    }
    for ($rivi=1; $rivi<count($f); $rivi++)
    {
      $pilko=explode("|", trim($f[$rivi]));
      echo $pilko[0] .' ('. @intval($pilko[1]/$yht*100) .'%) <br>
<TABLE bgcolor="#FF6633" height="10" width="'. @intval($pilko[1]/$yht*100+1) .'"><TR><TD> </TD></TR></TABLE>';

    }
    echo 'Vastauksia: '. $yht;
  }
}
if ($vastaus && $HTTP_COOKIE_VARS["gallup_vastaus"]!==trim($f[0]))
{
  if ($vastaus>count($f) or $vastaus<1)
  {
    # jos vastaus on muunneltu
    echo 'eipäs onnistunut!';
  }
  else
  {
      # lisätään 1 vastaukseen
      $lis=explode("|", trim($f[$vastaus]));
      if ($lis[1]) { $lis[1]++; }
      else { $lis[1]=1; }
      $f[$vastaus]=$lis[0] ."|". $lis[1] ."\n";
      $filu=fopen($tiedosto,"w");
      foreach ($f as $rivi)
      {
      fwrite($filu, $rivi);
      }
      fclose ($filu);
      setcookie("gallup_vastaus",trim($f[0]),time()+ 60 * 60 * 24 * 5); #Eväste voimassa 5 vuorokautta (vika '* 5' pois lopusta, niin vuorokauden voimassa).
      header ("Location: ". $PHP_SELF);
  }
}
?>