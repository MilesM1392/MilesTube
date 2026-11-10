<?php

/**
 * Télécharge une vidéo YouTube ou en extrait la piste audio avec yt-dlp.
 * Les fichiers produits sont enregistrés dans storage/downloads/.
 */
class Downloader {

    /** Répertoire de sortie des téléchargements. */
    private string $dossier;

    public function __construct() {

        $this->dossier = __DIR__ . '/../storage/downloads/';

        if (!is_dir($this->dossier) || !is_writable($this->dossier)) {
            throw new RuntimeException(
                "Le dossier de téléchargement est inaccessible."
            );
        }
    }

    /** Télécharge le lien fourni et demande une conversion en MP3. */
    public function downloadMP3($link) {
        return $this->download($link, true);
    }

    /** Télécharge le lien fourni au format vidéo MP4. */
    public function downloadMP4($link) {
        return $this->download($link, false);
    }
    
    /**
     * Lance yt-dlp après validation du lien, puis renvoie le chemin du fichier
     * créé. $audio détermine si seule la piste audio doit être extraite.
     */
    private function download($link, $audio) {

        // Refuser les chaînes qui ne sont pas des URL valides.
        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Limiter le téléchargement aux domaines YouTube autorisés.
        $host = parse_url($link, PHP_URL_HOST);

        if (!in_array($host, ['youtube.com', 'www.youtube.com', 'youtu.be', 'www.youtu.be', 'm.youtube.com'])) {
            return false;
        }

        $commande = "/usr/bin/timeout --kill-after=10s 600s /usr/local/bin/yt-dlp";

        // Choisir les options de yt-dlp selon le format demandé.
        if ($audio) {
            $commande .= " -x --audio-format mp3";
        } else {
            $commande .= " -f bestvideo+bestaudio --merge-output-format mp4";
        }

        // Ajouter les métadonnées et la miniature au fichier final.
        $commande .= " --embed-metadata";
        $commande .= " --embed-thumbnail";
        $commande .= " --convert-thumbnails jpg";

        // Le modèle de nommage utilise le titre de la vidéo. yt-dlp affiche
        // ensuite le chemin final, que l'on pourra retrouver dans sa sortie.
        // Le nom de fichier est unique grâce à un identifiant aléatoire.
        $idTelechargement = bin2hex(random_bytes(2)); // 4 caractères hexadécimaux pour identifier le téléchargement
        $commande .= " -o " . escapeshellarg($this->dossier . "%(title)s [%(id)s]".$idTelechargement."." . ($audio ? "mp3" : "mp4"));
        $commande .= " --print after_move:filepath";
        $commande .= " " . escapeshellarg($link);

        // Exécuter la commande et récupérer sa sortie ainsi que son code retour.
        exec($commande . " 2>&1", $output, $code);

        // Affichage de diagnostic : utile pour voir la sortie de yt-dlp pendant
        // le développement, mais visible aussi dans la réponse HTTP.
        echo "<pre>";
        echo "Code retour : " . $code . "\n";
        print_r($output);
        echo "</pre>";

        //exec("id; echo PATH=\$PATH; command -v deno; command -v node", $output);
        //var_dump($output);

        // Vérifier le code retour de yt-dlp.
        exec($commande . " 2>&1", $output, $code);

        if ($code !== 0) {
            if ($code === 124 || $code === 137) {
                error_log("[MileTube] Téléchargement interrompu : délai maximal dépassé.");
            } else {
                error_log(
                    "[MileTube] Échec yt-dlp (code {$code}) :\n" .
                    implode("\n", $output)
                );
            }

            return false;
        }

        // Retrouver le fichier généré.
        foreach (array_reverse($output) as $ligne) {

            $ligne = trim($ligne);

            if (is_file($ligne) && filesize($ligne) > 0 && is_readable($ligne)) {
                return $ligne;
            }
        }

        // Aucun fichier valide trouvé.
        error_log("Fichier généré introuvable ou invalide : " . implode("\n", $output));

        return false;
    }
}