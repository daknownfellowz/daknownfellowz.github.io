<?php // pkstat_admin.php


include_once("pkstat_inc.php");

////////////////////////////////////
//    K ƒ V I J ƒ L A S K U R I
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


ob_start();

function is_logged(){
	global $_PKSTAT;
	if($_COOKIE['pkstat_login'] == md5($_PKSTAT['salasana'])){
		return 1;
	}else{
		return 0;
	}
}

$PKFILE = "pkstat_admin.php";

if($act == "logout"){
	setcookie("pkstat_login");
	header("Location: {$PKFILE}");
}

if(!is_logged()){
	print"<FORM METHOD=\"POST\" ACTION=\"{$PKFILE}?act=kirjaudu\">";
	print"Salasana: <INPUT TYPE=\"password\" NAME=\"salasana\">";
	print"<INPUT TYPE=\"submit\" value=\"Kirjaudu\">";
	print"</FORM>";
	if($act == "kirjaudu"){
		if($_POST['salasana'] == $_PKSTAT['salasana']){
			setcookie("pkstat_login", md5($_PKSTAT['salasana']));
			header("Location: {$PKFILE}");
		}else{
			print"<HR><B>Salasana v‰‰rin</B>";
		}
	}
}else{
	if($_PKSTAT['log_nolog'] == "1") $ch1 = " checked"; else $ch1 = "";
	if($_PKSTAT['log_ziplog'] == "1") $ch2 = " checked"; else $ch2 = "";
	if($_PKSTAT['log_range'] == "1") $ch3 = " checked"; else $ch3 = "";
	if($_PKSTAT['log_alasalli'] == "1") $ch4 = " checked"; else $ch4 = "";

	$rangeval = $_PKSTAT['log_rangevalue'];
	if(!$rangeval) $rangeval = 30;

	print"<B style=\"font:24px verdana\">FFS Multicounter - Admin paneeli</B> ( <A HREF=\"{$PKFILE}?act=logout\">Kirjaudu ulos t‰st‰</A>, tai sulkemalla ikkuna )<BR><BR>";

	if($act == ""){
		print"<A HREF=\"{$PKFILE}?act=banbots\">IP-osoitteiden Estolista</A><br><hr>";
		print"<FORM METHOD=\"POST\" ACTION=\"{$PKFILE}?act=save\">";
		print"<INPUT TYPE=\"checkbox\" NAME=\"pkstat_tilastointi\" value=\"1\"$ch1>Kytke tilastointi pois p‰‰lt‰<BR>";
		print"<HR>";
		print"<INPUT TYPE=\"checkbox\" NAME=\"pkstat_tilastozip\" value=\"1\"$ch2>K‰yt‰ pakattua tilastojen s‰ilˆnt‰‰ (s‰‰st‰‰ noin 50-90% levytilasta, mutta saattaa vaikeuttaa niiden lukemista)<BR>";
		print"<INPUT TYPE=\"checkbox\" NAME=\"pkstat_tilastorange\" value=\"1\"$ch3>Tallenna tilastoja vain viimeisten <INPUT TYPE=\"text\" NAME=\"pkstat_rangeval\" value=\"$rangeval\" size=\"3\" maxlength=\"3\"> p‰iv‰n ajalta<BR>";
		print"<BR>";
		print"<INPUT TYPE=\"checkbox\" NAME=\"pkstat_alasalli\" value=\"1\"$ch4>ƒl‰ salli kenenk‰‰n muun kuin adminin lukea <A HREF=\"{$PKFILE}?act=showstats\">lataushistoriaa</A><BR>";
		print"<BR>";
		print"<INPUT TYPE=\"submit\" value=\"Tallenna asetukset\">";
		print"</FORM>";
	}

	if($act == "save"){
		$data = array();
		$data[] = (int)$_POST['pkstat_tilastointi'];
		$data[] = (int)$_POST['pkstat_tilastozip'];
		$data[] = (int)$_POST['pkstat_tilastorange'];
		$data[] = sprintf("%03d", $_POST['pkstat_rangeval'] > 999 ? 999 : $_POST['pkstat_rangeval']);
		$data[] = (int)$_POST['pkstat_alasalli'];
		$rivi = implode("", $data);

		if($filu = @fopen($_PKSTAT['admin_file'], "w")){
			fwrite($filu, $rivi);
			header("Location: {$PKFILE}?show=done");
		}else{
			header("Location: {$PKFILE}?show=error");
		}
		@fclose($filu);
	}
	
	if($act == "banbots"){
		$sisalto = @file_get_contents($_PKSTAT['hak']."/admin_banlist");
		print"<A HREF=\"{$PKFILE}\">Tilastoinnin asetukset</A><BR><HR><BR>";
		print"<FORM METHOD=\"POST\" ACTION=\"{$PKFILE}?act=savebot\">";
		print"Kirjoita riveitt‰in estett‰v‰ IP tyyliin: <B>63.127.255.51</B> tai: <B>63.127.255</B>, jolloin viimeist‰ numerosarjaa ei huomioida.<BR><BR>";
		print"<TEXTAREA NAME=\"pkstat_banlist\" ROWS=\"10\" COLS=\"40\">$sisalto</TEXTAREA><BR><BR>";
		print"<INPUT TYPE=\"submit\" value=\"Tallenna asetukset\">";
		print"</FORM>";
	}

	if($act == "savebot"){
		
	}

	if($show == "done"){
		print"Asetukset tallennettiin!";
	}elseif($show == "error"){
		print"Tapahtui virhe, eik‰ asetuksiasi voitu tallentaa.<br>Tarkista tiedostojen ja hakemistojen oikeudet";
	}
}

if($act == "showstats"){
	if($_PKSTAT['log_alasalli'] != "1" || is_logged()){
		
	}
}



ob_end_flush();

?>