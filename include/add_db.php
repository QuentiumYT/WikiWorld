<?php
include_once('sql.php');
include_once('upload.php');

// Check if type is set
if (isset($_POST['type'])) {
    $data = $_POST;
    $type = $data['type'];
    // Remove type, image and MAX_FILES_SIZE to not add them in the DB
    unset($data['type']);
    unset($data['image']);
    unset($data['MAX_FILES_SIZE']);
    if ($type === 'cars') {
        // Get default name
        $baseName = $data['car_name'];
        // Know with + if it was manually added
        $data['car_name'] = '+' . $baseName;
        // Register picture path
        $data['car_pic'] = str_replace(' ', '_',  $baseName) . '.jpg';
        // Display what's gonna be added
        foreach ($data as $key => $value) {
            echo '<p>Ajout de la clé <b>' . $key . '</b> avec la valeur <b>' . $value . '</b>.</p>';
        }
        // Use the function to add the data
        addDb($type, $data);
        // Upload the picture from the client with the details
        uploadPic($_FILES['image'], str_replace(' ', '_',  $baseName), $type);
    } else if ($type === 'constructors') {
        // Get default name
        $baseName = 'Manufacturer_' . str_replace(' ', '_', $data['brand_name']);
        // Register picture path
        $data['brand_pic'] =  $baseName . '.png';
        // Display what's gonna be added
        foreach ($data as $key => $value) {
            echo '<p>Ajout de la clé <b>' . $key . '</b> avec la valeur <b>' . $value . '</b>.</p>';
        }
        // Use the function to add the data
        addDb($type, $data);
        // Upload the picture from the client with the details
        uploadPic($_FILES['image'], str_replace(' ', '_',  $baseName), $type);
    } else if ($type === 'players') {
        // Display what's gonna be added
        echo '<p>Ajout de la clé <b>user_name</b> avec la valeur <b>' .  $data['user_name'] . '</b>.</p>';
        // Try to get IP and city or country
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            addDb('user_ip', $_SERVER["HTTP_CLIENT_IP"]);
        }
        if (!empty($_SERVER["GEOIP_CITY"])) {
            addDb('user_city', $_SERVER["GEOIP_CITY"]);
        } else if (!empty($_SERVER["GEOIP_COUNTRY_NAME"])) {
            addDb('user_city', $_SERVER["GEOIP_COUNTRY_NAME"]);
        }
        // Get a return code if exists
        $returnCode = addDb($type, $data);
        if ($returnCode === '23000') {
            $type .= '?code=exists';
        } else {
            // Get last ID added
            $id = getLastId('user_id', $type)['id'];
            $type .= '?code=success';
            // Set a cookie to keep logged in
            setcookie('player_id', $id, strtotime('+360 days'), '/');
        }
    }
    // Redirect to the type page
    header('Refresh: 3; URL=/' . $type);
}
