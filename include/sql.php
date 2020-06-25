<?php
// If user go to sql.php, redirect to home
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    header('Refresh: 0; URL=/');
}
// login and user pdo connection
include_once('login.php');

$pdo = connect();

// Simple display function to select all cars
function selectAllCars()
{
    global $pdo;
    $query = $pdo->prepare('SELECT car_id, car_name, car_pic FROM cars ORDER BY car_brand_id, car_name');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Simple display function to select a car details
function selectCar($carId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM cars WHERE car_id = :id');
    $query->bindValue(':id', $carId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Simple display function to select all editions from a car
function selectCarEdition($carId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM editions WHERE car_id = :id ORDER BY style_overall');
    $query->bindValue(':id', $carId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Simple display function to select all constructors
function selectAllConstructors()
{
    global $pdo;
    $query = $pdo->prepare('SELECT brand_id, brand_name, brand_pic FROM constructors ORDER BY brand_name');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Simple display function to select a constructor details
function selectConstructor($constructorId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM constructors WHERE brand_id = :id');
    $query->bindValue(':id', $constructorId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Simple display function to select the contructor of a car
function selectCarConstructor($constructorId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM cars WHERE car_brand_id = :id ORDER BY car_date_end DESC');
    $query->bindValue(':id', $constructorId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

//  Select all favorites or only with a certain number of stars
function selectAllFavorites($countStar = 'all')
{
    global $pdo;
    if ($countStar === 'all') {
        $query = $pdo->prepare('SELECT * FROM favorites NATURAL JOIN players WHERE stars >= 1 AND stars <= 5 ORDER BY user_id');
    } else {
        $query = $pdo->prepare('SELECT * FROM favorites NATURAL JOIN players WHERE stars = :count ORDER BY user_id');
        $query->bindValue(':count', $countStar, PDO::PARAM_INT);
    }
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Simple display function to select stars from a user and a car
function selectFavorite($userId, $carId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT stars FROM favorites WHERE user_id = :id AND car_id = :id2');
    $query->bindValue(':id', $userId, PDO::PARAM_INT);
    $query->bindValue(':id2', $carId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Select favorites from a player with a sort direction
function selectPlayerFavorites($userId, $sort = '')
{
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM favorites NATURAL JOIN cars WHERE stars >= 1 AND stars <= 5 AND user_id = :id ' . $sort);
    $query->bindValue(':id', $userId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Show a small recap of the count of stars and the stars
function selectFavoritesRecap()
{
    global $pdo;
    $query = $pdo->prepare('SELECT COUNT(car_id) as "nb_cars", stars FROM favorites GROUP BY stars HAVING stars >= 1 AND stars <= 5 ORDER BY stars DESC');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Show an average of stars of the most voted cars
function selectFavoritesBest()
{
    global $pdo;
    $query = $pdo->prepare('SELECT AVG(stars) AS "stars_avg", car_id FROM favorites GROUP BY car_id HAVING COUNT(car_id) > 1');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Simple display function to select all players
function selectAllPlayers()
{
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM players ORDER BY user_date');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Simple display function to select a player details
function selectPlayer($userId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM players WHERE user_id = :id');
    $query->bindValue(':id', $userId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Add an array of data in the db with secure strings
function addDb($table, $data)
{
    global $pdo;
    $columns = implode(', ', array_keys($data));
    $escape_data = array_map('addslashes', array_values($data));
    $escape_data = array_map('strip_tags', array_values($escape_data));
    $escape_data = array_map('htmlspecialchars', array_values($escape_data));
    $values  = implode('", "', $escape_data);
    $query = $pdo->prepare('INSERT INTO ' . $table . ' (' . $columns . ') VALUES ("' . $values . '")');
    // echo 'insert into ' . $table . ' (' . $columns . ') VALUES ("' . $values . '")';
    $query->execute();
    return $query->errorCode();
}

// Edit a specific value in the db with an ID (need a loop)
function editDb($table, $key, $value, $column, $id)
{
    global $pdo;
    $value = addslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    $query = $pdo->prepare('UPDATE ' . $table . ' SET ' . $key . ' = :value WHERE ' . $column . ' = :id');
    // echo 'update ' . $table . ' set ' . $key . '=' . $value . ' where ' . $column . '=' . $id;
    $query->bindValue(':value', $value, PDO::PARAM_STR);
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->execute();
}

// Edit the number of stars in the db with the player and the car ID
function editStarsDb($table, $key, $value, $column, $id, $column2, $id2)
{
    global $pdo;
    $value = intval($value);
    $query = $pdo->prepare('UPDATE ' . $table . ' SET ' . $key . ' = :value WHERE ' . $column . ' = :id AND ' . $column2 . ' = :id2');
    // echo 'update ' . $table . ' set ' . $key . '=' . $value . ' where ' . $column . '=' . $id . ' AND ' . $column2 . '=' . $id2;
    $query->bindValue(':value', $value, PDO::PARAM_STR);
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->bindValue(':id2', $id2, PDO::PARAM_STR);
    $query->execute();
}

// Delete a specific value in the db with an ID
function deleteDb($table, $column, $id)
{
    global $pdo;
    $query = $pdo->prepare('DELETE FROM ' . $table . ' WHERE ' . $column . ' = :value');
    // echo 'delete from ' . $table . ' where ' . $column . '=' . $id;
    $query->bindValue(':value', $id, PDO::PARAM_INT);
    $query->execute();
}

// Remove the favorite from the db with it's two IDs
function deleteStarsDb($table, $column, $id, $column2, $id2)
{
    global $pdo;
    $query = $pdo->prepare('DELETE FROM ' . $table . ' WHERE ' . $column . ' = :id AND ' . $column2 . ' = :id2');
    // echo 'delete from ' . $table . ' where ' . $column . '=' . $id . ' AND ' . $column2 . '=' . $id2;
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->bindValue(':id2', $id2, PDO::PARAM_STR);
    $query->execute();
}

// Get last ID from a table (used when a player is created)
function getLastId($column, $table)
{
    global $pdo;
    $query = $pdo->prepare('SELECT MAX(' . $column . ') AS "id" FROM ' . $table);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}
