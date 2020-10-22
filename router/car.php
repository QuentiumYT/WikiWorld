<?php
// If user go to car.php, redirect to cars list
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/cars');
}

$carDetails = selectCar($carId);
// If car does not exist, 404 page
if (!$carDetails) {
    include('404.php');
    exit();
}

// Set the cookie if a user is selected below and refresh to apply
if (isset($_POST['player_select'])) {
    setcookie('player_id', $_POST['player_select'], strtotime('+360 days'), '/');
    unset($_POST);
    header('Refresh: 0');
    exit();
}

// A car has been voted, add DB
if (isset($_POST['stars'])) {
    // Get all data at different places
    $data = [
        'user_id' => $_COOKIE['player_id'],
        'car_id' => $carId,
        'stars' => $_POST['stars']
    ];
    // Remove post if submit again
    unset($_POST);
    $returnCode = addDb('favorites', $data);
    if ($returnCode === '23000') {
        // Redirect to the current page but without attributes if there's already some
        $loc = $_SERVER['HTTP_ORIGIN'] . $_SERVER['REDIRECT_URL'] . '?code=exists';
    } else {
        $loc = $_SERVER['HTTP_REFERER'];
    }
    header('Location: ' . $loc);
    exit();
}

// Select editions of a car
$carEditions = selectCarEdition($carId);
$carConstructor = selectConstructor($carDetails['car_brand_id']);
$players = selectAllPlayers();

echo '<aside class="players">';
echo '<h2>Détails de la voiture  n°' . $carId . '</h2>';

echo '<div class="table">';
echo '<div class="table-content">';
echo '<a href="/car/' . $carDetails['car_id'] . '" class="t-item clic" style="width: 50%">Voiture : ' . $carDetails['car_name'] . '</a>';
echo '<a href="/constructor/' . $carDetails['car_brand_id'] . '" class="t-item clic" style="width: 30%">Constructeur : ' . $carConstructor['brand_name'] . '</a>';
echo '<p class="t-item" style="width: 13%">Id : ' . $carDetails['car_id'] . '</p>';
echo '<div class="t-item clic col" style="width: 7%">';
echo '<a href="/edit/car/' . $carId . '"><i class="fal fa-edit"></i></a>';
echo '<a href="/delete/car/' . $carId . '"><i class="fal fa-trash-alt"></i></a>';
echo '</div>';
// If car does not exist in-game
if (strpos($carDetails['car_name'], '+') === 0) {
    $carName = str_replace('+', ' ', $carDetails['car_name']);
    // Redirect to wikipedia infos
    echo '<a href="https://fr.wikipedia.org/wiki/' . $carName . '" target="_BLANK" class="t-item clic" style="width: 40%">Description : ' . $carDetails['car_desc'] . '</a>';
} else {
    // Redirect to fandom infos
    echo '<a href="https://nfsworld.fandom.com/wiki/' . $carDetails['car_name'] . '" target="_BLANK" class="t-item clic" style="width: 40%">Description : ' . $carDetails['car_desc'] . '</a>';
}
// Replace spaces and plus by understandable signs (%2B is +)
$pic_scr = str_replace([' ', '+'], ['_', '%2B'], $carDetails['car_name']) . '/' . $carDetails['car_pic'];
echo '<p class="t-item" style="width: 60%"><img src="/include/resize.php?s=620&f=/img/cars/' . $pic_scr . '"></p>';
echo '<p class="t-item" style="width: 50%">Commercialisation : ' . $carDetails['car_date_start'] . '</p>';
echo '<p class="t-item" style="width: 50%">Fin de production : ' . $carDetails['car_date_end'] . '</p>';
echo '<p class="t-item" style="width: 33.333333%">Moteur : ' . $carDetails['car_motor'] . '</p>';
echo '<p class="t-item" style="width: 33.333333%">Vitesses : ' . $carDetails['car_transmission'] . '</p>';
echo '<p class="t-item" style="width: 33.333333%">Transmission : ' . $carDetails['car_drivetrain'] . '</p>';
echo '</div>';
echo '</div>';
echo '<br>';

// If the player has been registered
if (isset($_COOKIE['player_id'])) {
    echo '<div class="form-select">';
    echo '<form method="POST">';
    echo '<label class="t-item" style="width: 40%" for="stars">Séléctionnez le nombre d\'étoiles pour ajouter cette voiture aux favoris :</label>';
    echo '<input type="range" name="stars" min="1" max="5" value="5" style="width: 14%" onchange="refresh(this.value)">';
    echo '<p class="t-item">Étoiles :&nbsp;<span id="starsCount">5</span><img class="star" src="/img/star.png"></p>';
    echo '<input class="t-item clic" type="submit" value="Valider">';
    echo '</form>';
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        if ($code === 'exists') {
            echo '<h3 style="color: #202020; text-align: center">Erreur: Le favoris existe déjà !</h3>';
        }
    }
    echo '</div>';
    echo '<script src="/js/range.js"></script>';
    // Else submit form to select an existing player
} else {
    echo '<div class="form-select">';
    echo '<form method="POST">';
    echo '<label class="t-item" style="width: 40%" for="player">Séléctionnez un joueur pour ajouter cette voiture aux favoris :</label>';
    echo '<select class="t-item" name="player_select" onchange="this.form.submit()">';
    echo '<option hidden disabled selected value>-- Choisissez un joueur --</option>';
    // Loop every existing players
    foreach ($players as $player) {
        echo '<option value="' . $player['user_id'] . '">' . $player['user_name'] . '</option>';
    }
    echo '</select>';
    echo '</form>';
    echo '</div>';
}
echo '<br>';

echo '<h2>Éditions (' . count($carEditions) . ')</h2>';

// Loop all editions
foreach ($carEditions as $edition) {
    echo '<div class="table">';
    echo '<div class="table-image">';
    $pic_scr = str_replace(' ', '_', $carDetails['car_name']) . '/' . $edition['style_pic'];
    echo '<p class="t-item" style="height: 100%"><img src="/include/resize.php?s=500&f=/img/cars/' . $pic_scr . '"></p>';
    echo '</div>';
    echo '<div class="table-content">';
    echo '<p class="t-item" style="width: 50%">Édition : ' . $edition['style_name'] . '</p>';
    echo '<p class="t-item" style="width: 25%">Classe : ' . $edition['style_class'] . '</p>';
    echo '<p class="t-item" style="width: 25%">Overall : ' . $edition['style_overall'] . '</p>';
    echo '<p class="t-item" style="width: 33.333333%">Vitesse max : ' . $edition['style_topspeed'] . '</p>';
    echo '<p class="t-item" style="width: 33.333333%">Acceleration : ' . $edition['style_acceleration'] . '</p>';
    echo '<p class="t-item" style="width: 33.333333%">Maniement : ' . $edition['style_handling'] . '</p>';
    // Check if price is not defined
    if (empty($edition['style_price'])) {
        $edition['style_price'] = "/";
    } else {
        $edition['style_price'] .= '<img class="money" src="/img/IconCurrency_IGC.png">';
    }
    // Check if sb price is not defined
    if (empty($edition['style_price_sb'])) {
        $edition['style_price_sb'] = "/";
    } else {
        $edition['style_price_sb'] .= '<img class="money" src="/img/IconCurrency_SpeedBoost.png">';
    }
    echo '<p class="t-item" style="width: 50%">Prix Argent : ' . $edition['style_price'] . '</p>';
    echo '<p class="t-item" style="width: 50%">Prix SpeedBoost : ' . $edition['style_price_sb'] . '</p>';
    echo '</div>';
    echo '</div>';
    echo '<br>';
}

backButton('/cars', 'Revenir aux voitures');
echo '</aside>';
