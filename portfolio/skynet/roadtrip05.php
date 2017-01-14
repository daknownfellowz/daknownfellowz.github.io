<?php include("yla.htm"); ?>


<div class="links">
<!-- <table width="324" height="108" border="0">
  <tr>
    <td width="220" align="top">-->&nbsp;<b>- ROADTRIP 18.-24.7. -</b>
	<script language="JavaScript1.2">

	/*
	DOM XML ticker- © Dynamic Drive (www.dynamicdrive.com)
	For full source code, 100's more DHTML scripts, and Terms Of Use, visit http://www.dynamicdrive.com
	Credit MUST stay intact
	*/

	//Container for ticker. Modify its STYLE attribute to customize style:
	var tickercontainer='<div id="container" style="background-color:#6686C9;width:150px;height:80px;font:normal 13px Verdana;"></div>'

	//Specify path to xml file
	var xmlsource="ticker.xml"

	////No need to edit beyond here////////////
	//load xml file
	if (window.ActiveXObject)
	var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
	else if (document.implementation && document.implementation.createDocument)
	var xmlDoc= document.implementation.createDocument("","doc",null);
	if (typeof xmlDoc!="undefined"){
	document.write(tickercontainer)
	xmlDoc.load(xmlsource)
	}

	//Regular expression used to match any non-whitespace character
	var notWhitespace = /\S/

	function init_ticker(){
	//Cache "messages" element of xml file
	tickerobj=xmlDoc.getElementsByTagName("xmlticker")[0]

	//REMOVE white spaces in XML file. Intended mainly for NS6/Mozilla
	for (i=0;i<tickerobj.childNodes.length;i++){
	if ((tickerobj.childNodes[i].nodeType == 3)&&(!notWhitespace.test(tickerobj.childNodes[i].nodeValue))) {
	tickerobj.removeChild(tickerobj.childNodes[i])
	i--
	}
	}
	document.getElementById("container").innerHTML=tickerobj.childNodes[1].firstChild.nodeValue
	msglength=tickerobj.childNodes.length
	currentmsg=2
	themessage=''
	setInterval("rotatemsg()",tickerobj.childNodes[0].firstChild.nodeValue)
	}

	function rotatemsg(){
	var msgsobj=tickerobj.childNodes[currentmsg]
	if (msgsobj.getAttribute("url")!=null){
	themessage='<a href="'+msgsobj.getAttribute("url")+'"'
	if (msgsobj.getAttribute("target")!=null)
	themessage+=' target="'+msgsobj.getAttribute("target")+'"'
	themessage+='>'
	}
	themessage+=msgsobj.firstChild.nodeValue
	if (msgsobj.getAttribute("url")!=null)
	themessage+='</a>'

	//Rotate msg and display it in DIV:
	document.getElementById("container").innerHTML=themessage
	currentmsg=(currentmsg<msglength-1)? currentmsg+1 : 1
	themessage=''
	}

	function fetchxml(){
	if (xmlDoc.readyState==4)
	init_ticker()
	else
	setTimeout("fetchxml()",10)
	}

	if (window.ActiveXObject)
	fetchxml()
	else if (typeof xmlDoc!="undefined")
	xmlDoc.onload=init_ticker

</script>
</div>


<div class="content">
<p style="font-size: 18px"><span style="font-weight: bold">ROADTRIP 18.-24.7.2005 ERIKOISRAPORTTI</span></p>

<p><img src="Taustakyhays.jpg" width="290" height="236"></p>
<p>&nbsp;</p>
<p>Katso <a href="gallery/roadtrip05/gallery.php">KUVAT</a>! &nbsp;<a href="gallery/roadtrip05/gallery.php"><img src="camera_logo.gif" border="0"></a>&nbsp;</p>

<p>Reitti: Stadi -> Uusikaupunki -> Rauma -> Pori -> Kristiinankaupunki -> Vaasa -> Tampere -> Stadiin takas. Eli yhteens‰ seitsem‰n (7) tiukkaa p‰iv‰‰ juhlimista.</p>


<b><i>Kursivoituna otteet reissup‰iv‰kirjasta, joka laadittiin suoraan Skynetin foorumille</i></b>

<hr>
<b>Maanantai 18.7. Uusikaupunki</b>

<p>Reissuun l‰hettiin puolenp‰iv‰n j‰lkeen. HP haettiin Myyrm‰est‰ ja sitten matkaan. Ekana p‰‰m‰‰r‰n‰ oli Uusikaupunki.</p>

<p><i>Hannu        18.07.2005 17:14 <br>
Myn‰m‰elt‰ terve4p </i></p>

<p><i>Anssi Roadtripilt‰        18.07.2005 22:11  <br>
18.7.2005 Uusikaupunki, Hotelli L‰nnentie.<br>

Halloota kansalle! Terveisi‰ per...Uudestakaupungista!! T‰‰ on helvetin pien mesta, t‰‰l ei oo ket‰‰. Vehmaa, Myn‰m‰ki ja muut eHaanat pikkupit‰j‰t vilahti matkal ja kerran erehdyttii vet‰m‰‰n v‰h‰n turhaa rundiakin.. no mut onnistuneesti saavuttiin perille. Ainii, ‰sken p‰‰stii nauttimaan hianon L‰nnentiemme (www.hotelli-lannentie.fi) luxuksestakin eli saunoa ja uida pieness‰ mutta meille piisaavassa altaassa. Nyt v‰h‰n bilist‰ peliin, ei meinaan sen kummempaan viitti revet‰ n‰itten kahen koomapetterin kans jotka kotiutu Tuskasta eilen (iha heikkona kun viel‰ pojat liiasta viinan kiskomisesta :D). Huomenna l‰het‰‰n Porin Jatseille, jalalla koreasti vetelee ja jatsittelemaan (=k‰nni p‰‰lle).<br>
- Anssi (Jokinen ja HP hengess‰ mukana)</i></p>

<hr>

<b>Tiistai 19.7. Pori</b>

<p>Puolenp‰iv‰n j‰lkeen saavuimme sitten Poriin. Porin jatsit olivat sopivasti meneill‰‰n niin mik‰s siin‰ (tuntui tosin majotusten hinnoissa ;X). Normaalisti ilman jatsejahan Porihan on kuulemma aivan pystyynkuollut mesta. Mutta hyv‰lt‰ n‰ytti tuona tiistaina. Tiistain majapaikassamme, Hotelli Amadossa ei ollut mahdollisuutta nettiin eli reissup‰iv‰kirjamerkintˆj‰ ei ole.</p>

<hr>

<b>Keskiviikko 20.7. Pori</b>

<p>Keskiviikko on tunnetusti biletysp‰iv‰ eli meininki oli p‰‰ll‰. P‰iv‰ll‰ k‰vimme ensin lekottelemassa Yyterin rannoilla (harvinaisia aurinkoisia p‰ivi‰ tuolla reissulla). Illalla normaaliin tapaan menimme sitten 'hurvittelemaan' Porin yˆel‰m‰‰n.</p>

<p><i>Hannu        20.07.2005 17:06  <br>
Jazz for you!! Porin ch‰‰zzeisseiss‰ nautiskellaan! Yyterin sannat on myˆs jo vallotettu. Kohta kutsuu porin yˆ, ts‰‰zz yˆ! PalataA!  </i></p>

<hr>

<b>Perjantai 22.7. Vaasa</b>

<p><i>Roadtrippajat        22.07.2005 00:26 <br> 
Torstai 21.7. Kristiinankaupunki (pyrahdys) & Vaasa <br>

HEEIII!! NONNII!! Nyt on Pori jatetty taakse. Eilinn meni ryypiskellesa paikallisen yoelaman pyorteissa. Eksyttiin Kino nimiseen baariin. Happari oli sopivasti paalla jote lokeroo joutu tilaan molemmille kasille:D. Kaikkein paallekayvimmat eukot oli yllari STADISTA. llta oli satananmoista sadetta, mutta HP kuiteki onnistu karayttamaan ittesa Yyterissa paivalla, yyterin sannoilla lekoteltiin. Tanaa rullattii Kristiinan"kaupungin" lapi jossa ei muuta mielenkiintosta nahty ku Kesaravintola PAVIS :D hah. Metropolin jalkee siirryttii HP:n ohjastamina tanne Vaasaan, suomen aurinkoisimpaa kaupunkiin. Omenahotelli loistaa palveluillaa just. Tana iltana ohjelmassa oli painostava striptease hassakka Club Ladypink. Emme suosittele kenellekaan. Ettikaa googlesta tietoo,kuulema strippareitte keskiansio 2,5 e PAIVA! Yks 40v jehu havsi privat-takahuoneesee shampanjan kera, melkone parivaljakko ammineen:). Painostusta ja kuumotsta ja eiku helvettiiin Public Cornerin turvaan. Huomenna odottaaki jo perjantai,eikoha normimeininkii..Gigglin Marrlin tai sit PaNeen (Papinnena)... OITA!!!  </i></p>

<p><i>Roadaajapellet        22.07.2005 20:37  
22.7. Omenahotelli Vaasa <br>

Tuuli tuivertaa ja sade ropisee!!  Rossossa syotiin ranskalais jehun sponssaamat (50e) rillipihvit. buiston majotus porisa lattialt loytny, rahala. kikkeli marliniiii jaktkmaaa meininkii. vittu!!  nyt ryypataaa, vittu saatan, perkkele!!  vittu  taa omenahotlli npis on aivanpaska!!!!  
<br><br>
Perkeleet        22.07.2005 23:42  <br>
Saatana vittu! kanniii pukkaaa taal jengill... hirveet dokaamist vittu, keskiviikko ei ollu mitaa taaha verrattuna! elvettii nyt Kikkkeli Marliniin .. palataa  
<br><br>
rroadtrippaajat        23.07.2005 04:09  <br>
terkut heidille  ja mintulle!!!  
<br><br>
tripp        23.07.2005 04:11  <br>
martina aitolehti ja saija ketola kutsu meiat lavalle giggling marlinis ja sinne mentiii, sit tehtii oharit nill!! salarakkaat on jees! <br>
<br>
H        23.07.2005 04:14  <br>
....ikava jai Heidii...  <br>
<br>
anssi        23.07.2005 04:32  <br>
vittu ma sanon teille, ei se nii haappost vaasa ku h:n lapis luulis. vittu pekele, nyt lekottelee, ei sentaa ruusujen keskelle. vittu mika darra.. huomen lahtee tampereel. hp vaha masentuneena amuvarhaisel heh  
<br><br>
H        23.07.2005 04:36  <br>
oolliiha se nii heellmmee!!!!  <br>
<br>
</i></p>

<hr>

<b>Lauantai 23.7. Tampere</b>

<p><i>the last stand        23.07.2005 18:31  
Tampere 23.7 Omena hoteli <br>

Himmeen hyva stadi taas taa tampere!! helvetin hyva ensivaikutelma! Kadulla silmat pyorii ja paat kaantyy kun tamperelaiset naisett kavelee ohi...jumaleizzoon!! Ekat pullot on jo avattu, tosin tas vaihees ressuu ei saadaa enaa ku vitulliset vasymyskannit jos oikeen innostus. Otetaan lungimmin mut nautitaa kesasta (veda narusta - nauti kesasta). Matka oli aika vitun tuskanen, ja varsiki Tuurin Vesa Keskisen kylakaupas kaynti oli yht tuskaa siel ryysikses pelleiilees. Ttoivottavasti ei mee ylipanostuksen puolelle viimeinen ilta... ;)  
<br>
<br>
Matkustelijat        24.07.2005 04:37  
24.7. klo 04.27 about ja Tampere<br>

Tampere rulez!! Paras roadtripin mestoist. Tajutont settii Nighttrainis asken...
Aikasemmin lamiskaa stadissa, teinixit uskotteli miehett johki Coloradoo mut eiha me mitaa loydetty Nitetrainis oli jeez meno ja lamiska lahti! 
PS. Eevalle terkkuja, eHanaa jubailla :)  
</i></p>
<br><br>
</div>

<?php 
include("pkstat_count.php"); 
include("ala.htm"); ?>