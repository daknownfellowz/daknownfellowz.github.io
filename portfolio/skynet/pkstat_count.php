<?php // pkstat_count.php


include_once("pkstat_inc.php");

////////////////////////////////////
//    K Ä V I J Ä L A S K U R I
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
// - Kävijät yhteensä 
// - Kävijät tänään 
// - Sivunlataukset yhteensä 
// - Sivunlataukset tänään 
// - Käyttäjät online 
// - Päivittäisin suurin kävijämäärä 
// - Päivittäisin suurin sivunlatausmäärä 
// - Päivittäisin suurin online määrä 
// - Aikaleimat ennätyksistä 
// - Viimeisin kävijä / lataus -aikaleima 
// - Milloin laskuri käynnistettiin, ja kuinka kauan se on ollut toiminnassa 
// - Keskimääräiset kävijämäärät päivittäin 
// - Keskimääräiset lataukset per kävijä 
// - Kaikki kävijäennätykset 100 kävijän tai 1000 latauksen välein 
// 
// Ominaisuudet: 
// - Graafinen kävijälaskuri 
// - Graafinen tilastokäyrä 
// - Kävijätilastojen selaaminen ja haku 
// - Mahdollisuus luoda oma kuva kävijälaskuriin 
// - Mahdollisuus käyttää pakattua tilastojen säilöntää 
// - Mahdollisuus kytkeä tilastointi pois päältä 
// - Mahdollisuus tallentaa tilastoja vain viimeisiltä 30 päivän ajalta
//
////////////////////////////////////
//
// Ohjeet:
// - Luo juureen kansio: "pkstats"
// - Luo kansion "pkstats" sisälle kansiot: "online" ja "tilastot"
//
// - Chmodaa KAIKKI yllä mainitut 3 kansiota arvolla 777
//
// - Laita juureen seuraavat tiedostot:
//    pkstat_inc.php
//    pkstat_count.php
//    pkstat_show.php
//    pkstat_grafcount.php
//    pkstat_kayra.php
//    pkstat_admin.php
//
// - Käytä include-toimintoa liittääksesi laskurin sivuillesi:
//      include("pkstat_count.php");
// - Liitä kyseinen include kaikille niille sivuille joilta haluat laskurin keräävän statistiikkaa
// - Liitä "pkstat_show.php" niille sivuille joilla haluat laskurin näyttävän eri tilastot
//
//
// Nyt sinulla pitäisi olla seuraavanlainen hakemistorakenne:
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
// Jos laskurisi ei toimi tai se heittää virhettä:
// - Tarkista onko hakemistorakenne sama.
// - Tarkista että olet antanut kaikille kansioille 777 CHMOD oikeudet jollain FTP-ohjelmalla.
// - Jos virhe ei mene ohi vaikka kansioilla on tarvittavat oikeudet:
//     - Anna myös kansioiden sisällä oleville tiedostoille erikseen 777 CHMOD oikeudet.
// - Jos sama virhe toistuu edelleen, tarkista että olet noudattanut ohjeita täsmällisesti.
// - Muussa tapauksessa ota yhteyttä: hctm at suomi24 piste fi
//
////////////////////////////////////




// Tyhjää vanhojen statsien tiedostot, säilyttää niiden päivien lukemat.
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

// $file_from: mikä tiedosto pakataan
// $file_to: mihin pakattu tiedosto tallennetaan
// $mem_usage: kuinka paljon muistia saa käyttää korkeintaan:
// --- esim jos $mem_usage on 1000, täten muistia käytetään korkeintaan 1000*1024 tavua
function file_compress($file_from, $file_to, $mem_usage = 1000){
	// Ei ylikirjoiteta olemassaolevaa tiedostoa:
	if(!file_exists($file_to)){
		$p = 0;
		$data = "";
		$fp = fopen($file_from, "r");
		$save = fopen($file_to, "w");
		fwrite($save, "‰FFSGZ\n"); // formaatin tunniste :)
		while(!feof($fp)){
			// luetaan 1024 tavua:
			$data .= fread($fp, 1024);
			// Jos käytettävissä oleva muistin määrä on ylitetty:
			if($p > $mem_usage){
				// Pakataan data:
				$zip = gzcompress($data);
				// Tallennetaan data tiedostoon, joka koostuu tyyliin: "5:seppo6:heikki5:keijo"
				// Eli merkkijonon pituus haetaan kaksoispistettä ennen.
				$len = strlen($zip);
				fwrite($save, $len.":".$zip);
				$data = "";
				$p = 0;
			}
			$p++;
		}
		if($data != ""){
			// Jos jäi tähteitä, lisätään nekin pakkaukseen:
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

// Tarkistaa onko jokin kävijäraja rikottu:
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

// Tuottaa tasan 8 merkkiä pitkän IP-osoitteen tyyliin: 50BA3449
function short_ip($ip){
	$osa = explode(".", $ip);
	return substr(sprintf("%02X%02X%02X%02X", (int)$osa[0], (int)$osa[1], (int)$osa[2], (int)$osa[3]), 0, 8);
}

// Purkaa koodatun IP-osoitteen:
function decode_short_ip($ip){
	return hexdec(substr($ip, 0, 2)).".".hexdec(substr($ip, 2, 2)).".".hexdec(substr($ip, 4, 2)).".".hexdec(substr($ip, 6, 2));
}

// Oletuksena 120 sekunnin aikaraja (älä vaihda)
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
				// Päivitetään tiedot jos sinä lataat sivun toistamiseen:
				@rename($tiedosto, $kansio."/".$file_ip.$aika);
				$find = 1;
			}elseif($aika - $file_time > $aikaraja){
				// Tuhotaan sen käyttäjän tiedot jonka aikaraja on umpeutunut:
				if(@unlink($tiedosto)){
					$online--;
				}
			}
		}
	}
	if(!$find){
		// Jos et ollut ladannut sivua vielä, luodaan tiedosto:
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

	// Jos päivä on vaihtunut...
	if(!file_exists($tamapaiva)){
		if($_PKSTAT['log_range'] && $_PKSTAT['log_nolog'] != "1"){
			// Tyhjätään vanhat tilastot:
			clear_old_stats();
		}

		// Haetaan eilinen päivä:
		$glob_today = glob($_PKSTAT['m'].$_PKSTAT['hak']."/*.today");
		$eilen = $glob_today[0];

		if(file_exists($eilen)){
			// Lasketaan eilisen uniikit kävijät:
			$uniks = intval(filesize($_PKSTAT['hak']."/fastdata_uniq")/9);

			// Lasketaan sivunlataukset:
			$loads = filesize($_PKSTAT['hak']."/fastdata_load");
			
			// Haetaan suurin online eiliseltä päivältä:
			$ton = file($_PKSTAT['hak']."/top_online_today");
			$tod_online = (int)$ton[0];

			$tilanimi = $_PKSTAT['hak']."/tilastot/".basename($eilen, ".today")."-".$uniks."-".$loads."-".$tod_online;

			$etsipaiva = glob($_PKSTAT['m'].$_PKSTAT['hak']."/tilastot/".basename($eilen, ".today")."*");
			$tilakayt = $etsipaiva[0];

			// Jos ei haluta logittaa:
			if($_PKSTAT['log_nolog']){
				// Varmistetaan ettei tilastoa ole jo luotu:
				if($tilakayt == ""){
					// Luodaan tyhjä tiedosto:
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
				// Lisätään eilinen päivä kaikkien aikojen tilastoon:
				modify_stat("yht_load", "add", $loads);
				modify_stat("yht_uniq", "add", $uniks);

				// Koitetaan jos ollaan tehty uusi ennätys:
				modify_stat("top_load", "compare", $loads);
				modify_stat("top_uniq", "compare", $uniks);

				// Nollataan päivittäiset laskurit:
				unlink($_PKSTAT['hak']."/fastdata_load");
				unlink($_PKSTAT['hak']."/fastdata_uniq");
				unlink($_PKSTAT['hak']."/top_online_today");
			}
		}

		// Jos aloituspäivämäärää ei ole tallennettu, tallennetaan se:
		$afile = $_PKSTAT['hak']."/aika.start";
		if(!file_exists($afile)){
			$filu = @fopen($afile, "w");
			@fwrite($filu, time());
			@fclose($filu);
		}
	}

	// Haetaan 2000 merkkiä tiedoston lopusta:
	$fp = @fopen($tamapaiva, "r");
	@fseek($fp, -2000, SEEK_END);
	$data = @fread($fp, 2000);
	@fclose($fp);

	// Jaetaan haettu tieto riveiksi taulukkoon:
	$rivi = explode("\r\n", $data);
	$ehja = 0;
	$eka = $rivi[0];

	// Tarkistetaan onko eka rivi ehjä:
	if(substr_count($eka, "|") == 7){
		if($eka[10] == "|"){
			$ehja = 1;
		}
	}

	// Jos eka rivi ei ollut ehjä, poistetaan se:
	if(!$ehja) array_shift($rivi);
	// Jos vika rivi on tyhjä, poistetaan se:
	if(trim(end($rivi)) == "") array_pop($rivi);

	// Tarkistussilmukka, joka tarkistaa onko sama käyttäjä ladannut
	//  identtisen sivun verraten kolmea edellistä sivunlatausta
	// Jos kaikki on identtistä paitsi aika, saa tiedostoon tallentaa jos
	//  aikaa on kulunut 5 sekuntia edellisestä sivunlatauksesta
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

	// Ei tehdä tätä turhaan jos kirjoitus on jo pois päältä:
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
			// Etsitään paholaisia hostista sekä selaintiedoista:
			foreach($botit as $value){
				if(stristr($host, $value) || stristr($_SERVER['HTTP_USER_AGENT'], $value)){
					$write = 0;
					$bot = 1;
					break;
				}
			}
		}
	}

	// Nyt kun vihdoin päästiin kirjoittamaan tiedostoon...
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
		// Tallennettaan viimeisimmän sivunlatauksen aikaleima:
		modify_stat("time_last_load", "savetime");
		// Tarkistetaan onko kävijärajan pykälä ylittynyt (lataukset):
		stat_rajarikki(filesize($_PKSTAT['hak']."/fastdata_load")+get_value("yht_load"), 0);

		// Jos uusi kävijä on ladannut sivua, eli IP:tä ei löytynyt vanhasta tiedostosta:
		if(!$ipfound){
			// Muistetaan käyttäjän IP: (eli sama käyttäjä ei tule tähän silmukkaan enää toista kertaa)
			save_fastdata("uniq", $ip_str." ");
			// Tallennettaan viimeisimmän uniikin kävijän aikaleima:
			modify_stat("time_last_uniq", "savetime");
			// Tarkistetaan onko kävijärajan pykälä ylittynyt (uniikit):
			stat_rajarikki(intval(filesize($_PKSTAT['hak']."/fastdata_uniq")/9)+get_value("yht_uniq"), 1);
		}
	}

	if(!$bot){
		// Koitetaan jos ollaan tehty käyttäjät online ennätys
		//  Samalla ajetaan online-laskuri:
		$nyt_online = ffs_users_online();
		if(modify_stat("top_online", "compare", $nyt_online)){
			// Jos ennätys oli tehty, tallennetaan ennätys, sekä IP-lista myöhempää tarkkailua varten:
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