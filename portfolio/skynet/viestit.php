<?php include("yla.htm"); ?>


<?php
 if ($viesti != '') {
  $uusirivi = '<p><b>' . date('d.m.Y H:i') . ', ' .
	          strip_tags($nimi) . '</b>: ' . strip_tags($viesti) .
              '</p>';
  $tiedosto = fopen('vieraskirja.txt', 'a');
  fputs($tiedosto, $uusirivi);
  fclose($tiedosto);
 }
?>


<p>&nbsp;</p>
  <H3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Anna palautetta! </H3>
  <h2>Vieraskirjan sisältö:</h2>
  <p><hr></p>
  <?php include ('vieraskirja.txt'); ?>
  <hr>
  <form action="vieraskirja.php" method="post">
     Nimesi: <input name="nimi"><br>
     Viestisi: <input name="viesti" size="50"><br>
     <input type="submit" value=" Jätä viesti ">
  </form>

<?php include("ala.htm"); ?>