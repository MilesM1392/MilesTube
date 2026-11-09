<?php
// Afficher les erreurs PHP pendant le développement (y compris sur un serveur configuré pour la production).
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set("display_errors", 1);

// Active le mécanisme d'autoload défini dans inc/poo.inc.php.
define("CHARGE_AUTOLOAD",true);
require_once("inc/poo.inc.php"); 

// Chargement des variables d'environnement
// Le fichier .env reste local et n'est pas versionné (voir .gitignore).
$env = parse_ini_file(__DIR__ . '/.env');

foreach ($env as $key => $value) {
    // Rend les paramètres accessibles à getenv() et au code PHP.
    putenv("$key=$value");
    $_ENV[$key] = $value;
}

// Le formulaire envoie la recherche en POST.
// Une réussite renvoie les résultats YouTube au format JSON.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {

    header('Content-Type: application/json; charset=utf-8');

    try {

        // Instancier le service de recherche YouTube.
        $youtube = new YoutubeService();

        // Effectuer la recherche.
        $videos = $youtube->search($_POST['query']);

        // Ce contrôleur répond en JSON ; le navigateur construit les cartes.
        echo json_encode([
            'success' => true,
            'videos' => $videos
        ]);

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la recherche YouTube.',
            'que paso: ' . $e->getMessage()
        ]);

    }

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

    // La réponse est le fichier ; le navigateur lit son nom dans l'en-tête.
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

    <!-- Formulaire intercepté par research.js pour afficher les résultats sans rechargement. -->
    <form id="form-recherche" action="./" method="POST">

        <input type="text" id="recherche-input" name="query" placeholder="Rechercher une vidéo..." required />

        <button type="submit">
            Rechercher
        </button>

    </form>

    <div id="resultats-recherche">
        <!-- Les résultats de la recherche YouTube seront affichés ici par JavaScript. -->
    </div>

    <iframe id="spotify" data-testid="embed-iframe" style="border-radius:12px" src="https://open.spotify.com/embed/track/3eX2JOSwcPjVEe2zbVZ2GH?utm_source=generator&theme=0&si=23328f68948e487e" width="40%" height="120" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>

    <!-- l'URL est transmise à ce même contrôleur en POST.  caché sur l'affichage mais nécessaire pour l'instant-->
    <form style="display: none;" id="formulaire-telechargement" method="post" action="./">

        <div >
            <input id="url-input" type="url" name="url" placeholder="Lien YouTube..." required />
            <button id="bouton-lancer" type="submit">Télécharger</button>
        </div>

        <div>
            <label for="audio">Télécharger la vidéo</label>
            <input type="checkbox" name="audio" value="0" id="audio" />
        </div>

    </form>

<?php



    $spinner = new Spinner();
    $spinner->afficher();

    $fin = new FinHtml();
    $fin->afficher();
