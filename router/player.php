<?php
// If user go to player.php, redirect to player list
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/players');
}
$playerDetails = selectPlayer($playerId);
// If player does not exist, 404 page
if (!$playerDetails) {
    include('404.php');
    exit();
}

// List of thead content
$theadContent = [
    'stars' => '<th><a href="?sort=stars&dir=ASC">Étoiles : %s</a></th>',
    'car_name' => '<th><a href="?sort=car_name&dir=ASC">Voiture : %s</a></th>',
    'car_id' => '<th><a href="?sort=car_id&dir=ASC">Id : %s</a></th>',
    'car_pic' => '<th><a href="?sort=car_name&dir=ASC">Image :</a></th>',
    'car_date_start' => '<th><a href="?sort=car_date_start&dir=ASC">Commercialisation : %s</a></th>',
    'car_date_end' => '<th><a href="?sort=car_date_end&dir=ASC">Fin de production : %s</a></th>'
];

// Display sorted column and direction
if (isset($_GET['sort']) && isset($_GET['dir'])) {
    foreach ($theadContent as $key => $value) {
        if ($_GET['sort'] == $key) {
            if ($_GET['dir'] == 'ASC') {
                $sort = 'ORDER BY ' . $key . ' ASC';
                $theadContent[$key] = str_replace(['%s', '&dir=ASC'], ['▲', '&dir=DESC'], $value);
            } else if ($_GET['dir'] == 'DESC') {
                $sort = 'ORDER BY ' . $key . ' DESC';
                $theadContent[$key] = str_replace(['%s', '&dir=DESC'], ['▼', '&dir=ASC'], $value);
            }
        }
    }
} else {
    // Default order
    $sort = "ORDER BY stars DESC";
    $theadContent['stars'] = '<th><a href="?sort=stars&dir=ASC">Étoiles : ▼</a></th>';
}
// Replace temp string variable
foreach ($theadContent as $key => $value) {
    $theadContent[$key] = str_replace(' %s', '', $value);
}

// Select favorites of a player
$playerFavorites = selectPlayerFavorites($playerId, $sort);

echo '<aside class="player">';
echo '<h2>Détails du joueur n°' . $playerId . '</h2>';

echo '<div class="table">';
echo '<a href="/player/' . $playerDetails['user_id'] . '" class="t-item clic" style="width: 100%">Joueur :<br>' . $playerDetails['user_name'] . '</a>';
echo '<p class="t-item" style="width: 100%">Date enregistrée :<br>' . $playerDetails['user_date'] . '</p>';
echo '<p class="t-item" style="width: 100%">Id :<br>' . $playerDetails['user_id'] . '</p>';
// If favorites is not 0 (division fail)
if (count($playerFavorites) !== 0) {
    $stars_avg = round(array_sum(array_column($playerFavorites, 'stars')) / count($playerFavorites), 3);
} else {
    $stars_avg = 0;
}
echo '<p class="t-item" style="width: 100%">Moyenne des étoiles :<br>' . $stars_avg . '</p>';
echo '</div>';
echo '<br>';

echo '<h2>Favoris (' . count($playerFavorites) . ')</h2>';

echo '<table><thead><tr>';
// Echo the header
foreach ($theadContent as $thead) {
    echo $thead;
}
echo '</tr></thead><tbody>';

// Loop all favorites
foreach ($playerFavorites as $fav) {
    echo '<tr>';
    echo '<td>' . $fav['stars'] . '<img class="star" src="/img/star.png"></td>';
    echo '<td>' . str_replace('+', ' ', $fav['car_name']) . '</td>';
    echo '<td>' . $fav['car_id'] . '</td>';
    // Get the src with small picture
    $pic_scr = str_replace(' ', '_', $fav['car_name']) . '_s/' . $fav['car_pic'];
    echo '<td><img src="/img/cars/' . $pic_scr . '"></td>';
    echo '<td>' . $fav['car_date_start'] . '</td>';
    echo '<td>' . $fav['car_date_end'] . '</td>';
    echo '</tr>';
}
echo '</tbody></table>';

backButton('/players', 'Revenir aux joueurs');
echo '</aside>';
