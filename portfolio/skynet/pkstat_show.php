<?php // pkstat_show.php
// HUOM! t‰m‰ sivu ainoastaan NƒYTTƒƒ tilastoja

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
//
// T‰m‰n tiedoston ohjeet:
// - K‰yt‰ include-toimintoa n‰ytt‰‰ksesi laskurin tilastoja:
//      include("pkstat_show.php");
// - Liit‰ kyseinen include kaikille niille sivuille miss‰ haluat laskurin n‰ytt‰v‰n statistiikkaa
//
////////////////////////////////////





$file_start = $_PKSTAT['hak']."/aika.start";

if(file_exists($file_start)){
	$start = file_get_contents($file_start);

	// T‰m‰n p‰iv‰n tilastot...
	$nyt_uniq = intval(filesize($_PKSTAT['hak']."/fastdata_uniq")/9);
	$nyt_load = filesize($_PKSTAT['hak']."/fastdata_load");
	$time_uniq = file_get_contents($_PKSTAT['hak']."/time_last_uniq");
	$time_load = file_get_contents($_PKSTAT['hak']."/time_last_load");

	$nyt_online = count(glob($_PKSTAT['m'].$_PKSTAT['hak']."/online/*"));

	// Kaikkien aikojen tilastot...
	@$yht_uniq = $nyt_uniq+get_fileval("yht_uniq", $null);
	@$yht_load = $nyt_load+get_fileval("yht_load", $null);
	if(!$yht_uniq) $yht_uniq = $nyt_uniq;
	if(!$yht_load) $yht_load = $nyt_load;

	// Enn‰tystilastot...
	@$top_uniq = get_fileval("top_uniq", $time_top_uniq);
	@$top_load = get_fileval("top_load", $time_top_load);
	$top_online = get_fileval("top_online", $time_top_online);
	
	// Aika viimeisimm‰st‰ uniikista k‰vij‰st‰, tai sivunlatauksesta:
	$time_uniq = date("H:i:s", $time_uniq);
	$time_load = date("H:i:s", $time_load);

	// Aika enn‰tyksen teosta:
	$time_top_uniq = date("j.n.Y", $time_top_uniq);
	$time_top_load = date("j.n.Y", $time_top_load);
	$time_top_online = date("j.n.Y - H:i:s", $time_top_online);

	// Jos t‰m‰n p‰iv‰n statsit ovat suurempia kuin muina p‰ivin‰ koskaan:
	if($nyt_uniq > $top_uniq){
		$top_uniq = $nyt_uniq;
		$time_top_uniq = $time_uniq;
	}
	if($nyt_load > $top_load){
		$top_load = $nyt_load;
		$time_top_load = $time_load;
	}

	$loads_per_user = ceil($yht_load/$yht_uniq);

	// Aika jolloin laskuri k‰ynnistettiin:
	$time_start = date("j.n.Y", $start);

	// Kuinka monta p‰iv‰‰ laskuri on ollut toiminnassa:
	$time_toiminta = floor((mktime(0, 0, 0, date("n"), date("j"), date("Y"))-mktime(0, 0, 0, date("n", $start), date("j", $start), date("Y", $start)))/86400);

	$ave_load = round($yht_load/($time_toiminta+1));
	$ave_uniq = round($yht_uniq/($time_toiminta+1));
	$not = 0;
}else{
	$not = 1;
}

if($not){
	print"<B>Laskuri ei ole viel‰ toiminnassa tai k‰vijˆit‰ ei ole viel‰ tullut.</B><br>Jos t‰m‰ viesti ei l‰hde pois katsomalla \"pkstat_count.php\" tiedostoa selaimella, niin tarkista tiedostojen ja hakemistojen oikeudet.";
}else{

/////////////////////////////////////////////
//
// T‰st‰ alasp‰in voit tehd‰ mit‰ lyst‰‰.
//
// Muuttujanimet k‰yttˆ‰ varten:
//  $nyt_uniq / $nyt_load = Uniikit/lataukset t‰n‰‰n
//  $yht_uniq / $yht_load = Uniikit/lataukset koko ajalta
//  $top_uniq / $top_load = Enn‰tykset uniikeista/latauksista
//  $time_uniq / $time_load = Aika viimeisimm‰st‰ uniikista/latauksesta
//  $time_top_uniq / $time_top_load = Aika uniikkien/latauksien enn‰tyksist‰
//  $time_start = Aika jolloin laskuri k‰ynnistettiin
//  $time_toiminta = Kuinka monta p‰iv‰‰ laskuri on ollut toiminnassa
//  $nyt_online = K‰ytt‰j‰t online nyt
//  $top_online = Online enn‰tys m‰‰r‰
//  $time_top_online = Aikaleima online-enn‰tyksest‰
//
/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////

print"<font style=\"font:12px verdana; COLOR: #D1CECE\">

Laskuri k‰ynnistetty: $time_start<br>
Laskuri toiminnassa: $time_toiminta p‰iv‰‰<br>
Viimeisin k‰vij‰: $time_uniq<br>
Viimeisin sivunlataus: $time_load<br>
K‰vij‰enn‰tys: $time_top_uniq<br>
Latausenn‰tys: $time_top_load<br>
Online-enn‰tys: $time_top_online<br>
Latauksia keskim‰‰rin: $loads_per_user per k‰vij‰<br>
Keskim‰‰rin $ave_uniq k‰vij‰‰ p‰ivitt‰in<br>
Keskim‰‰rin $ave_load latausta p‰ivitt‰in<br>
<hr>


<b>T‰n‰‰n:</b><br>
K‰vij‰t: $nyt_uniq<br>
Lataukset: $nyt_load<br>
<b>Yhteens‰:</b><br>
K‰vij‰t: $yht_uniq<br>
Lataukset: $yht_load<br>
<b>P‰ivitt‰isin suurin:</b><br>
K‰vij‰t: <span title=\"Enn‰tys tehtiin aikana: $time_top_uniq\" style=\"cursor:help\">$top_uniq</span><br>
Lataukset: <span title=\"Enn‰tys tehtiin aikana: $time_top_load\" style=\"cursor:help\">$top_load</span><br>
<b>Online:</b><br>
Nyt: $nyt_online<br>
Enn‰tys: <span title=\"Enn‰tys tehtiin aikana: $time_top_online\" style=\"cursor:help\">$top_online</span><br>
<BR>


<hr>
<br>
Graafiset:<BR>
K‰vij‰t: <img src=\"pkstat_grafcount.php?type=0\" border=\"0\"><BR>
Lataukset: <img src=\"pkstat_grafcount.php?type=1\" border=\"0\"><BR>
<br>
P‰ivitt‰iset tilastok‰yr‰t viimeisilt‰ kahdelta kuukaudelta:<br>
<BR>
K‰vij‰t:<BR>
<img src=\"pkstat_kayra.php?type=0\" border=\"0\"><BR>
<BR>
Lataukset:<BR>
<img src=\"pkstat_kayra.php?type=1\" border=\"0\"><BR>
<BR>
<HR>

</font>
";

/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////

} // ƒl‰ koske t‰h‰n.

?>