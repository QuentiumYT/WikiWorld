<?php
include_once('sql.php');

$data = $_POST;
$type = $data['type'];
// Remove type to not edit it in the DB
unset($data['type']);
// Get password for these types
if ($type === 'cars' || $type === 'constructors') {
    $baseName = $data['base_name'];
    $pwd = $data['passwd'];
    // Unset other unneeded vars
    unset($data['base_name']);
    unset($data['passwd']);
    unset($data[$columnId]);
}

if ($type === 'cars') {
    $columnId = 'car_id';
    $columnName = 'car_name';
    $id = $data[$columnId];
    // 156 cars in NFSW, else, it's manually added
    if ($id > 156) {
        if (strpos($data[$columnName], '+') === false) {
            // $data['car_pic'] = str_replace(' ', '_', $data[$columnName]) . '.jpg';
            $data[$columnName] = '+' . $data[$columnName];
        } else {
            // $data['car_pic'] = str_replace([' ', '+'], ['_', ' '], $data[$columnName]) . '.jpg';
            $data[$columnName] = '+' . str_replace('+', '', $data[$columnName]);
        }
    }
} else if ($type === 'constructors') {
    $columnId = 'brand_id';
    $columnName = 'brand_name';
    $id = $data[$columnId];
} else if ($type === 'favorites') {
    $columnId = 'user_id';
    $columnId2 = 'car_id';
    $id = $data[$columnId];
    $id2 = $data[$columnId2];
    $key = 'stars';
    $value = $data[$key];
    echo '<p>Mise à jour de la clé <b>' . $key . '</b> avec la valeur <b>' . $value . '</b>.</p>';
    // Specific function to edit with multiple IDs
    editStarsDb($type, $key, $value, $columnId, $id, $columnId2, $id2);
    $loc = $type;
}

if (isset($pwd)) {
    // Hash correspond to 'cars_protect'
    if (hash('sha512', $pwd) === 'cf1f3f2a28074b650383843498cc06db0ef4e5854179f42907cec3755bec5a1f0fb159bf437cb155c0b47d2499d9910ee2d4e91f5749be0d911a1f366287416f') {
        // Rename folder name
        $oldName = '../img/' . $type . '/' . str_replace(' ', '_', $baseName);
        $newName = '../img/' . $type . '/' . str_replace(' ', '_', $data[$columnName]);
        // If name is not the same
        if (!$oldName === $newName) {
            // Rename old to new if not exists
            if (file_exists($oldName) && !file_exists($newName)) {
                rename($oldName, $newName);
                rename($oldName . '_s', $newName . '_s');
            } else {
                // Unknown folder in database
                exit();
                header('Refresh: 1; URL=/' . substr($type, 0, -1) . '/' . $id);
            }
        }

        // Loop to edit the values one by one
        foreach ($data as $key => $value) {
            echo '<p>Mise à jour de la clé <b>' . $key . '</b> avec la valeur <b>' . $value . '</b>.</p>';
            editDb($type, $key, $value, $columnId, $id);
        }
        $loc = substr($type, 0, -1) . '/' . $id;
    } else {
        // Return error code to called URL
        echo '<p>Vous n\'êtes pas authorisé à éditer cet élément.</p>';
        $loc = 'edit/' . substr($type, 0, -1) . '/' . $id . '?code=unauthorized';
    }
}
// Go back to the base location
header('Refresh: 3; URL=/' . $loc);
