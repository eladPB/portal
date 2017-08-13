<?php
/**
 * Created by PhpStorm.
 * User: bs
 * Date: 28/05/2016
 * Time: 11:22
 */

class general_util
{
//    public function __construct()
//    {
//
//    }


// ////////////////////// General //////////////////////

    // If you are not Administrator Go to Dashboard
    public function IsIAdmin()
    {
        If($_SESSION['privileges'] != 2 ){
            header('location: /');
        }
        return ;
    }

    public function GetPost($name)
    {
		$val = '';
        if ($_POST) {
			if (isset($_POST[$name])) {
				$val = mysql_real_escape_string($_POST[$name]);
			}
		}
		elseif ($_GET) {
			if (isset($_GET[$name])) {
				$val = mysql_real_escape_string($_GET[$name]);
			}
		}
        return $val;


    }

    public function GetPostOrZero($name)
    {
        $val = 0;
        if ($_POST) {
            if (isset($_POST[$name])) {
                $val = mysql_real_escape_string($_POST[$name]);
            }
        }
        elseif ($_GET) {
            if (isset($_GET[$name])) {
                $val = mysql_real_escape_string($_GET[$name]);
            }
        }
        return $val;
    }


    public function GetPostOrNumbertOrZero($name)
    {
        $val = 0;
        if ($_POST) {
            if (isset($_POST[$name])) {
                $val = mysql_real_escape_string($_POST[$name]);
            }
        }
        elseif ($_GET) {
            if (isset($_GET[$name])) {
                $val = mysql_real_escape_string($_GET[$name]);
            }
        }
        if (!is_numeric($val)){
        $val = 0;
        }

        return $val;
    }


    public function GetPostOrSession($name)
    {
        $val = '';
        if ($_POST) {
            if (isset($_POST[$name])) {
                $val = mysql_real_escape_string($_POST[$name]);
            }
        } elseif ($_GET) {
            if (isset($_GET[$name])) {
                $val = mysql_real_escape_string($_GET[$name]);
            }
        } elseif ($_SESSION[$name]) {
            $val = mysql_real_escape_string($_SESSION[$name]);
        }
        return $val;
    }

    function resize_image($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $new_width = $w;
            $new_height = $h;
        } else {
            if ($w/$h > $r) {
                $new_width = $h*$r;
                $new_height = $h;
            } else {
                $new_height = $w/$r;
                $new_width = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Output Save
        imagejpeg($dst, $file, 100);

        return $dst;
    }



}

