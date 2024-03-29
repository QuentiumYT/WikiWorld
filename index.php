<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/img/logo.png">

    <title>Need For Speed - WikiWorld</title>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://assets.quentium.fr/FA/pro.min.css">
</head>

<body>
    <?php
    // All include (always same page)
    include('router/route.php');
    include('include/display.php');
    include('include/sql.php');

    // List of menu elements
    $menuList = ['Accueil', 'Voitures', 'Constructeurs', 'Favoris', 'Joueurs'];
    $menuLink = ['/', '/cars', '/constructors', '/favorites', '/players'];
    $menuIcon = ['home-lg-alt', 'car', 'industry-alt', 'heart', 'user'];

    echo '<nav class="main-menu"><ul>';
    // Loop menu elements
    for ($i = 0; $i < count($menuList); $i++) {
        echo '<a href="' . $menuLink[$i] . '">';
        echo '<div class="menu-item">';
        echo '<i class="fal fa-' . $menuIcon[$i] . '"></i>';
        echo '<li>' . $menuList[$i] . '</li>';
        echo '</div>';
        echo '</a>';
    }
    echo '</ul></nav>';

    ?>
    <div class="wrapper">
        <header>
            <img src="/img/logo_name.png" alt="">
            <h1>WikiWorld</h1>
        </header>
        <?php
        // Debug infos top right
        echo '<div class="debug">';
        echo 'PATH = ' . join('/', $url) . '<br>';
        if (isset($_COOKIE['player_id'])) {
            echo 'PLAYER_ID = ' . $_COOKIE['player_id'];
        }
        echo '</div>';
        // Include home page
        if (empty($url) || $url[0] === 'index.php') {
            include('router/home.php');
        } else {
            // Include classic pages from the menu
            if (in_array('/' . $url[0], $menuLink)) {
                include('router/' . $url[0] . '.php');
            } else {
                // Switch case for specific pages handling
                switch ($url[0]) {
                    case 'car':
                        if (isset($url[1])) {
                            $carId = (intval($url[1]) === 0 ? 1 : intval($url[1]));
                            include('router/car.php');
                        } else {
                            include('router/404.php');
                        }
                        break;
                    case 'constructor':
                        if (isset($url[1])) {
                            $constructorId = (intval($url[1]) === 0 ? 1 : intval($url[1]));
                            include('router/constructor.php');
                        } else {
                            include('router/404.php');
                        }
                        break;
                    case 'player':
                        if (isset($url[1])) {
                            $playerId = (intval($url[1]) === 0 ? 1 : intval($url[1]));
                            include('router/player.php');
                        } else {
                            include('router/404.php');
                        }
                        break;
                    case 'edit':
                        if (isset($url[2])) {
                            $editType = $url[1];
                            $editId = (intval($url[2]) === 0 ? 1 : intval($url[2]));
                            if ($url[1] === 'favorite') {
                                if (isset($url[3])) {
                                    $editId2 = (intval($url[3]) === 0 ? 1 : intval($url[3]));
                                } else {
                                    include('router/404.php');
                                    break;
                                }
                            }
                            include('router/edit.php');
                        } else {
                            include('router/404.php');
                        }
                        break;
                    case 'delete':
                        if (isset($url[2])) {
                            $removeType = $url[1];
                            $removeId = (intval($url[2]) === 0 ? 1 : intval($url[2]));
                            if ($url[1] === 'favorite') {
                                if (isset($url[3])) {
                                    $removeId2 = (intval($url[3]) === 0 ? 1 : intval($url[3]));
                                } else {
                                    include('router/404.php');
                                    break;
                                }
                            }
                            include('router/delete.php');
                        } else {
                            include('router/404.php');
                        }
                        break;
                        // Return 404 page if fails
                    default:
                        include('router/404.php');
                        break;
                }
            }
        }
        ?>
        <footer><i class="fal fa-copyright"></i> Réalisé par Quentin Lienhardt</footer>
    </div>
</body>

</html>
