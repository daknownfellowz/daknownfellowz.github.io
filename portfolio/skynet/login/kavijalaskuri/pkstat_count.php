<?php // pkstat_count.php


include_once("pkstat_inc.php");

////////////////////////////////////
//    K � V I J � L A S K U R I
//
//  - - FFS Multicounter (v.2) - -
//
////////////////////////////////////
//
// By: T.M. (hctm at suomi24 piste fi)
// www.HC-Codes.net
// http://koti.mbnet.fi/winuus/
//
////////////////////////////////////
// 
// Laskee seuraavanlaisia tilastoja: 
// - K�vij�t yhteens� 
// - K�vij�t t�n��n 
// - Sivunlataukset yhteens� 
// - Sivunlataukset t�n��n 
// - K�ytt�j�t online 
// - P�ivitt�isin suurin k�vij�m��r� 
// - P�ivitt�isin suurin sivunlatausm��r� 
// - P�ivitt�isin suurin online m��r� 
// - Aikaleimat enn�tyksist� 
// - Viimeisin k�vij� / lataus -aikaleima 
// - Milloin laskuri k�ynnistettiin, ja kuinka kauan se on ollut toiminnassa 
// - Keskim��r�iset k�vij�m��r�t p�ivitt�in 
// - Keskim��r�iset lataukset per k�vij� 
// - Kaikki k�vij�enn�tykset 100 k�vij�n tai 1000 latauksen v�lein 
// 
// Ominaisuudet: 
// - Graafinen k�vij�laskuri 
// - Graafinen tilastok�yr� 
// - K�vij�tilastojen selaaminen ja haku 
// - Mahdollisuus luoda oma kuva k�vij�laskuriin 
// - Mahdollisuus k�ytt�� pakattua tilastojen s�il�nt�� 
// - Mahdollisuus kytke� tilastointi pois p��lt� 
// - Mahdollisuus tallentaa tilastoja vain viimeisilt� 30 p�iv�n ajalta
//
////////////////////////////////////
//
// Ohjeet:
// - Luo juureen kansio: "pkstats"
// - Luo kansion "pkstats" sis�lle kansiot: "online" ja "tilastot"
//
// - Chmodaa KAIKKI yll� mainitut 3 kansiota arvolla 777
//
// - Laita juureen seuraavat tiedostot:
//    pkstat_inc.php
//    pkstat_count.php
//    pkstat_show.php
//    pkstat_grafcount.php
//    pkstat_kayra.php
//    pkstat_admin.php
//
// - K�yt� include-toimintoa liitt��ksesi laskurin sivuillesi:
//      include("pkstat_count.php");
// - Liit� kyseinen include kaikille niille sivuille joilta haluat laskurin ker��v�n statistiikkaa
// - Liit� "pkstat_show.php" niille sivuille joilla haluat laskurin n�ytt�v�n eri tilastot
//
//
// Nyt sinulla pit�isi olla seuraavanlainen hakemistorakenne:
//
// www.testisivu.net/pkstat_inc.php
// www.testisivu.net/pkstat_count.php
// www.testisivu.net/pkstat_show.php
// www.testisivu.net/pkstat_grafcount.php
// www.testisivu.net/pkstat_kayra.php
// www.testisivu.net/pkstat_admin.php
// www.testisivu.net/pkstats/
// www.testisivu.net/pkstats/online/
// www.testisivu.net/pkstats/tilastot/
//
////////////////////////////////////
//
// Jos laskurisi ei toimi tai se heitt�� virhett�:
// - Tarkista onko hakemistorakenne sama.
// - Tarkista ett� olet antanut kaikille kansioille 777 CHMOD oikeudet jollain FTP-ohjelmalla.
// - Jos virhe ei mene ohi vaikka kansioilla on tarvittavat oikeudet:
//     - Anna my�s kansioiden sis�ll� oleville tiedostoille erikseen 777 CHMOD oikeudet.
// - Jos sama virhe toistuu edelleen, tarkista ett� olet noudattanut ohjeita t�sm�llisesti.
// - Muussa tapauksessa ota yhteytt�: hctm at suomi24 piste fi
//
////////////////////////////////////




// Tyhj�� vanhojen statsien tiedostot, s�ilytt�� niiden p�ivien lukemat.
function clear_old_stats(){
	global $_PKSTAT;
	$kanta = @glob($_PKSTAT['m'].$_PKSTAT['hak']."/tilastot/*");
	if(!$_PKSTAT['log_rangevalue']) $_PKSTAT['log_rangevalue'] = 30;
	$end = count($kanta)-$_PKSTAT['log_rangevalue']-1;
	if($end > 0){
		for($u = 0; $u < $end; $u++){
			$koko = filesize($kanta[$u]);
			if($koko > 0){
				$clear = @fopen($kanta[$u], "w");
				@fclose($clear);
			}
		}
	}
}

// $file_from: mik� tiedosto pakataan
// $file_to: mihin pakattu tiedosto tallennetaan
// $mem_usage: kuinka paljon muistia saa k�ytt�� korkeintaan:
// --- esim jos $mem_usage on 1000, t�ten muistia k�ytet��n korkeintaan 1000*1024 tavua
function file_compress($file_from, $file_to, $mem_usage = 1000){
	// Ei ylikirjoiteta olemassaolevaa tiedostoa:
	if(!file_exists($file_to)){
		$p = 0;
		$data = "";
		$fp = fopen($file_from, "r");
		$save = fopen($file_to, "w");
		fwrite($save, "�FFSGZ\n"); // formaatin tunniste :)
		while(!feof($fp)){
			// luetaan 1024 tavua:
			$data .= fread($fp, 1024);
			// Jos k�ytett�viss� oleva muistin m��r� on ylitetty:
			if($p > $mem_usage){
				// Pakataan data:
				$zip = gzcompress($data);
				// Tallennetaan data tiedostoon, joka koostuu tyyliin: "5:seppo6:heikki5:keijo"
				// Eli merkkijonon pituus haetaan kaksoispistett� ennen.
				$len = strlen($zip);
				fwrite($save, $len.":".$zip);
				$data = "";
				$p = 0;
			}
			$p++;
		}
		if($data != ""){
			// Jos j�i t�hteit�, lis�t��n nekin pakkaukseen:
			$zip = gzcompress($data);
			$len = strlen($zip);
			fwrite($save, $len.":".$zip);
			$data = "";
			$p = 0;
		}
		fclose($save);
		fclose($fp);
	}
}

// Hakee ekan rivin halutusta tiedostosta:
function get_value($tiedosto){
	global $_PKSTAT;
	return @intval(trim(current(file($_PKSTAT['hak']."/$tiedosto"))));
}

// Tarkistaa onko jokin k�vij�raja rikottu:
function stat_rajarikki($maara, $type){
	global $_PKSTAT;
	if($maara > 0){
		$m = $type == 1 ? "uniq" : "load";
		if($maara % $_PKSTAT["sstat_".$m] == 0){
			$rivi = time().sprintf("%010d", $maara)."\n";
			$save = fopen($_PKSTAT['hak']."/raja_{$m}", "a");
			fwrite($save, $rivi);
			fclose($save);
			$save = fopen($_PKSTAT['hak']."/raja_last_{$m}", "w");
			fwrite($save, $rivi);
			fclose($save);
		}
	}
}

// Tuottaa tasan 8 merkki� pitk�n IP-osoitteen tyyliin: 50BA3449
function short_ip($ip){
	$osa = explode(".", $ip);
	return substr(sprintf("%02X%02X%02X%02X", (int)$osa[0], (int)$osa[1], (int)$osa[2], (int)$osa[3]), 0, 8);
}

// Purkaa koodatun IP-osoitteen:
function decode_short_ip($ip){
	return hexdec(substr($ip, 0, 2)).".".hexdec(substr($ip, 2, 2)).".".hexdec(substr($ip, 4, 2)).".".hexdec(substr($ip, 6, 2));
}

// Oletuksena 120 sekunnin aikaraja (�l� vaihda)
function ffs_users_online($aikaraja = 120){
	global $_PKSTAT;
	$kansio = $_PKSTAT['hak']."/online";
	$files = glob($_PKSTAT['m']."$kansio/*");
	$online = (int)@count($files);
	$ip = short_ip($_SERVER['REMOTE_ADDR']);
	$find = 0;
	$aika = time();
	if(is_array($files)){
		foreach($files as $tiedosto){
			$base = basename($tiedosto);
			$file_ip = substr($base, 0, 8);
			$file_time = substr($base, 8);
			if($file_ip == $ip){
				// P�ivitet��n tiedot jos sin� lataat sivun toistamiseen:
				@rename($tiedosto, $kansio."/".$file_ip.$aika);
				$find = 1;
			}elseif($aika - $file_time > $aikaraja){
				// Tuhotaan sen k�ytt�j�n tiedot jonka aikaraja on umpeutunut:
				if(@unlink($tiedosto)){
					$online--;
				}
			}
		}
	}
	if(!$find){
		// Jos et ollut ladannut sivua viel�, luodaan tiedosto:
		fclose(fopen($kansio."/".$ip.$aika, "w"));
		$online++;
	}
	return $online;
}

function modify_stat($tiedosto, $style, $num = ""){
	global $_PKSTAT;
	$savefile = $_PKSTAT['hak']."/$tiedosto";
	$filu = @file($savefile);
	$eka = (int)trim($filu[0]);
	$write = 1;
	$data = "";
	if($style == "savetime"){
		$data = time();
	}elseif($style == "compare"){
		if($num > $eka){
			if($tiedosto == "top_load"){
				$aika = @file_get_contents($_PKSTAT['hak']."/time_last_load");
			}elseif($tiedosto == "top_uniq"){
				$aika = @file_get_contents($_PKSTAT['hak']."/time_last_uniq");
			}else{
				$aika = time();
			}
			if($aika){
				$data = $num."\r\n".$aika;
				$write = 1;
			}else{
				$write = 0;
			}
		}else{
			$write = 0;
		}
	}elseif($style == "add"){
		$data = $eka+$num;
	}
	if($write && $data != ""){
		$save = fopen($savefile, "w");
		fwrite($save, $data);
		fclose($save);
		return true;
	}else{
		return false;
	}
}

function save_fastdata($type, $data){
	global $_PKSTAT;
	$save = @fopen($_PKSTAT['hak']."/fastdata_$type", "a");
	@fwrite($save, $data);
	@fclose($save);
}

function clean_string($data){
	return stripslashes(str_replace(array("\r", "\n", "|"), array("%0D", "%0A", "%7C"), $data));
}

function ffs_count_stats(){
	global $_PKSTAT;
	$write = 1;
	$bot = 0;
	$tamapaiva = $_PKSTAT['hak']."/".date("ymd").".today";

	$ip = $_SERVER['REMOTE_ADDR'];
	$host = @gethostbyaddr($ip);
	$prev = $_SERVER['HTTP_REFERER'];
	$prev = clean_string(substr($prev, 0, 7) == "http://" ? substr($prev, 7) : $prev);
	$nyky = clean_string($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	$agent = clean_string($_SERVER['HTTP_USER_AGENT']);
	$kieli = clean_string($_SERVER['HTTP_ACCEPT_LANGUAGE']);

	// Jos p�iv� on vaihtunut...
	if(!file_exists($tamapaiva)){
		if($_PKSTAT['log_range'] && $_PKSTAT['log_nolog'] != "1"){
			// Tyhj�t��n vanhat tilastot:
			clear_old_stats();
		}

		// Haetaan eilinen p�iv�:
		$glob_today = glob($_PKSTAT['m'].$_PKSTAT['hak']."/*.today");
		$eilen = $glob_today[0];

		if(file_exists($eilen)){
			// Lasketaan eilisen uniikit k�vij�t:
			$uniks = intval(filesize($_PKSTAT['hak']."/fastdata_uniq")/9);

			// Lasketaan sivunlataukset:
			$loads = filesize($_PKSTAT['hak']."/fastdata_load");
			
			// Haetaan suurin online eiliselt� p�iv�lt�:
			$ton = file($_PKSTAT['hak']."/top_online_today");
			$tod_online = (int)$ton[0];

			$tilanimi = $_PKSTAT['hak']."/tilastot/".basename($eilen, ".today")."-".$uniks."-".$loads."-".$tod_online;

			$etsipaiva = glob($_PKSTAT['m'].$_PKSTAT['hak']."/tilastot/".basename($eilen, ".today")."*");
			$tilakayt = $etsipaiva[0];

			// Jos ei haluta logittaa:
			if($_PKSTAT['log_nolog']){
				// Varmistetaan ettei tilastoa ole jo luotu:
				if($tilakayt == ""){
					// Luodaan tyhj� tiedosto:
					$save = fopen($tilanimi, "w");
					fclose($save);
					// Poistetaan eilinen filu:
					unlink($eilen);
				}
			}else{
				// Varmistetaan ettei tilastoa ole jo luotu:
				if($tilakayt == ""){
					// Pakataanko tilastot?:
					if($_PKSTAT['log_ziplog']){
						if(function_exists("gzcompress")){
							file_compress($eilen, $tilanimi);
							unlink($eilen);
						}
					}else{
						@rename($eilen, $tilanimi);
					}
				}
			}
			
			// Varmistetaan ettei tilastoa ole jo luotu:
			if($tilakayt == ""){
				// Lis�t��n eilinen p�iv� kaikkien aikojen tilastoon:
				modify_stat("yht_load", "add", $loads);
				modify_stat("yht_uniq", "add", $uniks);

				// Koitetaan jos ollaan tehty uusi enn�tys:
				modify_stat("top_load", "compare", $loads);
				modify_stat("top_uniq", "compare", $uniks);

				// Nollataan p�ivitt�iset laskurit:
				unlink($_PKSTAT['hak']."/fastdata_load");
				unlink($_PKSTAT['hak']."/fastdata_uniq");
				unlink($_PKSTAT['hak']."/top_online_today");
			}
		}

		// Jos aloitusp�iv�m��r�� ei ole tallennettu, tallennetaan se:
		$afile = $_PKSTAT['hak']."/aika.start";
		if(!file_exists($afile)){
			$filu = @fopen($afile, "w");
			@fwrite($filu, time());
			@fclose($filu);
		}
	}

	// Haetaan 2000 merkki� tiedoston lopusta:
	$fp = @fopen($tamapaiva, "r");
	@fseek($fp, -2000, SEEK_END);
	$data = @fread($fp, 2000);
	@fclose($fp);

	// Jaetaan haettu tieto riveiksi taulukkoon:
	$rivi = explode("\r\n", $data);
	$ehja = 0;
	$eka = $rivi[0];

	// Tarkistetaan onko eka rivi ehj�:
	if(substr_count($eka, "|") == 7){
		if($eka[10] == "|"){
			$ehja = 1;
		}
	}

	// Jos eka rivi ei ollut ehj�, poistetaan se:
	if(!$ehja) array_shift($rivi);
	// Jos vika rivi on tyhj�, poistetaan se:
	if(trim(end($rivi)) == "") array_pop($rivi);

	// Tarkistussilmukka, joka tarkistaa onko sama k�ytt�j� ladannut
	//  identtisen sivun verraten kolmea edellist� sivunlatausta
	// Jos kaikki on identtist� paitsi aika, saa tiedostoon tallentaa jos
	//  aikaa on kulunut 5 sekuntia edellisest� sivunlatauksesta
	if(is_array($rivi)){
		$aika = time();
		for($u = count($rivi)-1, $p = 0; $u >= 0 && $p < 3; $u--, $p++){
			$osa = explode("|", $rivi[$u]);
			if($aika - $osa[0] < $_PKSTAT['aikaraja']){
				if($osa[1] == $ip){
					if($osa[3] == $prev){
						if($osa[4] == $nyky){
							if($osa[5] == $agent){
								$write = 0;
							}
						}
					}
				}
			}
		}
	}

	// Ei tehd� t�t� turhaan jos kirjoitus on jo pois p��lt�:
	if($write){
		// Jos halutaan bannia botit:
		if($_PKSTAT['banbots']){
			// Botit ja muut paholaiset, jotka saattavat sotkea tilastoja:
			$botit = array(
				"archive.org", "inktomisearch", "googlebot", "altavista", "ihmemaa", 
				"yahoo", "msnbot", "overture.com", "alexa.com", "crawl", 
				"httrack", "website copier", "offline browser", "web mirror utility", 
				"girafa.com", "gigabot", "emeraldshield.com", "exabot.com"
			);
			// Etsit��n paholaisia hostista sek� selaintiedoista:
			foreach($botit as $value){
				if(stristr($host, $value) || stristr($_SERVER['HTTP_USER_AGENT'], $value)){
					$write = 0;
					$bot = 1;
					break;
				}
			}
		}
	}

	// Nyt kun vihdoin p��stiin kirjoittamaan tiedostoon...
	if($write){
		// Luodaan IP-numero tyyliin: 3E91BF04
		$ip_str = short_ip($ip);
		// Tiedosto jossa on kaikki IP:t, jotka alkavat samalla numerolla:
		$base_ipfile = $_PKSTAT['hak']."/fastdata_uniq";
		$ipfound = 0;
	
		if(file_exists($base_ipfile)){
			// Onko IP jo tiedostossa:
			if(strpos(file_get_contents($base_ipfile), $ip_str) !== false){
				$ipfound = 1;
			}
		}

		$filu = @fopen($tamapaiva, "a");
		@fwrite($filu, time()."|$ip|$host|$prev|$nyky|$agent|$kieli|\r\n");
		@fclose($filu);
		
		// Kasvatetaan nopeampaa latauslaskuria:
		save_fastdata("load", ".");
		// Tallennettaan viimeisimm�n sivunlatauksen aikaleima:
		modify_stat("time_last_load", "savetime");
		// Tarkistetaan onko k�vij�rajan pyk�l� ylittynyt (lataukset):
		stat_rajarikki(filesize($_PKSTAT['hak']."/fastdata_load")+get_value("yht_load"), 0);

		// Jos uusi k�vij� on ladannut sivua, eli IP:t� ei l�ytynyt vanhasta tiedostosta:
		if(!$ipfound){
			// Muistetaan k�ytt�j�n IP: (eli sama k�ytt�j� ei tule t�h�n silmukkaan en�� toista kertaa)
			save_fastdata("uniq", $ip_str." ");
			// Tallennettaan viimeisimm�n uniikin k�vij�n aikaleima:
			modify_stat("time_last_uniq", "savetime");
			// Tarkistetaan onko k�vij�rajan pyk�l� ylittynyt (uniikit):
			stat_rajarikki(intval(filesize($_PKSTAT['hak']."/fastdata_uniq")/9)+get_value("yht_uniq"), 1);
		}
	}

	if(!$bot){
		// Koitetaan jos ollaan tehty k�ytt�j�t online enn�tys
		//  Samalla ajetaan online-laskuri:
		$nyt_online = ffs_users_online();
		if(modify_stat("top_online", "compare", $nyt_online)){
			// Jos enn�tys oli tehty, tallennetaan enn�tys, sek� IP-lista my�hemp�� tarkkailua varten:
			$online = glob($_PKSTAT['m'].$_PKSTAT['hak']."/online/*");
			if(is_array($online)){
				$filu = @fopen($_PKSTAT['hak']."/top_online_ipdata", "w");
				@flock($filu, LOCK_EX);
				foreach($online as $tiedosto){
					$base = basename($tiedosto);
					@fwrite($filu, substr($base, 8)."|".decode_short_ip(substr($base, 0, 8))."|\r\n");
				}
				@flock($filu, LOCK_UN);
				@fclose($filu);
			}
		}
		modify_stat("top_online_today", "compare", $nyt_online);
	}
}


ffs_count_stats();


?>