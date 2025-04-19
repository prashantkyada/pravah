<?php
include("conf.php");

function getuniqkey($special='')
{
	return md5(date("Y-m-d H:i:s").uniqid(rand(), true).time().$special);
}
// Function to get the next order number
function getNextOrderNumber($lastOrderNumber) {
    // Get the current month and year in the desired format
    $currentMonth = strtoupper(substr(date('F'), 0, 3)); // e.g., JAN
    $currentYear = date('y'); // e.g., 24

    // Extract month and year from the last order number
    if ($lastOrderNumber) {
        $lastMonth = substr($lastOrderNumber, 0, 3);
        $lastYear = substr($lastOrderNumber, 3, 2);
        $lastCount = (int)substr($lastOrderNumber, 5); // Extract sequential part
    } else {
        $lastMonth = null;
        $lastYear = null;
        $lastCount = 0;
    }

    // Check if the last order belongs to the current month and year
    if ($lastMonth === $currentMonth && $lastYear === $currentYear) {
        // Increment the count
        $newCount = $lastCount + 1;
    } else {
        // Start a new sequence
        $newCount = 1;
    }

    // Format the new order number
    $formattedCount = str_pad($newCount, 3, '0', STR_PAD_LEFT);
    return $currentMonth . $currentYear . $formattedCount;
}


function softwaretotal($query)
{
	global $mysqli;
	$passangers_count = mysqli_query($mysqli,$query);
 	$passangers_cnt = mysqli_fetch_array($passangers_count);
	return $passangers_cnt['count']; 
	//return '100000';
}
function compress($source, $destination, $quality) 
{
	
    $info = getimagesize($source);
	
    if($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
	
}
function resize_image($file, $w, $h, $crop=false) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    
    //Get file extension
    $exploding = explode(".",$file);
    $ext = end($exploding);
    
    switch($ext){
        case "png":
            $src = imagecreatefrompng($file);
        break;
        case "jpeg":
        case "jpg":
            $src = imagecreatefromjpeg($file);
        break;
        case "gif":
            $src = imagecreatefromgif($file);
        break;
        default:
            $src = imagecreatefromjpeg($file);
        break;
    }
    
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}
function random($length, $chars = '')
{	
	if(!$chars) 
	{
		$chars = implode(range('a','f'));
		$chars = implode(range('0','9'));
	}
	$shuffled = str_shuffle($chars);
	return substr($shuffled, 0, $length);
	
}
function serialkey()
{	
	return random(4);
}
	
function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}
	
//generic php function to send GCM push notification
/*
========== Default values ==============
ini_set('memory_limit', '-1');
$save_to_file = true;
$image_quality = 100;
$image_type = -1;

*/
// generate thumb from image and save it
function GenerateThumbFile($from_name, $to_name, $max_x, $max_y)
{
	global $save_to_file, $image_type, $image_quality;
	// if src is URL then download file first
	$temp = false;
	if (substr($from_name,0,7) == 'http://')
	{
		$tmpfname = tempnam("tmp/", "TmP-");
		$temp = @fopen($tmpfname, "w");
		if ($temp)
		{
			@fwrite($temp, @file_get_contents($from_name)) or die("Cannot download image");
			@fclose($temp);
			$from_name = $tmpfname;
		}
		else
		{
			die("Cannot create temp file");
		}
	}
	// get source image size (width/height/type)
	// orig_img_type 1 = GIF, 2 = JPG, 3 = PNG
	list($orig_x, $orig_y, $orig_img_type, $img_sizes) = @GetImageSize($from_name);
	
	// should we override thumb image type?
	$image_type = ($image_type != -1 ? $image_type : $orig_img_type);
	
	// check for allowed image types
	if ($orig_img_type < 1 or $orig_img_type > 3) die("Image type not supported");

	if ($orig_x > $max_x or $orig_y > $max_y)
	{
		// resize
		$per_x = $orig_x / $max_x;
		$per_y = $orig_y / $max_y;
		if ($per_y > $per_x)
		{
			$max_x = $orig_x / $per_y;
		}
		else
		{
			$max_y = $orig_y / $per_x;
		}
	}
	else
	{
		// keep original sizes, i.e. just copy
		if ($save_to_file)
		{
			@copy($from_name, $to_name);
		}
		else
		{
			switch ($image_type)
			{
				case 1:
					header("Content-type: image/gif");
					readfile($from_name);
					break;
				case 2:
					header("Content-type: image/jpeg");
					readfile($from_name);
					break;
				case 3:
					header("Content-type: image/png");
					readfile($from_name);
					break;
			}
		}
		return;
	}
	if ($image_type == 1)
	{
		// should use this function for gifs (gifs are palette images)
		$ni = imagecreate($max_x, $max_y);
	}
	else
	{
		// Create a new true color image
		$ni = ImageCreateTrueColor($max_x,$max_y);
	}
	// Fill image with white background (255,255,255)
	$white = imagecolorallocate($ni, 255, 255, 255);
	imagefilledrectangle( $ni, 0, 0, $max_x, $max_y, $white);
	// Create a new image from source file
	$im = ImageCreateFromType($orig_img_type,$from_name); 
	// Copy the palette from one image to another
	imagepalettecopy($ni,$im);
	// Copy and resize part of an image with resampling
	imagecopyresampled(
	$ni, $im,             // destination, source
	0, 0, 0, 0,           // dstX, dstY, srcX, srcY
	$max_x, $max_y,       // dstW, dstH
	$orig_x, $orig_y);    // srcW, srcH
	
	// save thumb file
	SaveImage($image_type, $ni, $to_name, $image_quality, $save_to_file);

	if($temp)
	{
		unlink($tmpfname); // this removes the file
	}
}
function SaveImage($type, $im, $filename, $quality, $to_file = true) {

  $res = null;

  // ImageGIF is not included into some GD2 releases, so it might not work
  // output png if gifs are not supported
  if(!function_exists('imagegif')) $type = 3;

  switch ($type) {
    case 1:
      if ($to_file) {
        $res = ImageGIF($im,$filename);
      }
      else {
        header("Content-type: image/gif");
        $res = ImageGIF($im);
      }
      break;
    case 2:
      if ($to_file) {
        $res = ImageJPEG($im,$filename,$quality);
      }
      else {
        header("Content-type: image/jpeg");
        $res = ImageJPEG($im, NULL, $quality);
      }
      break;
    case 3:
      if (PHP_VERSION >= '5.1.2') {
        // Convert to PNG quality.
        // PNG quality: 0 (best quality, bigger file) to 9 (worst quality, smaller file)
        $quality = 9 - min( round($quality / 10), 9 );
        if ($to_file) {
          $res = ImagePNG($im, $filename, $quality);
        }
        else {
          header("Content-type: image/png");
          $res = ImagePNG($im, NULL, $quality);
        }
      }
      else {
        if ($to_file) {
          $res = ImagePNG($im, $filename);
        }
        else {
          header("Content-type: image/png");
          $res = ImagePNG($im);
        }
      }
      break;
  }

  return $res;

}

function ImageCreateFromType($type,$filename) {
 $im = null;
 switch ($type) {
   case 1:
     $im = ImageCreateFromGif($filename);
     break;
   case 2:
     $im = ImageCreateFromJpeg($filename);
     break;
   case 3:
     $im = ImageCreateFromPNG($filename);
     break;
  }
  return $im;
}

function db_sql_safe($values)
{
	return mysql_real_escape_string ($values);
}
class display_navigator{
	var $result,$totalRecordFound,$hrefpglinks,$allrows,$fired_query;	
	function __construct($qry,$pagelink,$hrefcls,$noofpg)
	{
			$query=$qry;   //******* query of the data to be display
			//die;
			$result= mysql_query($query); 
			$totalRecFound = mysql_num_rows($result); 

			$noofpages = $noofpg; // this is the number of records to be display on the screen 
	
			$totalRecords=$totalRecFound;
			$totalPages=ceil($totalRecords/$noofpages);
			$showingpage="&nbsp;|&nbsp;";
			
			if((int)$_GET['pageno'] > $totalPages)
			{
				$_GET['pageno']=$totalPages;
			}
			if(!$_GET['pageno'])
			{
				$pageno=1;
				$initlimit=0;
			}else
			{
				$pageno=$_GET['pageno'];
				$initlimit=($pageno*$noofpages)-$noofpages;		
			}
			
			if($pageno>$totalPages){$pageno=1;}
				if($pageno < 6 )
				{
					$startpage = 1;
					if($pageno + 5  >= $totalPages )
						{
							$endpage = $totalPages;
						}	
						else
						{
							$endpage = 10 ;
						}
				}	
			else
				{
					$startpage = $pageno - 5 ;
					if($pageno + 5  > $totalPages )
					{
						$endpage = $totalPages;
					}	
					else
					{
						$endpage = $pageno + 5 ;
					}
					
				}

			for($i=$startpage;$i<=$endpage;$i++)
			{			
				if($i==$pageno && $i==$totalPages)
				{
					$showingpage.="<span class=\"paging_link_current\">$i</span>";
				}
				else if($i==$pageno)
					$showingpage.=" <span class=\"paging_link_current\">$i</span> | ";
				else
					$showingpage.="<A style=\"text-decoration:none\" class='".$hrefcls."' href='".$pagelink."&pageno=$i'>$i</a> | ";// change link name and give u'r page link
			}
			
			if($totalPages>1)
			{			
				if($pageno=="1")
				{
					$page=$pageno + 1;
					$next="<A style=\"text-decoration:none\" class='".$hrefcls."' href='".$pagelink."&pageno=$page'>Next</A>";// change link name and give u'r page link
					$prev="";		
				}else if($pageno==$totalPages)
				{
					$page=$pageno - 1;
					$next="";
					$prev="<A style=\"text-decoration:none\" class='".$hrefcls."' href='".$pagelink."&pageno=$page'>Previous</A>";// change link name and give u'r page link			
				}else
				{
					$page1=$pageno + 1;
					$page2=$pageno - 1;
					$next="<A style=\"text-decoration:none\" class='".$hrefcls."'href='".$pagelink."&pageno=$page1'>Next</A>";// change link name and give u'r page link
					$prev="<A style=\"text-decoration:none\" class='".$hrefcls."' href='".$pagelink."&pageno=$page2'>Previous</A>";// change link name and give u'r page link		
				}
				
			}else
			{
				$next="";
				$prev="";		
			}	
			$query.=" LIMIT $initlimit,$noofpages";
			$result= mysql_query($query);
			$totalRecordFound = mysql_num_rows($result); 
			if($prev == "" && $next =="")
			{$hrefpglinks="";} else {
				$hrefpglinks=$prev. " " .$showingpage." ".$next;
			}
			$this->hrefpglinks=$hrefpglinks;
			$this->result=$result;
			$this->fired_query=$query;			
			$this->totalRecordFound=$totalRecordFound;
	}
}
?>