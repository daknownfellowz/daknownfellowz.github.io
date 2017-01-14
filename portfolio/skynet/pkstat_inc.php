<?php // pkstat_inc.php

//////////////////////////////////////////
////////// NÄIHIN SAAT KOSKEA ////////////
//////////////////////////////////////////

$_PKSTAT['salasana'] = "testisalasana"; // Adminin salasana
$_PKSTAT['hak'] = getcwd()."/pkstats"; // Päähakemisto laskurille, ei suositella vaihdettavaksi. (ei kenoviivaa viimeiseksi merkiksi)

$_PKSTAT['aikaraja'] = 5; // 5 sekunnin aikaraja identtisille sivunlatauksille
$_PKSTAT['banbots'] = 1; // bannitaanko botit jotka joskus sotkevat tilastot (1/0, oletus kyllä(1))
$_PKSTAT['sstat_uniq'] = 100; // kertoo joka 100 uniikin kävijän välein milloin tuo raja rikottiin
$_PKSTAT['sstat_load'] = 1000; // kertoo joka 1000 latauksen välein milloin tuo raja rikottiin



//////////////////////////////////////////
///////// ÄLÄ KOSKE ALLA OLEVIIN /////////
//////////////////////////////////////////

$_PKSTAT['admin_file'] = $_PKSTAT['hak']."/admin_settings";
$PKSTR = @file_get_contents($_PKSTAT['admin_file']);
$_PKSTAT['log_nolog'] = (int)$PKSTR[0]; // Kytke tilastointi pois päältä?
$_PKSTAT['log_ziplog'] = (int)$PKSTR[1]; // Käytä pakattua tilastojen säilöntää?
$_PKSTAT['log_range'] = (int)$PKSTR[2]; // Tallenna tilastoja vain viimeisten x päivän ajalta?
$_PKSTAT['log_rangevalue'] = (int)substr($PKSTR, 3, 3); // x päivän suuruus
$_PKSTAT['log_alasalli'] = (int)$PKSTR[6];
$_PKSTAT['m'] = (PHP_OS == "WIN32") ? "/" : "";


function get_fileval($tiedosto, &$unix){
	global $_PKSTAT;
	$filu = @file($_PKSTAT['hak']."/$tiedosto");
	if(@count($filu) == 2) $unix = trim($filu[1]);
	return trim($filu[0]);
}

?>