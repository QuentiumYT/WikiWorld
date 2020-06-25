<?php
// If user go to add.php, redirect to home
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/');
}

if (isset($cars)) {
    // Get a car details to help filling the form
    $randomCar = selectCar(20);
    // Get a list of all constructors for the select
    $constructors = selectAllConstructors();

    echo '<div class="form-action">';
    echo '<h3>Ajout d\'une voiture</h3>';
    echo '<form action="/include/add_db.php" method="POST" enctype="multipart/form-data">';
    // Loop car data
    foreach ($randomCar as $key => $value) {
        if ($key !== 'car_id') {
            echo '<label for="' . $key . '">' . $key . '</label>';
            // Chage to textarea
            if ($key === 'car_desc') {
                echo '<textarea rows="6" cols="50" placeholder="' . $value . '" name="' . $key . '" style="vertical-align: middle" required></textarea><br>';
                // Change to constructor's name
            } else if ($key === 'car_brand_id') {
                echo '<select name="' . $key . '" value="null" required>';
                echo '<option hidden disabled selected value>-- Choisissez un constructeur --</option>';
                foreach ($constructors as $constructor) {
                    echo '<option value="' . $constructor['brand_id'] . '">' . $constructor['brand_name'] . '</option>';
                }
                echo '</select><br>';
                // Number of transmission speed
            } else if ($key === 'car_transmission') {
                echo '<input type="number" min="0" max="10" step="1" name="' . $key . '" required><br>';
                // Dates between 1900 and 2020
            } else if (strpos($key, 'date')) {
                echo '<input type="number" min="1900" max="2020" step="1" value="2000" name="' . $key . '" required><br>';
                // File type
            } else if ($key === 'car_pic') {
                echo '<input name="MAX_FILES_SIZE" value="10000000" hidden>';
                echo '<input type="file" name="image" required><br>';
                // Basic text input
            } else {
                echo '<input type="text" placeholder="' . $value . '" name="' . $key . '" required><br>';
            }
        }
    }
    // Know the type
    echo '<input type="text" name="type" value="cars" hidden><br>';
    echo '<input type="submit" value="Ajouter">';
    echo '</form>';
    echo '</div>';
} else if (isset($constructors)) {
    $randomConstructor = selectConstructor(5);

    echo '<div class="form-action">';
    echo '<h3>Ajout d\'un constructeur</h3>';
    echo '<form action="/include/add_db.php" method="POST" enctype="multipart/form-data">';
    // Loop constructor data
    foreach ($randomConstructor as $key => $value) {
        if ($key !== 'brand_id') {
            echo '<label for="' . $key . '">' . $key . '</label>';
            // Change to textarea
            if ($key === 'brand_desc') {
                echo '<textarea rows="6" cols="50" placeholder="' . $value . '" name="' . $key . '" style="vertical-align: middle" required></textarea><br>';
                // File type
            } else if ($key === 'brand_pic') {
                echo '<input name="MAX_FILES_SIZE" value="1000000" hidden>';
                echo '<input type="file" name="image" required><br>';
            } else {
                // Basic text input
                echo '<input type="text" placeholder="' . $value . '" name="' . $key . '" required><br>';
            }
        }
    }
    // Know the type
    echo '<input type="text" name="type" value="constructors" hidden><br>';
    echo '<input type="submit" value="Ajouter">';
    echo '</form>';
    echo '</div>';
} else if (isset($players)) {
    echo '<div class="form-action">';
    echo '<h3>Création d\'un compte joueur</h3>';
    echo '<form action="/include/add_db.php" method="POST">';
    echo '<label for="user_name">Pseudo joueur</label>';
    // Just enter a username
    echo '<input type="text" placeholder="Pseudo" name="user_name" required><br>';
    echo '<input type="text" name="type" value="players" hidden><br>';
    // Display a message if code
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        if ($code === 'exists') {
            echo '<h3 style="color: #202020">Erreur: L\'utilisateur entré existe déjà !</h3>';
        } else if ($code === 'success') {
            echo '<h3 style="color: #202020">Succès: L\'utilisateur à bien été créé, vous pouvez désormais voter une voiture sur la page de celle ci !</h3>';
        }
    }
    echo '<input type="submit" value="Créer">';
    echo '</form>';
    echo '</div>';
}
