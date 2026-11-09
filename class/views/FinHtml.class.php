<?php


class finHtml {
    public function afficher() {
        echo '          <script src="ressources/JS/processing.js"></script>';
        echo '          <script>';
        echo '              document.querySelector("#background-video").playbackRate = 0.6;';
        echo '          </script>';
        echo '      </body>';
        echo '    </html>';

    }
};