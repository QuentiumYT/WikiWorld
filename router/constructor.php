<?php
// If user go to constructor.php, redirect to constructors list
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/constructors');
}

$constructorDetails = selectConstructor($constructorId);
// If constructor does not exist, 404 page
if (!$constructorDetails) {
    include('404.php');
    exit();
}
// Get all constructor's cars
$carsConstructor = selectCarConstructor($constructorId);

echo '<aside class="players">';
echo '<h2>Détails du constructeur n°' . $constructorId . '</h2>';

echo '<div class="table">';
echo '<div class="table-content" style="width:150%">';
echo '<a href="/constructor/' . $constructorDetails['brand_id'] . '" class="t-item clic" style="width: 100%; height: 50%">Constructeur : ' . $constructorDetails['brand_name'] . '</a>';
echo '<p class="t-item" style="width: 80%; height: 50%">Id : ' . $constructorDetails['brand_id'] . '</p>';
echo '<div class="t-item clic col" style="width: 20%; height: 50%">';
echo '<a href="/edit/constructor/' . $constructorId . '"><i class="fal fa-edit"></i></a>';
echo '<a href="/delete/constructor/' . $constructorId . '"><i class="fal fa-trash-alt"></i></a>';
echo '</div>';
echo '</div>';
echo '<div class="table-image-full">';
echo '<img style="width: 220px" src="/img/constructors/' . $constructorDetails['brand_pic'] . '">';
echo '</div>';
echo '<div class="table-content">';
echo '<a href="https://nfsworld.fandom.com/wiki/' . $constructorDetails['brand_name'] . '" target="_BLANK"  class="t-item clic" style="width: 100%">Description : ' . $constructorDetails['brand_desc'] . '</a>';
echo '</div>';
echo '</div>';
echo '<br>';

echo '<h2>Voitures du constructeur (' . count($carsConstructor) . ')</h2>';

// Loop all cars of the constructor
foreach ($carsConstructor as $car) {
    echo '<div class="table">';
    echo '<div class="table-image">';
    // Replace spaces and plus by understandable signs (%2B is +)
    $pic_scr = str_replace([" ", "+"], ["_", "%2B"], $car['car_name']) . '/' . $car['car_pic'];
    echo '<p class="t-item" style="min-height: 260px"><img src="/include/resize.php?s=500&f=/img/cars/' . $pic_scr . '"></p>';
    echo '</div>';
    echo '<div class="table-content">';
    echo '<a href="/car/' . $car['car_id'] . '" class="t-item clic" style="width: 100%">Nom : ' . $car['car_name'] . '</a>';
    echo '<p class="t-item" style="width: 50%">Commercialisation : ' . $car['car_date_start'] . '</p>';
    echo '<p class="t-item" style="width: 50%">Fin de production : ' . $car['car_date_end'] . '</p>';
    echo '<p class="t-item" style="width: 50%">Moteur : ' . $car['car_motor'] . '</p>';
    echo '<p class="t-item" style="width: 50%">Vitesses : ' . $car['car_transmission'] . '</p>';
    echo '</div>';
    echo '</div>';
    echo '<br>';
}

backButton('/constructors', 'Revenir aux constructeurs');
echo '</aside>';
