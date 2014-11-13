<html> 
<head> 
<title>Kävijälaskurin malli</title> 
<style type="text/css"> 

body {
	background-color: #020122;
}


a { color: black; text-decoration: none; } 
a:link { color: black; text-decoration: none; } 
a:visited { color: black; text-decoration: none; } 
a:active { color: black; text-decoration: none; } 
a:hover { color: red; text-decoration: none; } 
.font { font-family: Verdana; font-size: 9px; font-color: #020122 } 
</style> 
</head> 

<body> 
<font class="font"> 


<?PHP 
    #LASKURI by:DrKafka 
    $file="kavijalaskuri.txt"; 
    IF(!IS_WRITABLE($file)){DIE("Tiedostolla ".$file." ei ole kirjoitus oikeuksia!");} 
    $ip=EXPLODE(".",GETENV("REMOTE_ADDR")); 
    $ip=$ip[0].'.'.$ip[1].'.'.$ip[2]; 
    $f=FILE($file); 
    $s=true; 
    FOR($i=0;$i<COUNT($f);$i++)IF($ip==TRIM($f[$i])){$s=false;BREAK;} 
    IF($s==true){$fp=FOPEN($file, "a");FLOCK($fp,2);FWRITE($fp,$ip."\r\n");FCLOSE($fp);} 
    $c=COUNT(FILE($file)); 
    echo '<B>'.$c.'</B> uniikkia'; 
?>



</font> 
</body> 
</html>