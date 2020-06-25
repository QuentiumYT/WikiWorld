<?php
// Display the back button
function backButton($href, $text)
{
    echo '<a class="back-button" href="' . $href . '">';
    echo '<div class="back-button-item">' . $text . '</div>';
    echo '</a>';
}
