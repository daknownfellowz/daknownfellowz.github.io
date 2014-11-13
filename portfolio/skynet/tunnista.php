<html>
<body>
<?php
  if ($kayttaja == 'sulo' && $salasana == 'salmiakki') {
     print 'Tervetuloa kotiin homo!';
  }  else  {
     print 'Käyttäjää ' . $kayttaja . ' ei tunnistettu tai salasana on väärä.';
  }
?>
</body>
</html>
