<?php
// If user go to constructors.php, redirect to constructors list
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/constructors');
}
// Get an array of all constructors infos
$constructors = selectAllConstructors();

echo '<h2>Liste des ' . count($constructors) . ' constructeurs</h2>';

echo '<aside class="constructors">';
echo '<div class="constructor-list">';
// Loop all constructors
foreach ($constructors as $constructor) {
    echo '<a href="/constructor/' . $constructor['brand_id'] . '">';
    echo '<div class="constructor-item">';
    echo '<img src="/img/constructors/' . $constructor['brand_pic'] . '" alt="' . $constructor['brand_name'] . '">';
    echo '<p>' . $constructor['brand_name'] . '</p>';
    echo '</div>';
    echo '</a>';
}
echo '</div>';

include_once('add.php');
echo '</aside>';
