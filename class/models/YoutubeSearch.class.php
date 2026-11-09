<?php


class YoutubeSearch {

    public function search($query) {
            // Permet de rechercher des vidéos sur YouTube en utilisant l'API YouTube Data v3
            // Remplacez 'YOUR_API_KEY' par votre clé API YouTube
            $apiKey = 'YOUR_API_KEY';
            $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . urlencode($query) . '&type=video&key=' . $apiKey;
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            return $data;
    }

}