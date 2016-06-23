<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
if ( ! function_exists('upload_pic'))
{
	//============================================
	// upload picture 
	//============================================
	function upload_pic($newfilename='', $delfile='', $directory='img/userfiles', $prefix='', $delthumb=false, $error='', $subdir=''){
	
	 $dir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$directory.'/';
	
	 if(empty($newfilename['name'])){ return $delfile; }
	 
	 	$error[1]=isset($error[1])?$error[1]:'Error uploading!';
		$error[2]=isset($error[2])?$error[2]:'Too big image size! Max size 1024x1024px';
		$error[3]=isset($error[3])?$error[3]:'Too big file size! Max file size 2Mb';
		$error[4]=isset($error[4])?$error[4]:'Incorrect file type! Use .jpg , .png , .gif file extension only';
		
	  if(!empty($delfile)){ 
	  		@unlink($dir.$delfile);
			if($delthumb=true){ 
				@unlink($dir.'small/'.$delfile);
				@unlink($dir.'big/'.$delfile);
                @unlink($dir.'thumbnail/'.$delfile);
			}
		}

		 $mb_max = 2; //MAX FILE SIZE = 2Mb
		 if(!is_dir($dir)) mkdir($dir, 0777);
	
		 if (!empty($newfilename['name']))   /*UPLOAD THUMB*/
 		    {
		         $filename = strtolower($newfilename['name']);
		         $filetype = explode(".", $filename);
		         $type = $filetype[count($filetype) - 1];
				 $filename = $prefix.md5(microtime().$filename).'.'.$type;
				 $size_arr = @getimagesize($newfilename['tmp_name']);	
	
		         if (is_uploaded_file($newfilename['tmp_name']))
		         {
		             if ($type=="jpg" || $type=="jpeg" || $type=="png" || $type=="gif")
		             {
		                 if ($newfilename['size']<(1024*1024*$mb_max))
		                 {
							 /* if ($size_arr[0]<1024 and $size_arr[1]<1024)
							 { */
								 if (file_exists($dir.$filename)){ unlink($dir.$filename); }
								 
								if (!(move_uploaded_file($newfilename['tmp_name'],$dir.$filename)))
								{echo '<script>alert("'.$error[1].'"); self.location.href=\''.$_SERVER['HTTP_REFERER'].'\';</script>'; die(); }
								else{ 
									return $filename;
								}
							 /* }
							 else{ echo '<script>alert("'.$error[2].'"); self.location.href=\''.$_SERVER['HTTP_REFERER'].'\';</script>'; die(); } */
						 }
					     else{ echo '<script>alert("'.$error[3].'"); self.location.href=\''.$_SERVER['HTTP_REFERER'].'\';</script>'; die(); }
					}
					else{ echo '<script>alert("'.$error[4].'"); self.location.href=\''.$_SERVER['HTTP_REFERER'].'\';</script>'; die(); }
				}
				else{ echo '<script>alert("'.$error[1].'"); self.location.href=\''.$_SERVER['HTTP_REFERER'].'\';</script>'; die(); }
		   }
	}
	//============================================
}





if ( ! function_exists('resize_pic'))
{
	//============================================
	// resize picture 
	//============================================
	function resize_pic($file,$newprefix='',$picw=100,$pich=100,$directory='img/userfiles',$newdirectory='img/userfiles', $subdir=''){
	
	$dir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$directory.'/';
	$newdir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$newdirectory.'/';
	
		$filecur=$dir.$file;
		$newfilecur=$newdir.$newprefix.$file;

                if(file_exists($newfilecur)==false){

			$size = getImageSize($filecur); //
			$srcWidth = $size[0]; $srcHeight = $size[1]; //   -
			$destWidth = $picw;
			$destHeight = $pich;

			if (!function_exists('imagecreatefromjpeg')) {
				die("PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed");
			}
			if (!function_exists('imagecreatetruecolor')) {
				die("PHP running on your server does not support GD version 2.x, please switch to GD version 1.x on the config page");
			}

			if($size[2] == IMAGETYPE_PNG){
				$src_img = @imagecreatefrompng($filecur);
			}else if($size[2] == IMAGETYPE_GIF){
                $src_img = @imagecreatefromgif($filecur);
            }else{
				$src_img = @imagecreatefromjpeg($filecur);
			}

			if (!$src_img) {
				//die("Invalid image!");
				return false;
			}else{

			$dst_img = imagecreatetruecolor($destWidth, $destHeight);

			  if($size[2] == IMAGETYPE_PNG) {
				// Turn off transparency blending (temporarily)
				imagealphablending($dst_img, false);

				// Create a new transparent color for image
				$color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);

				// Completely fill the background of the new image with allocated color.
				imagefill($dst_img, 0, 0, $color);

				// Restore transparency blending
				imagesavealpha($dst_img, true);
			  }

			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, (int)$destWidth, (int)$destHeight, $srcWidth, $srcHeight);
			@unlink($newfilecur);

			if($size[2] == IMAGETYPE_PNG){
				imagepng($dst_img, $newfilecur, 9);

                        }else if($size[2] == IMAGETYPE_GIF){
                                imagegif($dst_img, $newfilecur);
                        }else{
				imagejpeg($dst_img, $newfilecur, 100);
			}

			imagedestroy($src_img);
			imagedestroy($dst_img);

			}

		}
	
	 return true;	
	}
	//============================================
}



if ( ! function_exists('zoom_pic'))
{

	//============================================
	// zoom pic 
	//============================================
	function zoom_pic($img,$width,$height,$directory='img/userfiles', $subdir=''){
	 $dir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$directory.'/';
	 $ret['width']=$width;
	 $ret['height']=$height;
	 $ret['top']=0;
	 $ret['left']=0;
		
		if ($size=@getimagesize($dir.$img)) {
			$x_w=$size[0]-$width;
			$x_h=$size[1]-$height;
				  	
	        if($x_w < $x_h){
	        	$ratio = $height / $size[1];
				$ret['width'] = intval($size[0] * $ratio);
				$ret['left'] = intval(($width - $ret['width']) / 2);
	        }elseif($x_w > $x_h) {
	        	$ratio = $width / $size[0];
	        	$ret['height'] = intval($size[1] * $ratio);
				$ret['top'] = intval(($height - $ret['height']) / 2);
	        }
		}
	   
	 return $ret;    
	}
	//============================================
}



if ( ! function_exists('setlogo_pic'))
{
	//============================================
	// set logo
	//============================================
	function setlogo_pic($img,$logo,$paddingWidth,$paddingHeight,$zoom_width,$zoom_height,$directory='img/userfiles',$newdirectory='',$resize=false, $subdir=''){
	
	 $dir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$directory.'/';
	 $logodir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/img/';
	 
	 if(empty($newdirectory)){
	 	$newdir=$dir;
	 }else{
	 	$newdir=$dir.$newdirectory.'/';
	 }
	 
         if($resize==true){
            resize_pic($img,$newdirectory.'/',$zoom_width,$zoom_height,$directory);
            $srcImage = @imagecreatefromjpeg($newdir.$img);	
         }else{
            $srcImage = @imagecreatefromjpeg($dir.$img);
         }

              if($srcImage){	                
                $logoImage = imagecreatefrompng($logodir.$logo);

                $srcWidth  = imagesx($srcImage);
                $srcHeight = imagesy($srcImage);

                $logoWidth  = imagesx($logoImage);
                $logoHeight = imagesy($logoImage);

                imagealphablending($logoImage,false);
                imagesavealpha($logoImage,true);

                $trcolor = imagecolorallocate($logoImage,255,255,255);
                imagecolortransparent($logoImage,$trcolor);

                imagecopy($srcImage,$logoImage,$srcWidth-$logoWidth-$paddingWidth,$srcHeight-$logoHeight-$paddingHeight,0,0,$logoWidth,$logoHeight);

                @unlink($newdir.$img);

                imagejpeg($srcImage,$newdir.$img,85);

                imagedestroy($srcImage); 

                return true; 
              }else{
                return false;
              }        
	}
	//============================================
}



if(!function_exists('img_resize_new'))
{
/**
	Функция изменяет размер изображения,
	 если передать вместо ширины/высоты 0, то размер изменится пропорционально

	@param string полный путь к файлу изображения
	@param string путь для нового изображения
	@param int    ширина
	@param int    высота
	@param string цвет фона, если (останется свободное место)
	@param int    качество на выходе в %
	
	@return boolean */
	function img_resize_new($src, $out, $width, $height, $color = 0xFFFFFF, $quality = 85, $subdir='') 
	{
        ini_set('memory_limit','512M');
        
		$src=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$src;
		$out=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$out;
		
		if(!file_exists($src)){ return false; }
		if($size = @getimagesize($src))
		{
			// Исходя из формата (mime) картинки, узнаем с каким форматом имеем дело
			$format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
			if($format=='png'){$quality=9;}    
			//и какую функцию использовать для ее создания
			$picfunc = 'imagecreatefrom'.$format; 
			
			$image_src = $picfunc($src); // sourse size
			$w_src = imagesx($image_src);
			$h_src = imagesy($image_src);
            
            @unlink($out);
            
			if($width==$w_src and $height==$h_src){
				copy($src, $out);
				imagedestroy($image_src);						
			}else{
				if($width && !$height)
				{
					$height = $h_src * ( $width / $w_src );
				}
				elseif($height && !$width)
				{
					$width = $w_src * ( $height / $h_src );
				}
				elseif(!$width && !$height)
				{
					$width = $w_src;
					$height = $h_src;   
				}
				
				$canvas = imagecreatetruecolor( $width, $height );
				imagealphablending($canvas, false);
				$color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
				imagefill($canvas, 0, 0, $color);
				imagesavealpha($canvas, true);
				
				$src_x = $src_y = 0;
				$src_w = $w_src;
				$src_h = $h_src;
				
				$cmp_x = $w_src  / $width;
				$cmp_y = $h_src / $height;
				// calculate x or y coordinate and width or height of source
				if ( $cmp_x > $cmp_y ) {
					$src_w = round( ( $w_src / $cmp_x * $cmp_y ) );
					$src_x = round( ( $w_src - ( $w_src / $cmp_x * $cmp_y ) ) / 2 );
				} elseif ( $cmp_y > $cmp_x ) {
					$src_h = round( ( $h_src / $cmp_y * $cmp_x ) );
					$src_y = round( ( $h_src - ( $h_src / $cmp_y * $cmp_x ) ) / 2 );
				}
				
				if($width==$height)
				{
					if($w_src>$h_src)
					  imagecopyresampled($canvas, $image_src, 0, 0,
                      round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                      0, $width, $height, min($w_src,$h_src), min($w_src,$h_src));  
					if ($w_src<$h_src) 
					  imagecopyresampled($canvas, $image_src, 0, 0, 0, 0, $width, $height,
					  min($w_src,$h_src), min($w_src,$h_src)); 
					if ($w_src==$h_src) 
					  imagecopyresized($canvas, $image_src, 0, 0, 0, 0, $width, $height, $w_src, $w_src); 
				}
				else{
				  imagecopyresampled( $canvas, $image_src, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h );
				//imagecopyresampled( $canvas, $image_src, 0, 0, 0, 0, $width, $height, $src_w, $src_h );
				}
				$func_image = 'image'.$format;
				$func_image($canvas, $out, $quality);
				imagedestroy($image_src);
				imagedestroy($canvas);
				
			}
			
			return true;       
			
		}
	}
}

if ( ! function_exists('upload_photo'))
{
	//============================================
	// upload picture 
	//============================================
	function upload_photo($newfilename='', $delfile='', $directory='userfiles', $prefix='', $delthumb=false){
	clearstatcache();
	$ret['error']='';
	$ret['filename']='';
	$dir=$_SERVER['DOCUMENT_ROOT'].'/'.$directory.'/';
	 if(empty($newfilename['name'])){ $ret['filename']=$delfile; return $ret; }		
	  if(!empty($delfile)){ 
	  		@unlink($dir.$delfile);
			if($delthumb=true){ 
			    @unlink($dir.$defile);
			}
		}
		 $mb_max = 30; //MAX FILE SIZE = 100Mb
		 if(!is_dir($dir)) mkdir($dir, 0777);
		 if (!empty($newfilename['name']))   /*UPLOAD THUMB*/
 		    {
		         $filename = strtolower($newfilename['name']);
		         $filetype = explode(".", $filename);
		         $type = $filetype[count($filetype) - 1];
				 $filename = $prefix.md5(microtime().$filename).'.'.$type;
				 $size_arr = @getimagesize($newfilename['tmp_name']);	
		         if (!empty($newfilename['tmp_name']))
		         {
		             if ($type=="jpg" || $type=="jpeg" || $type=="png" || $type=="gif")
		             {
		                 if ($newfilename['size']<(1024*1024*$mb_max)) 
		                 {
							 if ($size_arr[0]<=2200 and $size_arr[1]<=2200)
							 {
								 if (file_exists($dir.$filename)){ unlink($dir.$filename); }					
								if (!(move_uploaded_file($newfilename['tmp_name'],$dir.$filename)))
								{ $ret['error'] = "Error upload ! "; }
								else{ 
									$ret['filename'] = $filename;
								}
							 }
							 else{ $ret['error'] = "Too big picture size! Max 2200x2200"; }
						 }
					     else{ $ret['error'] = "Too big picture size!"; }
					}
					else{ $ret['error'] = "Uncorrect file type!";}
				}
				else{ $ret['error'] = "Error upload!";  }			   
		   }
		   unset($size_arr);
		   return $ret;
	}
	//============================================

}

if(!function_exists('img_resize_new2'))
{
/**
	Функция изменяет размер изображения,
	 если передать вместо ширины/высоты 0, то размер изменится пропорционально

	@param string полный путь к файлу изображения
	@param string путь для нового изображения
	@param int    ширина
	@param int    высота
	@param string цвет фона, если (останется свободное место)
	@param int    качество на выходе в %
	
	@return boolean */
function img_resize_new2($src, $out, $width, $height, $color = 0xFFFFFF, $quality = 80, $subdir='') 
{
    ini_set('memory_limit','512M');
    
    $src=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$src;
    $out=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$out;
    
    if(!file_exists($src)){return false;}
    if($size = @getimagesize($src))
    {
        // Исходя из формата (mime) картинки, узнаем с каким форматом имеем дело
        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        if($format=='png'){$quality=9;}    
        //и какую функцию использовать для ее создания
        $picfunc = 'imagecreatefrom'.$format; 
        
        $image_src = $picfunc($src); // sourse size
        $w_src = imagesx($image_src);
        $h_src = imagesy($image_src);
        
        @unlink($out);
        
        if($width && !$height)
        {
            $height = $h_src * ( $width / $w_src );
        }
        elseif($height && !$width)
        {
            $width = $w_src * ( $height / $h_src );
        }
        elseif(!$width && !$height)
        {
            $width = $w_src;
            $height = $h_src;   
        }
        
        $canvas = imagecreatetruecolor( $width, $height );
        imagealphablending($canvas, false);
        $color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $color);
        imagesavealpha($canvas, true);
        
        $src_x = $src_y = 0;
        $src_w = $w_src;
        $src_h = $h_src;
        
        $cmp_x = $w_src  / $width;
        $cmp_y = $h_src / $height;
        // calculate x or y coordinate and width or height of source
        if ( $cmp_x < $cmp_y ) {
            $src_w = round( ( $w_src / $cmp_x * $cmp_y ) );
            $src_x = round( ( $w_src - ( $w_src / $cmp_x * $cmp_y ) ) / 2 );
        } elseif ( $cmp_y < $cmp_x ) {
            $src_h = round( ( $h_src / $cmp_y * $cmp_x ) );
            $src_y = round( ( $h_src - ( $h_src / $cmp_y * $cmp_x ) ) / 2 );
        }
        
        if($width==$height)
        {
            if($w_src>$h_src)
              imagecopyresampled($canvas, $image_src, 0, 0,
              round((max($w_src,$h_src)-min($w_src,$h_src))/2),
              0, $width, $height, min($w_src,$h_src), min($w_src,$h_src));  
            if ($w_src<$h_src) 
              imagecopyresampled($canvas, $image_src, 0, 0, 0, 0, $width, $height,
              min($w_src,$h_src), min($w_src,$h_src)); 
            if ($w_src==$h_src) 
              imagecopyresized($canvas, $image_src, 0, 0, 0, 0, $width, $height, $w_src, $w_src); 
        }
        else{
          imagecopyresampled( $canvas, $image_src, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h );
          //imagecopyresampled( $canvas, $image_src, 0, 0, 0, 0, $width, $height, $src_w, $src_h );
        }
        $func_image = 'image'.$format;
        $func_image($canvas, $out, $quality);
        imagedestroy($image_src);
        imagedestroy($canvas);
        
        return true;       
        
    }

}
}


if ( ! function_exists('del_pic'))
{
	//============================================
	// delete picture 
	//============================================
	function del_pic($delfile='', $directory='img/userfiles', $subdir=''){
	
	 $dir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$directory.'/';
		
	    if(!empty($delfile)){ 
	  		@unlink($dir.$delfile);
		}

    }
}

?>