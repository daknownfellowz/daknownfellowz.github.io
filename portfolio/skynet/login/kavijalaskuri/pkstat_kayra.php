<?php // pkstat_kayra.php


include_once("pkstat_inc.php");

////////////////////////////////////
//    K Ä V I J Ä L A S K U R I
//
//  - - FFS Multicounter (v.2) - -
//
////////////////////////////////////
//
// By: T.M. (hctm at suomi24 piste fi)
// www.HC-Codes.net
// http://koti.mbnet.fi/winuus/
//
////////////////////////////////////


header("Content-type: image/png");




$type = $_GET['type'] ? 2 : 1;
$data = glob($_PKSTAT['m'].$_PKSTAT['hak']."/tilastot/*");


if(count($data) < 2){
	$im = imagecreate(120, 40);
	$val = imagecolorallocate($im, 255, 255, 255);
	$mus = imagecolorallocate($im, 0, 0, 0);
	imagerectangle($im, 1, 1, 119, 39, 1);
	imagestring($im, 2, 8, 7, "Ei vielä tarpeeksi", 1);
	imagestring($im, 2, 33, 20, "tilastoja", 1);
	imagepng($im);
	imagedestroy($im);
	die();
}


$ulos = array();
foreach($data as $tiedosto){
	$osa = explode("-", basename($tiedosto));
	$ulos[mktime(0, 0, 0, substr($osa[0], 2, 2), substr($osa[0], 4, 2), substr($osa[0], 0, 2))] = $osa[$type];
}

$data = $ulos;

// array_slice() korvaaja joka säilyttää taulukon alkion nimen
function array_getonly($array, $len){
	$p = count($array);
	foreach($array as $key => $value){
		if($p > $len) unset($array[$key]);
		$p--;
	}
	return $array;
}

$pituus = 60;
$data = array_getonly($data, $pituus);


$min = min($data);
$max = max($data);

$len = count($data);

if(!$lev) $lev = 600;
if(!$kor) $kor = 200;

$askeleet = floor($kor/20);
$ak = floor($kor/$askeleet);
$kor = $ak*$askeleet;

$ask = floor($lev/$len);
$lev = $ask*($len-1)+1;

$sx = 40;
$sy = 20;
$sxp = 20;
$syp = 20;


$maxkor = $max-$min;

$im = imagecreate($sx+$lev+$sxp, $sy+$kor+$syp+1);
$val = imagecolorallocate($im, 255, 255, 255);
$mus = imagecolorallocate($im, 0, 0, 0);

$h1 = imagecolorallocate($im, 238, 238, 238);
$h2 = imagecolorallocate($im, 226, 226, 226);

$har = imagecolorallocate($im, 214, 214, 238);


$pad = strlen("$max");

$apk = ($max-$min)/$askeleet;

$mp = $max;
$myp = $sy;


for($u = 0; $u <= $askeleet; $u++){
	imagestring($im, 2, $sx-$pad*6-4, $myp-7, sprintf("% {$pad}s", round($mp)), $mus);
	imageline($im, $sx-2, $myp, $sx+2, $myp, $mus);
	imageline($im, $sx+3, $myp, $sx+$lev-1, $myp, $har);
	$mp -= $apk;
	$myp += $ak;
}


imageline($im, $sx, $sy, $sx, $sy+$kor, $mus);


$rkor = $kor-1;

$u = 0;
foreach($data as $value){
	if($u > $len-2) break;
	$x = $sx+$u*$ask;
	$y1 = $max-$value;
	$y2 = $max-next($data);
	imageline($im, $x, ($y1/$maxkor)*$rkor+$sy, $x+$ask, ($y2/$maxkor)*$rkor+$sy, $mus);
	imageline($im, $x, $rkor+$sy+1, $x, ($y1/$maxkor)*$rkor+$sy, $mus);
	$u++;
}

imageline($im, $x+$ask, $rkor+$sy+1, $x+$ask, ($y2/$maxkor)*$rkor+$sy, $mus);


imageline($im, $sx, $sy+$kor+1, $sx+$lev-$spx-1, $sy+$kor-$spy+1, $mus);

$u = 0;
foreach($data as $value){
	if($u > $len-2) break;
	$x = $sx+$u*$ask;
	if($u % 2 == 0){
		$vari = $h1;
	}else{
		$vari = $h2;
	}
	imagefilltoborder($im, $x+$ask-1, $sy+$kor, $mus, $vari);
	$u++;
}


imageline($im, $sx, $sy+$kor, $sx+$lev-$spx-1, $sy+$kor-$spy, $mus);
imageline($im, $sx, $sy+$kor+1, $sx+$lev-$spx-1, $sy+$kor-$spy+1, $val);

$day = "SMTKTPL";

$u = 0;
foreach($data as $key => $value){
	$x = $sx+$u*$ask;
	imagestring($im, 1, $x, $kor+22, $day[date("w", $key)], $mus);
	$u++;
}

imagepng($im);
imagedestroy($im);


?>