<?php


class Head {
    public function afficher() {
        echo '<!DOCTYPE html>';
        echo '<html lang="fr" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
        echo '<head>';
        echo '<meta charset="UTF-8" />';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
        echo '<title>MilesTube - Youtube DL</title>';
        echo '<link rel="icon" type="image/avif" href="ressources/assets/img/favicon.avif" />';
        echo '<link rel="stylesheet" type="text/css" href="ressources/css/style.css">';
        echo '</head>';
    }
};