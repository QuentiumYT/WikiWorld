<?php
include_once('sql.php');

// Function to delete a folder with recursive files in it
function delTree($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

$data = $_POST;
$type = $data['type'];

// Check type to know what's the columns name
if ($type === 'cars') {
    // Get pwd
    $pwd = $data['passwd'];
    $columnId = 'car_id';
    $columnName = 'car_name';
    $id = $data[$columnId];
} else if ($type === 'constructors') {
    // Get pwd
    $pwd = $data['passwd'];
    $columnId = 'brand_id';
    $columnName = 'brand_name';
    $id = $data[$columnId];
} else if ($type === 'favorites') {
    // No password for favorites
    $columnId = 'user_id';
    $columnId2 = 'car_id';
    $id = $data[$columnId];
    $id2 = $data[$columnId2];
    echo '<p>Suppression de l\'élément <b>' . $id . '</b> avec <b>' . $id2 . '</b> dans la table <b>' . $type . '</b>.</p>';
    // Specific function to delete with 2 IDs
    deleteStarsDb($type, $columnId, $id, $columnId2, $id2);
    $loc = $type;
}

if (isset($pwd)) {
    // Hash correspond to 'cars_protect'
    if (hash('sha512', $pwd) === 'cf1f3f2a28074b650383843498cc06db0ef4e5854179f42907cec3755bec5a1f0fb159bf437cb155c0b47d2499d9910ee2d4e91f5749be0d911a1f366287416f') {
        echo '<p>Suppression de l\'élément <b>' . $id . '</b> dans la table <b>' . $type . '</b>.</p>';
        // Simply delete the ID
        deleteDb($type, $columnId, $id);
        if ($type === 'cars') {
            // Delete all cars pictures (small ones as well)
            $foldName = '../img/' . $type . '/' . str_replace(' ', '_', $data[$columnName]);
            if (file_exists($foldName)) {
                delTree($foldName);
                delTree($foldName . '_s');
            }
        } else if ($type === 'constructors') {
            // Dlete manufacturer picture
            $imgName = '../img/' . $type . '/Manufacturer_' . str_replace(' ', '_', $data[$columnName]) . '.png';
            if (file_exists($imgName)) {
                unlink($imgName);
            }
        }
        $loc = $type;
    } else {
        // Fail, return with error code to display
        echo '<p>Vous n\'êtes pas authorisé à supprimer cet élément.</p>';
        $loc = 'delete/' . substr($type, 0, -1) . '/' . $id . '?code=unauthorized';
    }
}
// Go back to the base location
header('Refresh: 3; URL=/' . $loc);
