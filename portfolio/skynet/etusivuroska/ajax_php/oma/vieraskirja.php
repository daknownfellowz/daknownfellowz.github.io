<?php include("yla.htm"); ?>

<?php

  //Tekstin siivous
  function Siivoa($teksti)
    {
      $teksti = stripslashes($teksti);
      $teksti = htmlentities($teksti);
      $teksti = str_replace("\r\n", '<br>', $teksti);
      $teksti = str_replace("\n", '<br>', $teksti);
      $teksti = str_replace('  ', '&nbsp;&nbsp;', $teksti);
      $teksti = str_replace('|', '&#124;', $teksti);
      return $teksti;
    }

  // Vieraskirja-tiedosto
  $tiedosto = 'vieraskirja.txt';
  if (!file_exists($tiedosto)) @touch($tiedosto, 0775);
  if (!file_exists($tiedosto))
  {
  echo 'Vieraskirjatiedosto on luotava ennen kuin vieraskirjaa voi käyttää ja sille annettava chmod 775 oikeudet!';
  exit;
  }

  //onko uutta viestiä?
  if (isset($_POST['viesti']))
    {
      if ($_POST['nimi'] == '')
        {
          $_POST['nimi'] = 'Anonyymi';
        }
// uuden viestin kirjoitus
      $fp = @fopen($tiedosto, 'r+');
      @flock($fp, 2);
      $uusi_viesti = Siivoa($_POST['nimi']) .  '|' . date('d.m.Y H:i') . '|' . Siivoa($_POST['viesti']) . "\n";
      $vanhat_viestit = @fread($fp, filesize($tiedosto));
      @rewind($fp);
      if (trim($vanhat_viestit == '')) $sisalto = trim($uusi_viesti); else $sisalto = $uusi_viesti . $vanhat_viestit;
      @fwrite($fp, $sisalto);
      @flock($fp, 3);
      @fclose($fp) or die('Ei kirjoitusoikeuksia, anna chmod 775 oikeudet vieraskirjatiedostoon.');
    }
  else //ei uutta viestiä
    {
      //haetaan vieraskirjan sisältö tiedostosta
      $fp = fopen($tiedosto, 'r');
      flock($fp, 1);
      $sisalto = fread($fp, filesize($tiedosto));
      flock($fp, 3);
      fclose($fp);
    }

//Linkit viesteihin
$sisalto = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $sisalto);

?>


    <style type="text/css">
      body
      {
        background-color: #020122;
      }
      table.viesti
      {
        margin: 5px;
        border-width: 2px;
        border-color: black;
        border-style: solid;
        padding: 0;
        background-color: #7FD6FA;
        text-align: justify;
      }
      td.otsikko
      {
        border-width: 0px 0px 2px 0px;
        border-color: black;
        border-style: solid;
        font-size: 0.9em;
        font-family: Verdana;
        color: black;
        background-color: #B5D7E5;
      }
      td.teksti
      {
        text-align: left;
        vertical-align: top;
        margin: 0.3em;
        font-size: 1em;
        color: black;
        text-align: justify;
		font-weight:bold;
      }
    </style>
  </head>
  <body>

  
<div class="foorumi" align="center">
<!--<div class="foorumi" align="center"> -->

<div align="left">
<h1>FOORUMI</h1>
<p> - Ja sieltä lähtee!</p>
</div>    

<?php

  //jaetaan sisältö riveittäin muuttujaan viestit
  $viestit = explode("\n", $sisalto);

  //määritetään katsottavat viestit
  if (!isset($_GET['persivu'])) $_GET['persivu'] = 10;
  if (!isset($_GET['sivu'])) $_GET['sivu'] = 1;
  $loppu = $_GET['persivu'] * $_GET['sivu'];
  $alku = $loppu - $_GET['persivu'] + 1;
  $viestit_maara = count($viestit);
  $sivut_maara = floor(($viestit_maara - 1) / $_GET['persivu']) + 1;
  if ($loppu > count($viestit)) $loppu = count($viestit);
  if (trim($sisalto == '')) $loppu = 0;

  //tulostetaan katsottavat viestit väliltä $alku...$loppu
  for ($viesti = $alku - 1; $viesti <= $loppu - 1; $viesti++)
  {
    $viestit[$viesti] = explode('|', $viestit[$viesti]);
    echo '
    <table cellpadding="2" cellspacing="0" class="viesti" width="600">
      <tr>
        <td class="otsikko"><b>' . $viestit[$viesti][0] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $viestit[$viesti][1] . '</b>
        </td>
      </tr>
      <tr>
        <td class="teksti">
          ' . $viestit[$viesti][2] . '
        </td>
      </tr>
    </table>';
    echo "\n";
  }

  echo "\n";
  echo '    <b>Sivut:</b><br>';

  //edelliseen sivuun linkki, jos olemassa
  if ($_GET['sivu'] > 1) echo '<a href="' . $_SERVER['PHP_SELF'] . '?sivu=' . ($_GET['sivu'] - 1) . '">&lt;&lt;</a> ';

  //kaikkiin sivuihin linkit, paitsi nykyinen tavallisena tekstinä
    for ($i = 1; $i <= $sivut_maara; $i++)
      if ($i != $_GET['sivu'])
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?sivu=' . $i . '">' . $i . '</a> ';
      else
        echo $i . ' ';

  //seuraavaan sivuun linkki, jos olemassa
  if ($_GET['sivu'] < $sivut_maara) echo '<a href="' . $_SERVER['PHP_SELF'] . '?sivu=' . ($_GET['sivu'] + 1) . '">&gt;&gt;</a>';

?>




<br><br>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <table>
          <tr>
            <td width="59" align="right"><b>Nimi:</b></td>
            <td width="329" align="left"><input name="nimi" size="53.5"></td>
          </tr>
          <tr>
            <td align="right" valign="top"><b>Asiaa:</b></td>
            <td align="left"><textarea name="viesti" rows="5" cols="46" wordwrap></textarea></td>
          <tr>
            <td align="center">&nbsp;</td>
            <td align="left"><!-- <img src="/anssi78/images/enter.gif" alt="Enter" > -->
			<br><input type="image" src="/anssi78/images/enter.gif" alt="Enter" style="border:1px solid #000000;">
			
			
			
			 <!-- <input type="submit" value="J&auml;t&auml; Viesti"> --></td>
          </tr>
        </table>
      </form>
   

<!-- </div> -->
</div>
<div class="etusivucode">
	<img src="images/etusivu_code.gif" width="125" border="0"></div>

<?php
include("pkstat_count.php"); 
 ?>