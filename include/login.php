<?php
// Check if server is local (computer or network hosted)
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '0.0.0.0') {
    include_once('config_local.php');
} else {
    // Get public database config
    include_once('config.php');
}

// Connect to the database
function connect()
{
    global $config;

    // Error handling to not show everything (password included)
    try {
        $pdo = new PDO(
            $config['driver'] . ':host=' . $config['server'] . ';dbname=' . $config['database'] . ';charset=utf8',
            $config['user'],
            $config['pwd']
        );
        $pdo->exec('SET NAMES UTF8');
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }

    // Return the global pdo var
    return $pdo;
}
