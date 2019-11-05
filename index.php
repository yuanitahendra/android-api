<?php
header('Content-Type: application/json');
$text = $_POST['text'];
$image = $_FILES['image']['name'];

$filedest = dirname(__FILE__) .'/'. $image;
move_uploaded_file($_FILES['image']['tmp_name'], $filedest);

$im = imagecreatefrompng($image);

if($im && imagefilter($im, IMG_FILTER_GRAYSCALE))
{
    imagepng($im, $image);
    $text = strtolower(str_replace(" ","",$text));
    print_r(json_encode((object) array(
      'text' => $text,
      'image' => $image,
     )));
}
else
{
  print_r(json_encode((object) array(
    'text' => "Error",
    'image' => "",
   )));
}

imagedestroy($im);
?>
