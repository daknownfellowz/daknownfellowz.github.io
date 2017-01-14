<?php
 //print_r($_POST);


 if ($viesti != '') {
  $uusirivi = '<p><b>' . date('d.m.Y H:i') . ', ' .
	          strip_tags($nimi) . '</b>: ' . 
			  strip_tags($tapahtuma) . ' : ' .  strip_tags($viesti) .
              '</p>';
  $tiedosto = fopen('vieraskirja2.txt', 'a');
  fputs($tiedosto, $uusirivi);
  fclose($tiedosto);
 }
?>

 <pre>
  <?php include ('vieraskirja2.txt');?>
 </pre>
