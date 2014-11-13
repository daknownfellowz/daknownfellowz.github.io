<?php include("yla.htm"); ?>

<div class="content">
<h1>UUTISET</h1>

<p>5.4.2006 &nbsp;<b>Alppikuvat tulevat ja pian!!</b></p> 

<p>21.11.2005 &nbsp;<b>DVD valmis: <a href="http://koti.mbnet.fi/anssi78/etusivuroska/roadtripkansi.html" target="WWW" onclick="window.open('','WWW','width=800,height=600,resizable=0,scrollable=no');">Roadtrip 2005: Länsi-Suomen kiertue</a>.</b></p>

<p>15.11.2005 &nbsp;<b>Roadtrip 2005 traileri julkaistu</b><br>


<p><b>5.8.2005  &nbsp;&nbsp;Ankkarock 2005 -<u>Huomenna</u>- 6.8.</b> Skynet osallistuu tilaisuuteen neljän miehen voimin.</p></b>

<p><b>3.6.2005  &nbsp;&nbsp;RISTEILYLLE -<u>TÄNÄÄN</u>-!!</b> Tallink Romantika klo 18.30 -> Vedetääs naamat! :D</p></b>

<b>13.5.2005  &nbsp;&nbsp;Risteilyä pukkaa! Tallink Romantika xx.06.2005-xx.06.2005<br></b>
<p>Fanien takii pvm viel avoin. Odotettavissa arvokasta kuvamatskuu skynetiinki tolt reissult :P<br>

<b><br>14.2.2005  &nbsp;&nbsp;GooM 2005 kuvat julkaistu!<br></b>
<p>Odotus palkitaan - GooM 2005 kuvat julkaistu tällä päivämäärällä kaiken kansan ihailtavaksi ja kommentoitavaksi! Varsinkin teille jotka menitte missaamaan noin mahtawan reissun (hävetkää..).<br>
  <br>
  <span class="style1"><b>13.2.2005</b>Foorumi kondiksessa! K&auml;yk&auml;&auml;s helvetiss&auml; pist&auml;m&auml;ss&auml; painava sananne.</span>
<p><b>13.2.2005  &nbsp;&nbsp;Skynet käyntiin todenteolla!</b>
    <br>        
  <p>Voi vattujen kevät! Skynetin alkutaival on ollut "lievästi" kankea. Kerralla marraskuussa
            ladattiin matsku (lähinnä se Hytösen kans väsätty logo+pari tyhjää sivuu..) verkkoon ja sen koommin miettimättä. Sillä sipuli. Saitti julkaistiinki
            lähinnä just logon takia, hah. Noh, Goom05 tuli ja meni (helvetin hyvä reissu muuten!) ja taas löytyi sen
            verran HC-kuvamateriaalia, että eiköhän sen voimalla seuraavat puol vuotta pidennetä tänki sivuston elämää... :D  nyt vaan odottelemaa
            et millo ne saadaa tänne kansan näytille. Seuraava reissu jolta odotettavissa korkeatasoista valokuvamateriaalia on 18.-20.2.2005 tapahtuva Himokselle suuntaava yhdistetty snoukkaus & afterski -matka.

<p><b>22.11.2004 </b>SKYNET ACTIVATED</p>

</div>
<div class="links">
<br>
<b>Hesarin uutiset tänään</b>

<br><br>

<?php
$uutiset = new HS_top_news();
$uutiset = null;
class HS_top_news {
/*
######  HS_top_news class ####
    * @version 1 22.03.2003
    * @copyright neon <neon@neon-line.net>
    * DESCRIPTION: Hakee uusimmat/tärkeimmät uutiset HS:n sivuilta ja parsii ne.
    * CHANGELOG:
        - parser kohtaan lisätty muutama kommentti pienehköksi manuaaliksi
        - errormessageja.....
################################
*/
    var $parser;
    /*
     * @resource XMLparser
     */
    var $tag;
    /*
     * @string viimeinen käsitelty merkki
     */
    var $result = array();
     /*
      * @array itse lopullinen data
      */
    var $uutinen = array();
     /*
      * @array sisäinen datakeeper
      */

    /* PUBLIC */
    function HS_top_news() {
        if(!function_exists('xml_parse'))
            exit('Järjestelmässäsi ei ole XML SAX-parser ominaisuuksia, ohjelma lopetetaan.');
        $url = "http://siirto.helsinginsanomat.fi/aukio/HS-Tuoreet-Top5.xml";
        if(function_exists("file_get_contents")):
            $data = @file_get_contents($url);
        else:
            $fp = @fopen($url,"r");
            $data = @fread($fp,4086);
            @fclose($fp);
        endif;
        if(!empty($data))
            $this->parse($data);
        else
            exit("Kohteesta $url ei saatu tietoja");
        return;
    }
    /* PRIVATE */
    function parse($xmlstring) {
    /* luodaan parser */
        $this->parser = xml_parser_create();
    /* Asetetaan mikä muuttuja sisältää xml parserin ja missä luokassa parser toimii */
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
    /* parsittaessa tagin alkaessa kutsutaan startElement metodi ja tagin loppuessa endElement metodi */
        xml_set_element_handler($this->parser, "startElement", "endElement");
    /* characterData käsittelee varsinaisen datan tagien välissä */
        xml_set_character_data_handler($this->parser, "characterData");
    /* parsitaan */
        xml_parse($this->parser, $xmlstring);
    $this->__print();
    /* vapautetaan parser, pakko vapauttaa koska tätä ei automaattisesti tapahdu */
        xml_parser_free($this->parser);
    }
    /* PRIVATE */
    function __print()
    {
    ksort($this->result);
    $this->result = array_reverse($this->result);
    echo "<table>\n";
    foreach($this->result as $data):
        echo $data;
    endforeach;
    echo '</table>';
    return;
    }
    /* PRIVATE */
    function startElement($parser, $name, $attrs) {
    switch($name):
        case "url":
        case "pvm":
        case "otsikko":
            $this->tag = $name;
        break;
    endswitch;
    }
    /* PRIVATE */
    function endElement($parser, $name) {
    if($name == $this->tag)
        $this->tag==null;
      if($name == "uutinen"):
        $part =  "\t <tr>\n\t\t".'<td bgcolor="#00659A">'."\n\t\t\t".'<a href="'.$this->uutinen[1].'" class="linkit"><font face="verdana" color="lightblue" size="1">'.
                          $this->uutinen[2].'</font></a>'."\n\t\t</td>\n\t</tr>\n\t<tr>\n\t\t".'<td bgcolor="#DCEAFD">'."\n\t\t\t".'<font face="verdana" size="0.3">('.$this->uutinen[0].')</font>'."\n\t\t</td>\n\t</tr>\n";
        $this->result[$this->uutinen[4]] = $part;
        $this->uutinen[2] = null;
    endif;
     }
    /* PRIVATE */
    function characterData($parser, $data) {
    $data = trim($data);
    if(!empty($data) && $data != "\n"):
        switch($this->tag):
            case "url":
                $this->uutinen[1] = $data;
            break;
            case "pvm":
                $this->uutinen[0] = $data;
                $id = str_replace(":","",str_replace("-","",str_replace(" ","",$data)));
                $this->uutinen[4] = $id;
            break;
            case "otsikko":
                $this->uutinen[2] .= $data;
            break;
        endswitch;
    endif;
    }
}
?>
</div>

<?php
include("pkstat_count.php"); 
 include("ala.htm"); ?>