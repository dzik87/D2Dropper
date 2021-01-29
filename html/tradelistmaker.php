<?php
	ini_set("display_errors", "on");
	$littleboy = $_SERVER['PHP_AUTH_USER'];
	if (!empty($_POST['tradeinfo'])) {

		$background; $lastImageHeight; $lastImageWidth; $lastImageX; $lastImageY; $rowY; $rowX; $maxRowHeight; $totalLength;
		$images 	= array();
		$freeSpots 	= array();
		$items 		= json_decode($_POST['tradeinfo']);
		$maxWidth 	= $_POST['maxwidth'];
			
		if (empty($items)) {
			$items = json_decode(stripslashes($_POST['tradeinfo']));  //fix in case of escape slashes
		} 
		
		class Image {
				public $name;
				public $width;
				public $height;
				public $type;
				public $char;
				public $acc;
		}

		class FreeSpot {
				public $x;
				public $y;
				public $width;
				public $height;
		}
		
		foreach ($items as $entry) { 
			$temp = json_decode($entry, true);
			
			$imgname = $temp['skin']."-".$temp['dropit'];
			$imgtype = $temp['itemType'];
			$imgacc = $temp['account'];
			$imgchar = $temp['charName'];
			$imgpath = "trade/$imgname.png";
			
			if(file_exists($imgpath)) {
				list($imgwidth, $imgheight) = getimagesize($imgpath);
				
				if ($imgwidth < $maxWidth) {
					$img = new Image();
					$img->width = $imgwidth;
					$img->height = $imgheight;
					$img->name = $imgpath;
					$img->type = $imgtype;
					$img->char = $imgchar;
					$img->acc = $imgacc;	
					$images[] = $img;
				}
			}
		}
	
		$number_of_images = count($images);
		
		if (!empty($number_of_images)) {
			if (isset($_POST['sorttype'])) {
				usort($images, function($a, $b) {
					return $b->type - $a->type;
				});
			} else {
				usort($images, function($a, $b) {
					return $b->height - $a->height;
				});
			}
			imageActions(false);
			imageActions(true);
			imagepng($background, 'users/_output_'.$littleboy.'.png');
			imagedestroy($background);
			
			imgurIt('users/_output_'.$littleboy.'.png');
		} else {
			print "You must have used too small of an image width!";
		}
	}
	
	function imageActions($mergeImg) {
		global $maxWidth, $background, $images, $freeSpots, $lastImageHeight, $totalLength,
		$lastImageWidth, $lastImageX, $lastImageY, $rowY, $rowX, $maxRowHeight;

		$totalLength 		= 0;
		$lastImageHeight	= 0;	
		$lastImageWidth 	= 0; 
		$lastImageX 		= 0;
		$lastImageY 		= 0;	
		$rowX 				= 0;
		$rowY 				= 0;
		$maxRowHeight 		= 0;		
		$tempImages 		= $images;
		$totalImages 		= count($tempImages);
		
		for ($x = 1; $x < $totalImages + 1; $x++) {
			
			for ($i = 0; $i < count($tempImages); $i++) {
				
				// Set all temp variables false before restarting loop
				$usableX		= null;
				$usableY		= null;
				$currImgWidth	= null;
				$currImgHeight	= null;
				$currImgPath	= null;
				
				// Define temp values to make it easier
				$currImgWidth = $tempImages[$i]->width;
				$currImgHeight = $tempImages[$i]->height;
				$currImgPath = $tempImages[$i]->name;
				$currImgAcc = $tempImages[$i]->acc;
				$currImgChar = $tempImages[$i]->char;
				
				if (!isset($currImgWidth) or !isset($currImgHeight) or !isset($currImgPath)) {
					$x++;
					break;
				}
				
				if (count($freeSpots) > 0) {
					for ($z = 0; $z < count($freeSpots); $z++) {
						$fsw = $freeSpots[$z]->width;
						$fsh = $freeSpots[$z]->height;
						
						if (($fsw - 5) >= $currImgWidth and ($fsh - 5) >= $currImgHeight) {
							$usableX = ($freeSpots[$z]->x);
							$usableY = ($freeSpots[$z]->y + 5);
							array_splice($freeSpots, $z, 1);
							break;
						}
					}
					if (isset($usableX) and isset($usableY)) {
						break;
					}
				}
				
				if (($rowX + 5) + $currImgWidth < $maxWidth) {
					$usableX = ($rowX + 5);
					$usableY = $rowY;					
					break;
				}
			}
			
			// Update Variables for later use
			$lastImageHeight = $currImgHeight;
			$lastImageWidth = $currImgWidth;
			$lastImageX = $usableX;
			$lastImageY = $usableY;	
			
			// Go to next row; We ran out of space!!!
			if (!isset($usableX) or !isset($usableY)) {	
				getFreeSpots($mergeImg, true);
		
				// Set values for next row
				$rowX = 0;
				$rowY = ($totalLength + 5);
				$maxRowHeight = 0;

				$x--;
				continue;
			}
			
			// If we have made it here.. hopefully we have everything we need to insert a new image...
			if ($mergeImg) {
				$image_object = imagecreatefrompng($currImgPath);

				$white = imagecolorallocate($image_object, 255, 255, 255);
				
				imagettftext($image_object, 20, 0, 15, 25, $white, 'fonts/exocet-blizzard-light.ttf', "$x");
				
			    if (isset($_POST['charinfo'])) {
					$dimensions = imagettfbbox(7, 0, 'fonts/ARIAL.TTF', "$currImgAcc/$currImgChar");
					$textWidth = abs($dimensions[4] - $dimensions[0]);
					$textX = imagesx($image_object) - ($textWidth + 3);
					
					imagettftext($image_object, 7, 0, $textX, 10, $white, 'fonts/ARIAL.TTF', "$currImgAcc/$currImgChar");
				}
				imagecopy($background, $image_object, $usableX, $usableY, 0, 0, $currImgWidth, $currImgHeight);
			}

			// Set new highest image height on a given row
			if ($currImgHeight > $maxRowHeight) {
				$maxRowHeight = $currImgHeight;
				$totalLength = ($rowY + $maxRowHeight);			
			}
			
			// Set current Row Width
			if ((($usableX + $currImgWidth) > $rowX) and (($usableY >= $rowY) and ($usableY <= ($rowY + $maxRowHeight)))) {			
				$rowX = ($usableX + $currImgWidth);
			}
			
			getFreeSpots($mergeImg, false);
			array_splice($tempImages, $i, 1);
		}
		
		if (count($tempImages) > 0) {
			return false;
		}
		
		if (!$mergeImg) {
			$background = imagecreatetruecolor($maxWidth, $totalLength);
			$freeSpots = array();
			
			// Enable transparency on the background
			imagealphablending($background, false);
			imagesavealpha($background, true);
			
			// Fill with transparent color
			$trans_colour = imagecolorallocatealpha($background, 0, 0, 0, 127);
			imagefill($background, 0, 0, $trans_colour);
		}
		
		return true;
	}
	
	function getFreeSpots($merge, $endSpot) {
		global $lastImageHeight, $background, $lastImageWidth, $lastImageX, $lastImageY, $totalLength, 
		$maxWidth, $freeSpots, $rowY, $rowX, $maxRowHeight;

		
		// Started a new row, get empty space on the last row
		if ($endSpot) {
			$freeSpotX = $rowX;
			$freeSpotY = $rowY;
			$freeSpotWidth = $maxWidth - $rowX;
			$freeSpotHeight = $maxRowHeight;
		}
		
		// Gets free space just below the last image
		if (!$endSpot) {
			$freeSpotX = $lastImageX;
			$freeSpotY = $lastImageY + $lastImageHeight;
			$freeSpotWidth = $lastImageWidth;
			$freeSpotHeight = ($totalLength - ($lastImageY + $lastImageHeight));	
		}
			
		// If we found a new free spot, Add it to array
		if (isset($freeSpotX) and isset($freeSpotY)) {
			$newFreeSpot = new FreeSpot();
			$newFreeSpot->width = $freeSpotWidth;
			$newFreeSpot->height = $freeSpotHeight;
			$newFreeSpot->x = $freeSpotX;
			$newFreeSpot->y = $freeSpotY;
			$freeSpots[] = $newFreeSpot;	
			
			/*if ($merge) { // for testing
				$freeSpotX2 = $freeSpotX + $freeSpotWidth;
				$freeSpotY2 = $freeSpotY + $freeSpotHeight;
				
				$red = imagecolorallocate($background, 255, 0, 0);
				$black = imagecolorallocate($background, 0, 0, 0);
				//imagefilledrectangle($background, $freeSpotX, $freeSpotY, $freeSpotX2, $freeSpotY2, $red);
				imagerectangle($background, $freeSpotX, $freeSpotY, $freeSpotX2, $freeSpotY2, $red);
			}*/
		}
		return true;
	}
	
	function imgurIt($img) {
		$filename 	= $img;
		$client_id 	= "b91bee2ff90a92d";
		$file 		= file_get_contents($filename);
		$url 		= 'https://api.imgur.com/3/image.json';
		$pvars  	= array('image' => base64_encode($file));
		$headers 	= array('Authorization: CLIENT-ID b91bee2ff90a92d');

        $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $pvars);
		
        if (($data = curl_exec($ch)) === FALSE) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        $back = json_decode($data, true);
		$link = $back['data']['link'];
		
		print '<a href="'.$link.'" target="_blank">'.$link.'</a>';
	}
?>