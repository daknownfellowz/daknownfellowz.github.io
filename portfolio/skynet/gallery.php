<?php
##############################
# YAEG(Yet Another Easy Gallery) v.1.5 by Markku Virtanen (Cahva^RNO)
# Thumbnail function is taken from php.net by unknown person
# Email: cahva@po-rno.fi)
# www: http://www.po-rno.fi or http://rno.wmhost.com
#
# Created: 2003-11-19
# Updated: 2004-04-22
# This script is meant to be as easy as possible. Works with GIF,JPG and PNG pictures.
# Gallery viewers can also comment the pictures too with this! Aint that neat! :)
#
# Usage
# ------
# Only things you have to do is:
# 1.Create directory
# 2.Set the permission to write to directory
# 3.Copy pictures to directory
#
# Thats it. Gallery is now ready for use. Open a browser. Go to the directory you created.
# For the first time it makes the thumbnails so it takes a bit longer than usual.
# You can later copy pictures as you want. It allways makes thumbnails for new pictures.
#
# Changes:
# v1.5 - added possibility to use other directory than the one where this script is(see $dirsetting)
#
##############################

# User config
$tsize=75; // Set thumbnail max size with this(pixels)
$cols=5; // Set how many thumbnails you want to show per row
$rows=5; // How many rows you want to show per page
$dateformat=1; // Dateformat for comments.. (1 = 19.13.2003 17:32:03) & (2 = 2003-13-19 17:32:03)
$pic_bg='#020122'; // Backgroundcolor for thumbnails
$text_bg='#020122'; // Backgroundcolor for filenames and resolutions
$dirsetting=0; // Set this to 1 if you want different picture directory than where the script is.(1=Manual setting, any other=automatic)
#

# Directory config
$domain=$_SERVER['SERVER_NAME'];
if ($dirsetting==1) {
    // Manual setting - change directories to match on your webserver
    $dirtree=$_SERVER['DOCUMENT_ROOT'].'/gallery/pictures/'; // Sets absolute server location where the pictures are.
    $dirtree_url='http://'.$domain.'/gallery/pictures/';  // Sets complete URL-location where the pictures are.
} else {
    // Automatic setting - Do not change
    $dirtree=str_replace(basename ($_SERVER['PATH_TRANSLATED']),'',$_SERVER['PATH_TRANSLATED']);
    $dirtree_url='';
}
#

// This sets the directory where this script resides
$url='http://'.$domain.$_SERVER['PHP_SELF'];

// Headers must revalidate. Else the comments doesnt update properly
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// Read directory contents and make the thumbs if necessary
$thumbs=array();
$filez=array();
if ($dir = @opendir($dirtree)) {
    while (($file = readdir($dir)) !== false) {
        if (is_file($dirtree.$file)) {
            $ext=explode('.',$file);
            $ext[1]=strtolower($ext[1]);

            if ($ext[1]=='jpg' || $ext[1]=='gif' || $ext[1]=='png') {
                if (!strstr($dirtree.$file,'THUMB'))
                    $filez[]=$file;
                else
                    $thumbs[]=$file;
            }
        }
    }
    closedir($dir);
}

// Sort arrays
natcasesort($filez);
reset($filez);
natcasesort($thumbs);
reset($thumbs);

// Thumb-o-matic
while ($pics=each($filez)) {
    $ext=explode('.',$pics[1]);

    // If no thumbnail was found, create it.
    if (array_search('THUMB'.$ext[0].'.jpg',$thumbs)===false) {
        image_createThumb($dirtree.$pics[1],$dirtree.'THUMB'.$ext[0].'.jpg',$tsize,$tsize,$quality=100);
        $uusiakuvia++;
    }
    // If thumbnail is older than the normal size picture, create it again and delete comment
    elseif(filectime($dirtree.'THUMB'.$ext[0].'.jpg')<filectime($dirtree.$pics[1])) {
        image_createThumb($dirtree.$pics[1],$dirtree.'THUMB'.$ext[0].'.jpg',$tsize,$tsize,$quality=100);
        if (file_exists($dirtree.$ext[0].'.txt'))
            unlink($dirtree.$ext[0].'.txt');
        $uusiakuvia++;
    }
}


// If theres no pictures, die
if (count($filez)==0) {
    die('No pictures found!');
}

// Splice array if there are more pages

$pics_per_page=$cols*$rows; // Calculate how many pics fit to page
if ($pics_per_page<count($filez)) {
    $pages=ceil(count($filez)/$pics_per_page);
    if ($page>1) {
        $startpic=($page-1)*$pics_per_page;
        $filez=array_splice($filez, $startpic, $pics_per_page);
    }
}

// Get current directory name
$h=explode('/',$dirtree);
$jei=count($h)-2;
$curdir=$h[$jei];

// Add comments
if ($_POST['Submit']) {
    if ($_POST['nick']=='')
        $nick='Anonymous';
    else
        $nick=$_POST['nick'];

    if ($_POST['comment']=='')
        die('Missing comment!</br><a href="'.$url.'">Main</a>');

    $ext=explode('.',$_POST['pic2']);

    $fp = fopen($dirtree.$ext[0].'.txt', "a+");
    if ($dateformat==1)
        $pvm = date('d.n.Y H:i:s');
    if ($dateformat==2)
        $pvm = date('Y-n-d H:i:s');

    $comment = stripslashes(htmlspecialchars($_POST['comment'], ENT_QUOTES));
    $nick = stripslashes(htmlspecialchars($nick, ENT_QUOTES));
    fwrite($fp, "$pvm|Nick: $nick|Comment: $comment\n");
    fclose($fp);
    header('Location: '.$url.'?pic='.urlencode($_GET['pic']));
}


if ($_GET['pic']) {
    // If a certain picture doesnt exist, redirect to overview
    if (!file_exists($dirtree.$_GET['pic']))
        header('Location: '.$url);

    $curpic=' Picture:'.$_GET['pic'];

    // Next-Prev Buttons
    reset($filez);

    while($joo=each($filez)) {
        $i++;
        if ($joo[1]==$_GET['pic']) {
            if ($i==count($filez)) {
                $p=end($filez);
                $p=prev($filez);
                $previous='<a href="'.$url.'?pic='.$p.'">&lt;&lt; <<Previous</a>';
            }
            elseif($i==1) {
                $p=reset($filez);
                $p=next($filez);
                $next='<a href="'.$url.'?pic='.$p.'">Next>> &gt;&gt;</a>';
            }
            elseif($i>1) {
                $p=current($filez);
                $next='<a href="'.$url.'?pic='.$p.'">Next>> &gt;&gt;</a>';
                $p=prev($filez);
                $p=prev($filez);
                $previous='<a href="'.$url.'?pic='.$p.'">&lt;&lt; <<Previous</a>';
            }
            break;
        }
    }
}

?>
<html>
<head>
<title>Directory:<?php echo $curdir.$curpic; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
table {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
}
-->
</style>
<style type="text/css">
<!--
a {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
}
-->
</style>
</head>

<body>
<?php
// Create form for comments
if ($_GET['com']==1 && $_GET['pic']) {
    ?>
    <form name="form1" method="post" action="<?php echo $url?>?pic=<?php echo urlencode($_GET['pic'])?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="80" valign="top">Nick:</td>
      <td><input name="Kommentoija" type="text" id="Kommentoija"><input name="pic2" type="hidden" id="pic2" value="<?php echo $_GET['pic']?>"></td>
    </tr>
    <tr>
      <td valign="top">Comment:</td>
      <td><input name="comment" type="text" id="comment" size="50">
        <br>
        <input type="submit" name="Submit" value="Lisää kommentti"> </td>
    </tr>
  </table>
  </form>

  <?php
  die("</body>\n</html>");
  }
if ($_GET['pic']) {
    $size = getimagesize($dirtree.$_GET['pic']);
    echo '<center>'.$_GET['pic'].' ('.$size[0].' x '.$size[1].')<br><br>';
    echo '<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=1>';
    echo '<tr><td>'.$previous.'</td><td><a href="'.$url.'">Overview</a></td><td>'.$next.'</td></tr>';
    echo '<tr align="center">';
    echo '<td colspan="3"><a href="'.$url.'?pic='.urlencode($_GET['pic']).'&com=1">[LISÄÄ KOMMENTTI!]</a></td></tr></table><br>';
    echo '<img src="'.$dirtree_url.$_GET['pic'].'"></center><hr>';
    $ext=explode('.',$_GET['pic']);
    if (file_exists($dirtree.$ext[0].'.txt')) {
        $text=file($dirtree.$ext[0].'.txt');
        for ($i=0; $i<count($text);$i++) {
            $pala=explode('|',$text[$i]);
            echo $pala[0].'<br>';
            echo $pala[1].'<br>';
            echo $pala[2].'<hr>';
        }
    }
}
else {

// Inform that thumbnail(s) was/were created
if ($uusiakuvia)
    echo $uusiakuvia.' thumbnail(s) created!';

// page-o-matic
if ($pages>1) {
    echo '<center><h4>';
    for($i=1;$i<$pages+1; $i++)
        echo '<a href="'.$url.'?page='.$i.'">'.$i.'</a> ';
    echo '</h4></center>';
}
?>
<table cellspacing="1" cellpadding="5" align="center"><tr align="center" bgcolor="<?=$pic_bg?>">
<?php
$tdcount=0;
$count=0;
natcasesort($filez);
reset($filez);
foreach ($filez AS $key=>$value) {
    // cut filename lenght if its over 18 characters(to prevent destroying the tablestructure :)
    if (strlen($value)>18)
        $fname=substr($value,0,18).'..';
    else
        $fname=$value;

    $size = getimagesize($dirtree.$value);
    $ext=explode('.',$value);

    // If conmmentfile was found, colorize filename
    if (file_exists($dirtree.$ext[0].'.txt'))
        $fileinf[]='<font color="#770000">'.$fname.'<br>('.$size[0].' x '.$size[1].')</font>';
    else
        $fileinf[]=$fname.'<br>('.$size[0].' x '.$size[1].')';

    if ($tdcount==$cols) {
        echo '</tr><tr bgcolor="'.$text_bg.'">'."\n";
        for ($i=$count-$cols; $i<$count;$i++)
            echo '<td>'.$fileinf[$i].'</td>'."\n";

        echo '</tr><tr><td colspan="'.$cols.'"></td></tr><tr align="center" bgcolor="'.$pic_bg.'">';
        $tdcount=0;
        if ($count==$pics_per_page)
            break;
    }
    echo '<td><a href="'.$url.'?pic='.urlencode($value).'"><img border="0" src="'.$dirtree_url.'THUMB'.$ext[0].'.jpg"></a></td>'."\n";
    $tdcount++;
    $count++;
}
if ($tdcount<$cols) {
    echo '</tr><tr bgcolor="'.$text_bg.'">';
    for ($i=$count-($tdcount); $i<$count;$i++)
        echo '<td>'.$fileinf[$i].'</td>'."\n";
    echo '</tr>';
}
if ($tdcount==$cols) {
    echo '</tr><tr bgcolor="'.$text_bg.'">'."\n";
    for ($i=$count-$cols; $i<$count;$i++)
        echo '<td>'.$fileinf[$i].'</td>'."\n";

    echo '</tr><tr><td colspan="'.$cols.'"></td></tr><tr align="center" bgcolor="'.$pic_bg.'">';
    $tdcount=0;
}
?>
</table>
<?php } ?>
</body>
</html>
<?php
function image_createThumb($src,$dest,$maxWidth,$maxHeight,$quality=100) {
    if (file_exists($src)  && isset($dest)) {
        // path info
        $destInfo  = pathInfo($dest);

        // image src size
        $srcSize   = getImageSize($src);

        // image dest size $destSize[0] = width, $destSize[1] = height
        $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio
        $destRatio = $maxWidth/$maxHeight;
        if ($destRatio > $srcRatio) {
            $destSize[1] = $maxHeight;
            $destSize[0] = $maxHeight*$srcRatio;
        }
        else {
            $destSize[0] = $maxWidth;
            $destSize[1] = $maxWidth/$srcRatio;
        }

        // path rectification
        if ($destInfo['extension'] == "gif") {
            $dest = substr_replace($dest, 'jpg', -3);
        }

        // true color image, with anti-aliasing
        $destImage = imageCreateTrueColor($destSize[0],$destSize[1]);
        imageAntiAlias($destImage,true);

        // src image
        switch ($srcSize[2]) {
            case 1: //GIF
            $srcImage = imageCreateFromGif($src);
            break;

            case 2: //JPEG
            $srcImage = imageCreateFromJpeg($src);
            break;

            case 3: //PNG
            $srcImage = imageCreateFromPng($src);
            break;

            default:
            return false;
            break;
        }

        // resampling
        imageCopyResampled($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]);

        // generating image
        switch ($srcSize[2]) {
            case 1:
            case 2:
            imageJpeg($destImage,$dest,$quality);
            break;

            case 3:
            imagePng($destImage,$dest);
            break;
        }
        return true;
    }
    else {
        return false;
    }
}
include("pkstat_count.php"); 
?>
