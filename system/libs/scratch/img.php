<?php
	if(!isset($_GET['val']) || !is_numeric($_GET['val']))
	{
		echo 'Access denied!';
	}
	else
	{
		$image = imagecreatefromjpeg('blank.jpg');
		$font_color = ImageColorAllocate($image,135,135,135);

		$box = ImageTTFBBox(11,0,dirname(__FILE__).'/arial.ttf',$_GET['val']);
		$textwidth = abs($box[4] - $box[0]);
		$x_finalpos = 50-($textwidth/2);
		
		imagettftext($image, 11, 0, $x_finalpos, 27, $font_color, dirname(__FILE__).'/arial.ttf', $_GET['val']);
		
		header('Content-Type: image/jpeg');
		
		ImageJPEG($image);
		ImageDestroy($image);
	}
?>