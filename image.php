<?php

/**
 *
 * 1) Generates the Overlay Image
 *
 *    from URL parameters or defaults.
 *    this script takes 
 *
 *    Usage: …/imagecombine.php?image=[url-encoded-image]&hex=FF2200&alpha=33
 *    Image: A URL encoded path to the image. 
 *           In this case here it's on the same server, but you could also get
 *           it from a URL get its contents
 *    Hex:   This is the color of the overlay to generate. Optional
 *    Alpha: Transparency of the Overlay. Optional
 *
 */


  // takes a hex color code (without the #) from the URL 
  // and turns it into RGB values, or defaults to black
  if(!isset($_GET['hex']) ){
    $r = 0; $g = 0; $b = 0;
  }else{
    list($r, $g, $b) = sscanf($_GET['hex'], "%02x%02x%02x");
  }


  // gets transparency from URL, and converts 0-100 to 0-127
  // (0 = completely opaque, 127 = completely transparent.)
  if(!isset($_GET['alpha']) ){
    $alpha = 0;
  }else{
    $alpha = round($_GET['alpha']/0.787);
  }


  // generates a PNG with the above parameters
  $im = imagecreatetruecolor(100, 100);
  $red = imagecolorallocatealpha($im, $r, $g, $b, $alpha);
  imagefill($im, 0, 0, $red);
  imagesavealpha($im, TRUE);


  // writes the generated image blob into a variable
  ob_start();

    header('Content-type: image/png');
    imagepng($im);
    imagedestroy($im);

  $raw_img2 = ob_get_contents();
  ob_end_clean();


  // save in an imagemagick object for later
  $src2 = new \Imagick();
  $src2 -> readImageBlob($raw_img2);


/**
 *
 * 2) Combines the two images *
 *
 *    Fetches the main image from the URL, decodes it, 
 *    and combines it with the main overlay generated above.
 *    Based on command: convert img1 img2 -compose Multiply -composite out
 *
 */


  // makes sure we were actually passed in image in the URL
  if(!isset($_GET['image']) ){
    die();
  }
  else{
    $src1 = new \Imagick($_SERVER['DOCUMENT_ROOT'] .rawurldecode($_GET['image']));
  }


  // makes sure the overlay is the same as the image
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


  // output as image
  header("Content-Type: image/png");
  $printimage = $src1->getImageBlob();
  echo $printimage;

?>

