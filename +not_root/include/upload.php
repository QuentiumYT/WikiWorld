<?php
// Upload a picture to the server
function uploadpic($picData, $picName, $picType)
{
    if (isset($picData['name']) && !empty($picData['name'])) {
        // Temp file name
        $temp = $picData['tmp_name'];
        // New name
        $name = $picData['name'];
        // Remove null byte in str
        $name = str_replace(chr(0), '', $name);
        // Get extension
        $exploded = explode('.', $name);
        $ext = $exploded[count($exploded) - 1];
        // Get file size if too big
        $size = $picData['size'];
        // Get MIME type
        $type = $picData['type'];

        // Arrays of values to match
        $allowed_type = ['image/bmp', 'image/gif', 'image/vnd.microsoft.icon', 'image/jpeg', 'image/png', 'image/tiff', 'image/webp'];
        $allowed_ext = ['.jpg', '.jpeg', '.png'];
        $denied_ext = ['.php', '.js', '.exe', '.sh', '.bash', '.cmd', '.bat', '.jar', '.ps', '.py', '.asp'];

        // Check if str equals any item in list
        // Python: if "image/png" in allowed_type:
        function any_string($string, $list)
        {
            foreach ($list as $element) {
                if (strpos($string, $element) !== false) {
                    return true;
                }
            }
            return false;
        }

        if ($size <= 10000000) {
            if (in_array($type, $allowed_type)) {
                if (any_string($name, $allowed_ext)) {
                    if (!any_string($name, $denied_ext)) {
                        if ($picType === 'cars') {
                            // Folders name
                            $folder = '../img/' . $picType . '/+' . $picName . '/';
                            $folder_s = '../img/' . $picType . '/+' . $picName . '_s/';
                            // Create folders if not exists
                            if (!file_exists($folder)) {
                                mkdir($folder, 0750);
                                mkdir($folder_s, 0750);
                            }
                            // Move files to new path
                            move_uploaded_file($temp, $folder . $picName . '.' . $ext);

                            $newName = $folder . $picName . '.jpg';
                            // Check if png
                            if (preg_match('/png/i', $ext)) {
                                // Create JPG from PNG and delete PNG
                                $imageTmp = imagecreatefrompng($folder . $picName . '.' . $ext);
                                imagejpeg($imageTmp, $newName, 100);
                                imagedestroy($imageTmp);
                                unlink($folder . $picName . '.' . $ext);
                            } else if (preg_match('/jpeg/i', $ext)) {
                                // Rename if extension is different
                                rename($folder . $picName . '.' . $ext, $newName);
                            }

                            // Resample the picture to scale it down (small = 220px width)
                            list($width, $height) = getimagesize($newName);
                            $newWidth = 220;
                            $newHeight = $newWidth * $height / $width;
                            $newImage = imagecreatetruecolor($newWidth, $newHeight);
                            $imageTmp = imagecreatefromjpeg($newName);
                            imagecopyresampled($newImage, $imageTmp, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                            // Destroy in RAM
                            imagedestroy($imageTmp);
                            // Save it to small folder with 100% JPG quality
                            imagejpeg($newImage, $folder_s . $picName . '.jpg', 100);
                        } else if ($picType === 'constructors') {
                            $folder = '../img/' . $picType . '/';
                            // Create the folder (should exist)
                            if (!file_exists($folder)) {
                                mkdir($folder, 0750);
                            }
                            // Move the PNG (should be a PNG file)
                            move_uploaded_file($temp, $folder . $picName . '.png');
                        }
                        echo 'Image envoyée !';
                    } else {
                        echo 'Haha, tu as vraiment essayé d\'injecter du PHP ?';
                    }
                } else {
                    echo 'Ce type d\'image n\'est pas supporté (.jpg, .jpeg, .png seulement) !';
                }
            } else {
                echo 'Ce fichier n\'est pas une image...';
            }
        } else {
            echo 'Ce fichier est trop grand (>10Mo)';
        }
    } else {
        echo 'Aucune image n\'a été reçue !';
    }
}
