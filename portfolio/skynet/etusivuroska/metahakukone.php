<?php 
// metahakukone.php

$hakusanat = '';

// Jos hakusanat syötetty, kutsutaan valittua hakupalvelua,
// muutoin näytetään syöttölomake
if (isset($_GET['hakusanat']))
{
	// Mahdolliset ylimääräiset kenoviivat pois
	// Magic_quotes_gpc-asiaa käsitellään merkkijonoja käsitt. luvussa
	if (ini_get('magic_quotes_gpc')) {
		$hakusanat = stripslashes($_GET['hakusanat']);
	} else {
		$hakusanat = $_GET['hakusanat'];
	}
	ohjaa($_GET['haku'], $hakusanat);
} else {
	do_header();
	lomake();
	do_footer();
}

// Alkumuodollisuudet
function do_header()  
{
	echo "<title>Metahakupalvelu</title>" .
		 "<h3 style=\"border-top: solid thin black;" .
		 "color:#000;background-color:#eee\">" .
		 "Metahakupalvelu</h3>";
}

// Loppumuodollisuudet
function do_footer()
{
	echo "</body>\n" .
		 "</html>\n";
}

// Syöttölomake
function lomake()
{
?>
<form method="get" action="<? echo $_SERVER['PHP_SELF'] ?>">
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td bgcolor="#eeeeee"><input type="text" name="hakusanat" />
		<input type="submit" value="Etsi" name="painike" /></td>
	</tr>
	<tr>
		<td bgcolor="#eeeeee">
		<select name="haku" size="1">	
			<option selected value="1">Google</option>	
			<option value="2">&nbsp;&nbsp;==&gt;Groups</option>
			<option value="3">&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;==&gt;kirjoittajahaku</option>
			<option selected value="4">Altavista</option>
		</select>				
		</td>
	</tr>
	</table>
</form>
<?php
}

// Funktio "ohjaa" selaimen $haku-parametrin mukaiseen
// hakukoneeseen mukanaan syötetyt hakusanat asianmukaisesti muotoiltuna (mm. URL-koodattuna).
function ohjaa($haku, $hakusanat)
{
	$hakujono = urlencode(utf8_encode($hakusanat));
	$kokojono = "Location: ";
	
	if($haku == "1") {
		$kokojono .= "http://www.google.com/search?q=$hakujono";
		header($kokojono);
	} elseif($haku == "2") {
		$kokojono .= "http://groups.google.com/groups?q=$hakujono";
		header($kokojono);
	} elseif($haku == "3") {
		$kokojono .= "http://groups.google.com/";
		$kokojono .= "groups?as_uauthors=$hakujono";
		header($kokojono);
	} else {
		$kokojono .= "http://www.altavista.com/web";
		$kokojono .= "/results?q=$hakujono";
		header($kokojono);		
	}
}

?>

	
		


		
		
