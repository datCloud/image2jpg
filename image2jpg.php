<?php

function SetBeackgroundColor($originalImage, $outputImage, $type, $quality, $color = array(255, 255, 255)){
    if($type === 'webp'){
        $input = imagecreatefromwebp($originalImage);
        $width = imagesx($input);
        $height = imagesy($input);
    }
    else{
        $input = imagecreatefrompng($originalImage);
        list($width, $height) = getimagesize($originalImage);
    }
    $output = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($output,  255, 255, 255);
    imagefilledrectangle($output, 0, 0, $width, $height, $white);
    imagecopy($output, $input, 0, 0, 0, 0, $width, $height);
    imagejpeg($output, $outputImage, $quality);
    imagedestroy($output);
    return $outputImage;
}

function ImageToJpg($originalImage, $quality){
    $exploded = explode('.',$originalImage);
    $ext = strtolower($exploded[count($exploded) - 1]); 
    $outputImage = getcwd().'/JPG/'.strtolower($exploded[0].'.jpg');

    if (preg_match('/jpg|jpeg/i',$ext)){
        $imageTmp=imagecreatefromjpeg($originalImage);
    }
    else if (preg_match('/png/i',$ext)){
        return SetBeackgroundColor($originalImage, $outputImage, 'png', $quality);
    }
    else if (preg_match('/webp/i',$ext)){
        return SetBeackgroundColor($originalImage, $outputImage, 'webp', $quality);
    }
    else if (preg_match('/gif/i',$ext)){
        $imageTmp=imagecreatefromgif($originalImage);
    }
    else if (preg_match('/bmp/i',$ext)){
        $imageTmp=imagecreatefrombmp($originalImage);
    }
    else{
        echo 'Cannot convert the file: '.$originalImage;
        return 0;
    }

    imagejpeg($imageTmp, $outputImage, $quality);
    imagedestroy($imageTmp);

    return $outputImage;
}

if($folder = opendir('./')){
    if(!is_dir(getcwd().'/JPG')){
        if(!mkdir(getcwd().'/JPG')){
            echo 'Cannot create folder :(';
        }
    }
    while(false !== ($filename = readdir($folder))){
        if(!is_dir($filename) && preg_match('/jpg|jpeg|png|gif|webp|bmp/i', array_pop(explode('.', $filename)))){
            chmod(ImageToJpg($filename, 90), 0777);
        }
    }
    chmod(getcwd().'/JPG', 0777);
    closedir($folder);
}

?>
