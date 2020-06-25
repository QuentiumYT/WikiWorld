<?php
// If user go to edit.php, redirect to main page
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/');
}

if ($editType === 'car') {
    // Get the selected car
    $editCar = selectCar($editId);
    // Get all constructors
    $constructors = selectAllConstructors();

    echo '<div class="form-action">';
    // If car ID doesn't exists
    if (!$editCar) {
        echo '<h3 style="color: #202020; padding: 30px">Erreur: L\'ID de cette voiture n\'est pas correct !</h3>';
        echo '</div>';
        backButton($ref . '/cars', 'Revenir aux voitures');
    } else {
        echo '<h3>Edition de la voiture ' . $editId . '</h3>';
        echo '<form action="' . $ref . '/include/edit_db.php" method="POST" enctype="multipart/form-data">';
        // Loop all keys for specific inputs
        foreach ($editCar as $key => $value) {
            if ($key !== 'car_pic') {
                // Hidden input
                if ($key === 'car_id') {
                    echo '<input value="' . $value . '" name="' . $key . '" hidden>';
                    continue;
                }
                echo '<label for="' . $key . '">' . $key . '</label>';
                // Textarea
                if ($key === 'car_desc') {
                    echo '<textarea rows="6" cols="50" name="' . $key . '" style="vertical-align: middle" required>' . $value . '</textarea><br>';
                    // Select options
                } else if ($key === 'car_brand_id') {
                    echo '<select name="' . $key . '" value="null" required>';
                    foreach ($constructors as $constructor) {
                        // Get the current constructor and set in selected
                        if ($editCar['car_brand_id'] === $constructor['brand_id']) {
                            echo '<option value="' . $constructor['brand_id'] . '" selected>' . $constructor['brand_name'] . '</option>';
                        } else {
                            echo '<option value="' . $constructor['brand_id'] . '">' . $constructor['brand_name'] . '</option>';
                        }
                    }
                    echo '</select><br>';
                    // Type date between 1900 and 2020
                } else if (strpos($key, 'date')) {
                    echo '<input type="number" min="1900" max="2020" step="1" value="' . $value . '" name="' . $key . '" required><br>';
                } else {
                    // Classic input
                    echo '<input type="text" value="' . $value . '" name="' . $key . '" required><br>';
                }
            }
        }

        echo '<label for="password">Mot de passe</label>';
        echo '<input type="password" name="passwd" required>';
        echo '<input type="text" name="type" value="cars" hidden>';
        echo '<input type="text" name="base_name" value="' . $editCar['car_name'] . '" hidden>';
        // Return a error code if password is wrong
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            if ($code === 'unauthorized') {
                echo '<h3 style="color: #202020">Erreur: Le mot de passe n\'est pas correct !</h3>';
            }
        }
        echo '<input type="submit" value="Modifier">';
        echo '</form>';
        echo '</div>';
        backButton($ref . '/' . $editType . '/' . $editId, 'Revenir à la voiture <br>"' . $editCar['car_name'] . '"');
    }
} else if ($editType === 'constructor') {
    $editConstructor = selectConstructor($editId);

    echo '<div class="form-action">';
    if (!$editConstructor) {
        echo '<h3 style="color: #202020; padding: 30px">Erreur: L\'ID de ce constructeur n\'est pas correct !</h3>';
        echo '</div>';
        backButton($ref . '/constructors', 'Revenir aux constructeurs');
    } else {
        echo '<h3>Edition du constructeur ' . $editId . '</h3>';
        echo '<form action="' . $ref . '/include/edit_db.php" method="POST" enctype="multipart/form-data">';
        // Loop all keys for specific inputs
        foreach ($editConstructor as $key => $value) {
            if ($key !== 'brand_pic') {
                // Hidden input
                if ($key === 'brand_id') {
                    echo '<input value="' . $value . '" name="' . $key . '" hidden>';
                    continue;
                }
                echo '<label for="' . $key . '">' . $key . '</label>';
                // Textarea
                if ($key === 'brand_desc') {
                    echo '<textarea rows="6" cols="50" name="' . $key . '" style="vertical-align: middle" required>' . $value . '</textarea><br>';
                } else {
                    // Classic input
                    echo '<input type="text" value="' . $value . '" name="' . $key . '" required><br>';
                }
            }
        }
        echo '<label for="password">Mot de passe</label>';
        echo '<input type="password" name="passwd" required>';
        echo '<input type="text" name="type" value="constructors" hidden>';
        echo '<input type="text" name="base_name" value="' . $editConstructor['brand_name'] . '" hidden>';
        // Return a error code if password is wrong
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            if ($code === 'unauthorized') {
                echo '<h3 style="color: #202020">Erreur: Le mot de passe n\'est pas correct !</h3>';
            }
        }
        echo '<input type="submit" value="Modifier">';
        echo '</form>';
        echo '</div>';
        backButton($ref . '/' . $editType . '/' . $editId, 'Revenir au contructeur <br>"' . $editConstructor['brand_name'] . '"');
    }
} else if ($editType === 'favorite') {
    $editFav = selectFavorite($editId, $editId2);

    echo '<div class="form-action">';
    // Cookie is set and the cookie is exactly the player id (else cancel)
    if (isset($_COOKIE['player_id']) && $editId == $_COOKIE['player_id']) {
        if (!$editFav) {
            echo '<h3 style="color: #202020; padding: 30px">Erreur: Cette combinaison ID / Voiture n\'est pas correcte !</h3>';
        } else {
            echo '<h3>Edition du favoris ' . $editId2 . ' du joueur ' . $editId . '</h3>';
            echo '<form action="' . $ref . '/include/edit_db.php" method="POST" enctype="multipart/form-data">';
            echo '<input value="' . $editId . '" name="user_id" hidden>';
            echo '<input value="' . $editId2 . '" name="car_id" hidden>';
            echo '<label for="stars">Nouveau nombre d\'étoiles</label>';
            // Only change the number of stars
            echo '<input type="range" name="stars" min="1" max="5" value="3" style="width: 14%; vertical-align: middle" onchange="refresh(this.value)">';
            echo '<p class="t-item" style="width: 20%; margin: auto">Étoiles :&nbsp;<span id="starsCount">3</span><img class="star" src="' . $ref . '/img/star.png"></p>';
            echo '<input type="text" name="type" value="favorites" hidden>';
            echo '<input type="submit" value="Modifier">';
            echo '</form>';
            // Include some JavaScript (why not?)
            echo '<script src="' . $ref . '/js/range.js"></script>';
        }
        echo '</div>';
        backButton($ref . '/favorites', 'Revenir aux favoris');
    } else {
        echo '<h3 style="color: #202020; padding: 30px">Erreur: Vous n\'êtes pas connecté en tant que cet utilisateur, vous ne pouvez donc pas supprimer ce favoris !</h3>';
        echo '</div>';
    }
}
