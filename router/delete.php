<?php
// If user go to delete.php, redirect to main page
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/');
}

// removeType is the page selected before edit was clicked
if ($removeType === 'car') {
    // Get the selected car
    $removeCar = selectCar($removeId);

    echo '<div class="form-action">';
    // If car ID doesn't exists
    if (!$removeCar) {
        echo '<h3 style="color: #202020; padding: 30px">Erreur: L\'ID de cette voiture n\'est pas correct !</h3>';
        echo '</div>';
    } else {
        echo '<h3>Suppression de la voiture ' . $removeId . '</h3>';
        echo '<form action="/include/delete_db.php" method="POST" enctype="multipart/form-data">';
        echo '<input type="text" name="type" value="cars" hidden>';
        echo '<input value="' . $removeId . '" name="car_id" hidden>';
        echo '<label for="car_name">Nom de la voiture</label>';
        echo '<input value="' . $removeCar['car_name'] . '" name="car_name" readonly onclick="return false"><br>';
        echo '<label for="password">Mot de passe</label>';
        echo '<input type="password" name="passwd" required><br>';
        // Return a error code if password is wrong
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            if ($code === 'unauthorized') {
                echo '<h3 style="color: #202020">Erreur: Le mot de passe n\'est pas correct !</h3>';
            }
        }
        echo '<input type="submit" value="Supprimer">';
        echo '</form>';
        echo '</div>';
        backButton('/' . $removeType . '/' . $removeId, 'Revenir à la voiture <br>"' . $removeCar['car_name'] . '"');
    }
} else if ($removeType === 'constructor') {
    // Get the selected constructor
    $removeConstructor = selectConstructor($removeId);

    echo '<div class="form-action">';
    // If constructor ID doesn't exists
    if (!$removeConstructor) {
        echo '<h3 style="color: #202020; padding: 30px">Erreur: L\'ID de ce constructeur n\'est pas correct !</h3>';
        echo '</div>';
    } else {
        echo '<h3>Suppression du contructeur ' . $removeId . '</h3>';
        echo '<form action="/include/delete_db.php" method="POST" enctype="multipart/form-data">';
        echo '<input type="text" name="type" value="constructors" hidden>';
        echo '<input value="' . $removeId . '" name="brand_id" hidden>';
        echo '<label for="brand_name">Nom du contructeur</label>';
        echo '<input value="' . $removeConstructor['brand_name'] . '" style="background-color: #aaaaaaaa" name="brand_name" readonly onclick="return false"><br>';
        echo '<label for="password">Mot de passe</label>';
        echo '<input type="password" name="passwd" required><br>';
        // Return a error code if password is wrong
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            if ($code === 'unauthorized') {
                echo '<h3 style="color: #202020">Erreur: Le mot de passe n\'est pas correct !</h3>';
            }
        }
        echo '<input type="submit" value="Supprimer">';
        echo '</form>';
        echo '</div>';
        backButton('/' . $removeType . '/' . $removeId, 'Revenir au contructeur <br>"' . $removeConstructor['brand_name'] . '"');
    }
} else if ($removeType === 'favorite') {
    // Get the selected favorite
    $removeFav = selectFavorite($removeId, $removeId2);

    echo '<div class="form-action">';
    // Cookie is set and the cookie is exactly the player id (else cancel)
    if (isset($_COOKIE['player_id']) && $removeId == $_COOKIE['player_id']) {
        // If favorite IDs doesn't exists
        if (!$removeFav) {
            echo '<h3 style="color: #202020; padding: 30px">Erreur: Cette combinaison ID / Voiture n\'est pas correcte !</h3>';
            echo '</div>';
        } else {
            echo '<h3>Suppression du favoris ' . $removeId2 . ' du joueur ' . $removeId . '</h3>';
            echo '<form action="/include/delete_db.php" method="POST" enctype="multipart/form-data">';
            echo '<input type="text" name="type" value="favorites" hidden>';
            echo '<input value="' . $removeId . '" name="user_id" hidden>';
            echo '<input value="' . $removeId2 . '" name="car_id" hidden>';
            echo '<label for="stars">Nombre d\'étoiles</label>';
            // Delete the number of stars
            echo '<input value="' . $removeFav['stars'] . '" style="background-color: #aaaaaaaa" name="stars" readonly onclick="return false"><br>';
            echo '<input type="submit" value="Supprimer">';
            echo '</form>';
            echo '</div>';
            backButton('/favorites', 'Revenir aux favoris');
        }
    } else {
        echo '<h3 style="color: #202020; padding: 30px">Erreur: Vous n\'êtes pas connecté en tant que cet utilisateur, vous ne pouvez donc pas supprimer ce favoris !</h3>';
        echo '</div>';
    }
}
