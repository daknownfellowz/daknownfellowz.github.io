<html>
<body>
<?php
  if ($kayttaja == 'sulo' && $salasana == 'salmiakki') {
     print 'Tervetuloa kotiin homo!';
  }  else  {
     print 'K�ytt�j�� ' . $kayttaja . ' ei tunnistettu tai salasana on v��r�.';
  }
?>
</body>
</html>
