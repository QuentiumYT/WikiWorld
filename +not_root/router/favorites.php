<?php
// If user go to favorites.php, redirect to favorites page
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/favorites');
}

// If star_count in GET url, use this to display favorites (else all)
if (isset($_GET['star_count'])) {
    $starsNumber = intval($_GET['star_count']);
    if ($starsNumber === 'all') {
        $favorites = selectAllFavorites();
    }
    if (is_numeric($starsNumber) && ($starsNumber >= 1) && ($starsNumber <= 5)) {
        $favorites = selectAllFavorites($starsNumber);
        // Failed to check if number or between 1 and 5
    } else {
        $favorites = selectAllFavorites();
    }
} else {
    $favorites = selectAllFavorites();
}
// Some table with statistics
$favoritesRecap = selectFavoritesRecap();
$favoritesBest = selectFavoritesBest();

echo '<aside class="favorites">';
echo '<h2>Récapitulatif des favoris</h2>';
echo '<div class="table">';
echo '<table><thead><tr>';
echo '<th>Étoiles :</th>';
echo '<th>Nombre</th>';
echo '</tr></thead><tbody>';
// Loop the table
foreach ($favoritesRecap as $recap) {
    echo '<tr>';
    echo '<td>' . $recap['stars'] . ' <img class="star" src="' . $ref . '/img/star.png"></td>';
    echo '<td>' . $recap['nb_cars'] . '</td>';
    echo '</tr>';
}
echo '</tbody></table>';
echo '</div>';

echo '<h2>Moyenne des étoiles des voitures les plus notées</h2>';
echo '<div class="table">';
echo '<table><thead><tr>';
echo '<th>Voiture :</th>';
echo '<th>Moyenne des étoiles</th>';
echo '</tr></thead><tbody>';
// Loop the table
foreach ($favoritesBest as $best) {
    $carName = selectCar($best['car_id']);
    echo '<tr>';
    echo '<td>' . str_replace('+', '', $carName['car_name']) . '</td>';
    echo '<td>' . $best['stars_avg'] . ' <img class="star" src="' . $ref . '/img/star.png"></td>';
    echo '</tr>';
}
echo '</tbody></table>';
echo '</div>';

echo '<h2>Liste des ' . count($favorites) . ' favoris</h2>';

echo '<div class="favorites-list">';
echo '<form method="GET">';
echo '<label for="stars">Trier par étoiles : </label>';
echo '<select name="star_count" onchange="this.form.submit()">';
echo '<option value="all">Toutes</option>';
// Select a sorting method using a select option
for ($i = 1; $i <= 5; $i++) {
    if (isset($_GET['star_count']) && $_GET['star_count'] == $i) {
        echo '<option selected value="' . $i . '">' . $i . '</option>';
    } else {
        echo '<option value="' . $i . '">' . $i . '</option>';
    }
}
echo '</select>';
echo '</form>';
foreach ($favorites as $fav) {
    // Get a car details using it's ID
    $carDetails = selectCar($fav['car_id']);

    echo '<div class="table">';
    echo '<div class="table-content" style="width: 150%">';
    echo '<a href="' . $ref . '/player/' . $fav['user_id'] . '" class="t-item clic" style="width: 100%; height: 33.333333%">Joueur :<br>' . $fav['user_name'] . '</a>';
    echo '<a href="' . $ref . '/car/' . $carDetails['car_id'] . '" class="t-item clic" style="width: 100%; height: 33.333333%">Voiture notée :<br>' . str_replace('+', ' ', $carDetails['car_name']) . '</a>';
    echo '<p class="t-item" style="width: 84%; height: 33.333333%">Id :<br>' . $fav['user_id'] . '</p>';
    echo '<div class="t-item clic col" style="width: 16%; height: 33.333333%">';
    echo '<a href="' . $ref . '/edit/favorite/' . $fav['user_id'] . '/' . $carDetails['car_id'] . '"><i class="fal fa-edit"></i></a>';
    echo '<a href="' . $ref . '/delete/favorite/' . $fav['user_id'] . '/' . $carDetails['car_id'] . '"><i class="fal fa-trash-alt"></i></a>';
    echo '</div>';
    echo '</div>';
    // Replace spaces and plus by understandable signs (%selectFavoritesRecap is +)
    $pic_scr = str_replace([' ', '+'], ['_', '%2B'], $carDetails['car_name']) . '/' . $carDetails['car_pic'];
    echo '<p class="t-item"><img src="' . $ref . '/include/resize.php?s=420&f=/img/cars/' . $pic_scr . '"></p>';
    echo '<p class="t-item col stars"">';
    echo '<span>' . $fav['stars'] . '</span>';
    // Display the number of stars, else 
    for ($i = 5; $i > 0; $i--) {
        if ($i > $fav['stars']) {
            echo '<img class="star" src="' . $ref . '/img/star_disabled.png">';
        } else {
            echo '<img class="star" src="' . $ref . '/img/star.png">';
        }
    }
    echo '</p>';
    echo '</div>';
    echo '<br>';
}
echo '</div>';
echo '</aside>';
