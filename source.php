<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Source code scraping</title>
    <link href="css/prism.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <?php
    // Get lines from Python file
    $file = file_get_contents('db/get_cars.py', true);
    echo '<pre class="line-numbers"><code class="language-py">';
    // Echo file in pre code class format
    echo $file;
    echo '</code></pre>';
    ?>
    <script src="js/prism.js"></script>
</body>

</html>