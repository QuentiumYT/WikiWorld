<?php
// If user go to cars.php, redirect to cars list
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/cars');
}
// Get an array of all cars infos
$cars = selectAllCars();

echo '<h2>Liste des ' . count($cars) . ' voitures</h2>';

echo '<aside class="cars">';
echo '<div class="car-list">';
// Loop all cars
foreach ($cars as $car) {
    echo '<a href="' . $ref . '/car/' . $car['car_id'] . '">';
    echo '<div class="car-item">';
    // Choose the right picture size (not using resize.php for optimisation)
    $pic_scr = str_replace(' ', '_', $car['car_name']) . '_s/' . $car['car_pic'];
    echo '<img src="' . $ref . '/img/cars/' . $pic_scr . '" alt="' . $car['car_name'] . '">';
    // Remove + sign if car is added
    echo '<p>' . str_replace('+', ' ', $car['car_name']) . '</p>';
    echo '</div>';
    echo '</a>';
}
echo '</div>';

include_once('add.php');
echo '</aside>';
