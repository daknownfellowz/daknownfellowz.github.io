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
        $part =  "\t <tr>\n\t\t".'<td bgcolor="#00659A">'."\n\t\t\t".'<a href="'.$this->uutinen[1].'"><font face="verdana" color="lightblue" size="1">'. 
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

include("pkstat_count.php"); 
?> 