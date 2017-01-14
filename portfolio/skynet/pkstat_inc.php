<?php // pkstat_inc.php

//////////////////////////////////////////
////////// N�IHIN SAAT KOSKEA ////////////
//////////////////////////////////////////

$_PKSTAT['salasana'] = "testisalasana"; // Adminin salasana
$_PKSTAT['hak'] = getcwd()."/pkstats"; // P��hakemisto laskurille, ei suositella vaihdettavaksi. (ei kenoviivaa viimeiseksi merkiksi)

$_PKSTAT['aikaraja'] = 5; // 5 sekunnin aikaraja identtisille sivunlatauksille
$_PKSTAT['banbots'] = 1; // bannitaanko botit jotka joskus sotkevat tilastot (1/0, oletus kyll�(1))
$_PKSTAT['sstat_uniq'] = 100; // kertoo joka 100 uniikin k�vij�n v�lein milloin tuo raja rikottiin
$_PKSTAT['sstat_load'] = 1000; // kertoo joka 1000 latauksen v�lein milloin tuo raja rikottiin



//////////////////////////////////////////
///////// �L� KOSKE ALLA OLEVIIN /////////
//////////////////////////////////////////

$_PKSTAT['admin_file'] = $_PKSTAT['hak']."/admin_settings";
$PKSTR = @file_get_contents($_PKSTAT['admin_file']);
$_PKSTAT['log_nolog'] = (int)$PKSTR[0]; // Kytke tilastointi pois p��lt�?
$_PKSTAT['log_ziplog'] = (int)$PKSTR[1]; // K�yt� pakattua tilastojen s�il�nt��?
$_PKSTAT['log_range'] = (int)$PKSTR[2]; // Tallenna tilastoja vain viimeisten x p�iv�n ajalta?
$_PKSTAT['log_rangevalue'] = (int)substr($PKSTR, 3, 3); // x p�iv�n suuruus
$_PKSTAT['log_alasalli'] = (int)$PKSTR[6];
$_PKSTAT['m'] = (PHP_OS == "WIN32") ? "/" : "";


function get_fileval($tiedosto, &$unix){
	global $_PKSTAT;
	$filu = @file($_PKSTAT['hak']."/$tiedosto");
	if(@count($filu) == 2) $unix = trim($filu[1]);
	return trim($filu[0]);
}

?>