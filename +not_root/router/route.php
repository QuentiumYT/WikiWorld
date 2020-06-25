<?php
// Get the URI of the requested page
function getCurrentUri()
{
    $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
    $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
    if (strstr($uri, '?')) {
        $uri = substr($uri, 0, strpos($uri, '?'));
    }
    $uri = '/' . trim($uri, '/');
    return $uri;
}

$base_url = getCurrentUri();
$url = [];
// Expload all routes
$routes = explode('/', $base_url);
foreach ($routes as $route) {
    if (trim($route) != '') {
        // Push the route to url array
        array_push($url, $route);
    }
}
