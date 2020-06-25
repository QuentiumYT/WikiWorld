<?php
// If user go to players.php, redirect to players list
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/players');
}
// Get an array of all players infos
$players = selectAllPlayers();

echo '<aside class="players">';
echo '<h2>Liste des ' . count($players) . ' joueurs</h2>';
// Loop all players
foreach ($players as $player) {
    echo '<div class="table">';
    echo '<a href="' . $ref . '/player/' . $player['user_id'] . '" class="t-item clic" style="width: 100%">Joueur :<br>' . $player['user_name'] . '</a>';
    echo '<p class="t-item" style="width: 100%">Id :<br>' . $player['user_id'] . '</p>';
    echo '<p class="t-item" style="width: 100%">Date enregistrée :<br>' . $player['user_date'] . '</p>';
    echo '</div>';
    echo '<br>';
}

include_once('add.php');
echo '</aside>';
