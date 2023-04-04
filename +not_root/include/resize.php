<?php
// Get file name parameter
$filename = '..' . $_GET['f'];
if (!file_exists($filename) || is_dir($filename)) {
    $filename = '../img/cars/unknown.jpg';
}
// Get new file size
$size = $_GET['s'];

// Override content type to display a picture
header('Content-type: image/jpeg');

// Get height and width of current picture
list($width, $height) = getimagesize($filename);
// Math to know proportional size
$newWidth = intval($size);
$newHeight = intval($newWidth * $height / $width);

// Create a blank picture
$newImage = imagecreatetruecolor($newWidth, $newHeight);
// Get picture in RAM
$imageTmp = imagecreatefromjpeg($filename);
// Resample the picture pixels to new dimensions
imagecopyresampled($newImage, $imageTmp, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

// Destroy RAM cache
imagedestroy($imageTmp);
// Create the picture to display with 99% JPG quality
imagejpeg($newImage, null, 99);
