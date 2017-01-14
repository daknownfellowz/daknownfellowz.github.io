<?php

$arrayTiedosto = file("laskuri_log.txt");     //Luetaan lokitiedosto muistiin

$kokonaismaara=count($arrayTiedosto);    //Lasketaan kävijöiden kokonaismäärä

// --- Järjestetään tiedoston sisältö oikeisiin array-muuttujiin
for($i=0;$i<count($arrayTiedosto);$i++)

// --- Pilkotaan tekstitiedosto pystyviivojen kohdalta muuttujiksi
{
   $valiaikainen = explode("|", $arrayTiedosto[$i]);

   $selain[$i]=$valiaikainen[0];
   $ip[$i]=$valiaikainen[1];
   $host[$i]=$valiaikainen[2];
   $referer[$i]=$valiaikainen[3];
}

// --- Lasketaan yksilöllisten osumien määrä ja tallennetaan tiedot muuttujiin
$kaikki_selaimet=(array_count_values($selain));
$kaikki_ipt=(array_count_values($ip));
$kaikki_hostit=(array_count_values($host));
$kaikki_refererit=(array_count_values($referer));

// --- Tulostetaan kokonaismäärä
echo("<strong>Kävijöitä yhteensä:</strong>$kokonaismaara<br>");

// --- Tulostetaan eri selaimet ja niiden määrä
echo("<strong>Selaimet:</strong><br>");

while (list($selaimen_nimi, $maara) = each($kaikki_selaimet)) {
   $prosentti=(($maara/$kokonaismaara)*100);
   $prosentti=round($prosentti,2);
   echo ("$selaimen_nimi = $maara  - $prosentti %<br> \n");
}

// --- Tulostetaan eri IP:t
echo("<strong>Ip-osoitteet:</strong><br>");

while (list($IPosoite, $maara) = each($kaikki_ipt)) {
   $prosentti=(($maara/$kokonaismaara)*100);
   $prosentti=round($prosentti,2);
   echo ("$IPosoite = $maara - $prosentti %<br> \n");
}

// --- Tulostetaan eri hostit
echo("<strong>Host-osoitteet:</strong><br>");

while (list($HostOsoite, $maara) = each($kaikki_hostit)) {
   $prosentti=(($maara/$kokonaismaara)*100);
   $prosentti=round($prosentti,2);
   echo ("$HostOsoite = $maara - $prosentti % <br> \n");
}

// --- Tulostetaan eri hostit
echo("<strong>Referer-osoitteet:</strong><br>");

while (list($RefererOsoite, $maara) = each($kaikki_refererit)) {
   $prosentti=(($maara/$kokonaismaara)*100);
   $prosentti=round($prosentti,2);
   echo ("$RefererOsoite = $maara - $prosentti % <br> \n");
}

?>
