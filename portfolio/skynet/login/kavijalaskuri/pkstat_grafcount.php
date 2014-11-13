<?php // pkstat_grafcount.php


include_once("pkstat_inc.php");

////////////////////////////////////
//    K ִ V I J ִ L A S K U R I
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
//
// Lisהה kymmenen nollaa luvun eteen:
// pkstat_grafcount.php?pad=10
//
////////////////////////////////////

header("Content-type: image/png");





$pad = $_GET['pad'];
$type = $_GET['type'];

// Oletuskuva laskurille:
$data="iVBORw0KGgoAAAANSUhEUgAAAJYAAAAUCAAAAABKEpvtAAAALnRFWHRDcmVhdGlvbiBUaW1lAG1hIDI5IG1hcnJh".
"cyAyMDA0IDE2OjQyOjU1ICswMjAw46VrlwAAAAd0SU1FB9QLHQ4rJacyTu4AAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAEZ0".
"FNQQAAsY8L/GEFAAADIklEQVR42u1WXZUeIQylDnBQHBQHRcJIGAnUQRyMBCSwDugqmDpgHVAHaUIIMHt2z2nfy8N+A/nh".
"JrkJa8z/9ffri34E83M/97/2XTBvb+tb1jp5apPcWwNzf3z3b68vv3eF8PWH7N35zb6+7Fd5u8HwGRHbZedBwWN+OxZi9b".
"JLOFZd5icW+bC3Sr2CqqLsHrYosV2yOc1DecI4EW+4Kt4qT2rIqBpWgILVDsAJAMhhXEGhwgrkqBSSn8tz9sYV0SY9Xm2o".
"n9hOSzZ13VrgUqk5BDCFCisjExbh4J88grq7JMuh4l6wyoMiBLjrebACi39sQzdccUmcwop4c45tFRikFge8MpzXe8I6h9".
"U1MJfmGPc9C07RpCes4FRWN/Rsm/kv6CGibEcy2qh8EW/nzKLosd8yYfkWt9BW0ZOyJ+E1k0Qk6/xooCERehfgWKzlLHgF".
"7VwsRAcrmRUOUbaKoIN3sPgsmH3ZhE1d+9KJ2sQ5YF61AxYk5gdo4V3neTunK5iFvrAJ59vRM91hBeqaJskbF9rVXe9gBb".
"KfyYrUAiFkUT64nDus23W+4OCSb9RNAbBpYSlZ6po4XYnzDgRHxRxOCiJ1YyuHXS1/DIv67vYbyEUF3/g+ChRSNBvnCgsd".
"FuL8JR60cVeyiAqw3+b7eKiHpG11T1pNv8MiTrc1DcyuQjzIqchoYl8u2s2e/I9qLlgrWfR1zq8RL0TPcNMOyy/6PGBlpZ".
"GUUItZsQdMq9x4B7exerDUImrTVaV5nMMxSh4NdxNreR2jtcPVcbbG1hNWfKCabZu44jaewW6RxexGpbLcN2ZwmmDqnOn6".
"kNgi6cjjehg3SFrDNuR3WJRhMW9dfIgW99cCO2HVXit7DYYfokTjRiNbw4igMgwb2xCOF+zSSU7tcwG1ZbEfwTrmO4YDJd".
"G74COFQSM6+fnIbQrpjgx5PaimLPryC8C6mK3WsvALONMJPD7Kmpac0XlrnKikTX1/jSvsVApNhzlPnV0YuR3uc+4rLrvA".
"sjrHsrH9X4JBg75cMJ8u//7ABve5Nrl6Sn3YA7D2qWufxsGb/+vf1x+DTalcu3917QAAAABJRU5ErkJggg==";


function get_fastdata($type){
	global $_PKSTAT;
	if($type == "uniq"){
		return intval(filesize($_PKSTAT['hak']."/fastdata_uniq")/9);
	}else{
		return filesize($_PKSTAT['hak']."/fastdata_load");
	}
}

if(!$type){
	$num = (string)(get_fastdata("uniq")+get_fileval("yht_uniq", $null));
}else{
	$num = (string)(get_fastdata("load")+get_fileval("yht_load", $null));
}

if(file_exists("pkstat_laskuri.png")){
	$kuva = imagecreatefrompng("pkstat_laskuri.png");
}else{
	$kuva = imagecreatefromstring(base64_decode($data));
}

$lev = imagesx($kuva);
$kor = imagesy($kuva);

$ask = $lev/10;
$num = sprintf("%0{$pad}s", $num);
$len = strlen($num);

$im = imagecreate($len*$ask, $kor);


for($u = 0; $u < $len; $u++){
	$sx = (int)$num[$u]*$ask;
	imagecopy($im, $kuva, $u*$ask, 0, $sx, 0, $ask, $kor);
}


imagepng($im);
imagedestroy($im);

?>