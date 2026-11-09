<?php


class Header {
    public function afficher() {
        echo '<body>';

        echo '        <h1>MilesTube - Youtube DL</h1>';

        echo '        <video autoplay muted loop playsinline id="background-video">';
        echo '        <source src="ressources/assets/img/background.mp4" type="video/mp4" />';
        echo '            Votre navigateur ne supporte pas les vidéos.';
        echo '        </video>';
        
    }

    
};