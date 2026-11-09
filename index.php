<?php
// Afficher les erreurs PHP pendant le développement (y compris sur un serveur configuré pour la production).
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set("display_errors", 1);

// Active le mécanisme d'autoload défini dans inc/poo.inc.php.
define("CHARGE_AUTOLOAD",true);
require_once("inc/poo.inc.php"); 

// La session doit être ouverte avant tout accès à $_SESSION.
session_start();

// L'action RAZ déconnecte l'utilisateur en supprimant son nom de la session,
// puis le redirige vers la page d'accueil.
if (isset($_GET['RAZ'])) {
    unset($_SESSION['username']);
    header('Location: ./');
    exit;
}



// Le formulaire envoie l'URL en POST. Une réussite renvoie le MP3 au navigateur.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {

    // Instancier le service qui lance les téléchargements via yt-dlp.
    $downloader = new Downloader();
    $fichier = $downloader->downloadMP3($_POST['url']);

    if ($fichier === false) {
        http_response_code(500);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors du téléchargement.'
        ]);

        exit;
    }

    header('Content-Type: audio/mpeg');
    header('Content-Disposition: attachment; filename="' . basename($fichier) . '"');
    header('Content-Length: ' . filesize($fichier));

    readfile($fichier);
    unlink($fichier);

    exit;
}

//- ------- Début du document html -------

    $entete = new Head();
    $entete->afficher();


    $header = new Header();
    $header->afficher();

?>     

    <!-- Formulaire d'accueil : l'URL est transmise à ce même contrôleur en POST. -->
    <form id="formulaire-telechargement" method="post" action="./">

        <div >
            <input id="url-input" type="url" name="url" placeholder="Lien YouTube..." required />
            <button id="bouton-lancer" type="submit">Télécharger</button>
        </div>

        <div>
            <label for="audio">Télécharger la vidéo</label>
            <input type="checkbox" name="audio" value="0" id="audio" />
        </div>

    </form>


    <iframe id="spotify" data-testid="embed-iframe" style="border-radius:12px" src="https://open.spotify.com/embed/track/3eX2JOSwcPjVEe2zbVZ2GH?utm_source=generator&theme=0&si=23328f68948e487e" width="40%" height="120" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>

<?php


    $spinner = new Spinner();
    $spinner->afficher();

    $fin = new FinHtml();
    $fin->afficher();