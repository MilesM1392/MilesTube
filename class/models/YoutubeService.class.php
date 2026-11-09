<?php


/** Appelle l'API YouTube Data pour rechercher des vidéos publiques. */
class YoutubeService {

    /** Retourne les vidéos trouvées ou lève une exception en cas d'échec. */
    public function search($query, $maxResults = 10) {

        // Vérification de la recherche
        $query = trim($query);

        if (empty($query)) {
            throw new InvalidArgumentException("La recherche ne peut pas être vide.");
        }

        // Clé API YouTube
            $apiKey = getenv('YOUTUBE_API_KEY');

        if (!$apiKey) {
            throw new RuntimeException("Clé API YouTube introuvable.");
        }

        // Construction de l'URL
        // Limiter maxResults à la plage acceptée par l'API (1 à 50).
        $params = [
            'part'       => 'snippet',
            'q'          => $query,
            'type'       => 'video',
            'maxResults' => min(max((int)$maxResults, 1), 50),
            'order'      => 'relevance',
            'key'        => $apiKey
        ];

        $url = 'https://www.googleapis.com/youtube/v3/search?' . http_build_query($params);

        // Configuration de la requête HTTP
        $context = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'timeout' => 10,
                'ignore_errors' => true
            ]
        ]);

        // Exécution de la requête
        // ignore_errors permet de lire le corps JSON même pour un statut HTTP d'erreur.
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new RuntimeException("Impossible de contacter l'API YouTube.");
        }

        // Décodage JSON
        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Réponse JSON invalide.");
        }

        // Gestion des erreurs retournées par YouTube
        if (isset($data['error'])) {
            throw new RuntimeException(
                $data['error']['message'] ?? "Erreur inconnue de l'API YouTube."
            );
        }

        // Retourne les résultats
        return $data['items'] ?? [];
    }

}
