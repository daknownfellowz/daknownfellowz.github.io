<?php
 if ($viesti != '') {
  $uusirivi = '<p><b>' . date('d.m.Y H:i') . ', ' .
	          strip_tags($nimi) . '</b>: ' . strip_tags($viesti) .
              '</p>';
  $tiedosto = fopen('vieraskirja2.txt', 'a');
  fputs($tiedosto, $uusirivi);
  fclose($tiedosto);
 }
?>

<html>
<body>
  <h2>Vieraskirjan sisältö:</h2>
  <?php include ('vieraskirja2.txt'); 
  include("pkstat_count.php"); ?>
  <hr>
  <form action="vieraskirja2.php" method="post">
     Nimesi: <input name="nimi"><br>
     Viestisi: <input name="viesti" size="50"><br>
     <input type="submit" value=" Jätä viesti ">
  </form>
</body>
</html>