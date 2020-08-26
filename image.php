<?php

/**
 *
 * 1) Generates the Overlay Image
 *
 *    from URL parameters or defaults.
 *
 */

  // takes a hex color code (without the #) from the URL and turns it into RGB values
  if(!isset($_GET['hex']) ){
    $r = 53; $g = 52; $b = 51;
  }else{
    list($r, $g, $b) = sscanf($_GET['hex'], "%02x%02x%02x");
  }


  // converts 0-100 to 0-127
  // (0 = completely opaque, 127 = completely transparent.)
  if(!isset($_GET['alpha']) ){
    $alpha = 50;
  }else{
    $alpha = round($_GET['alpha']/0.787);
  }


  // generates the PNG with the above parameters
  $im = imagecreatetruecolor(100, 100);
  $red = imagecolorallocatealpha($im, $r, $g, $b, $alpha);
  imagefill($im, 0, 0, $red);
  imagesavealpha($im, TRUE);


  // exports the generate overlay-blob into a variable
  ob_start();

    header('Content-type: image/png');
    imagepng($im);
    imagedestroy($im);

  $raw_img2 = ob_get_contents();
  ob_end_clean();


  // save in an imagemagick object for later
  $src2 = new \Imagick();
  $src2 -> readImageBlob($raw_img2);

  // not used
  // $src2 = new \Imagick($_SERVER['DOCUMENT_ROOT'] ."/app/themes/mmc-sage/assets/images/overlay.png");




/**
 *
 * 2) Combines the two images *
 *
 *    Basic command convert img1 img2 -compose Multiply -composite out
 *
 */



  // makes sure we were actually passed in image in the URL
  if(!isset($_GET['image']) ){
    die();
  }
  else{
    $src1 = new \Imagick($_SERVER['DOCUMENT_ROOT'] .rawurldecode($_GET['image']));
  }


  // makes sure they are the same size
  $src2->resizeimage(
    $src1->getImageWidth(),
    $src1->getImageHeight(),
    \Imagick::FILTER_LANCZOS,
    1
  );


  // magic
  $src1->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
  $src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
  $src1->compositeImage($src2, Imagick::COMPOSITE_MULTIPLY, 0, 0);
  // $src1->writeImage("./output.png");


  // output
  header("Content-Type: image/png");
  $printimage = $src1->getImageBlob();
  echo $printimage;

?>

