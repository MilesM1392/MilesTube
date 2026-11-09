# MilesTube
Système de téléchargements depuis youtube en php orienté objet

# MileTube

**MileTube** est une application web permettant de télécharger et de convertir des contenus multimédias à partir de sources compatibles avec [yt-dlp](https://github.com/yt-dlp/yt-dlp).

L'application propose une interface simple permettant de récupérer des médias au format audio ou vidéo, avec un traitement assuré côté serveur.

## Fonctionnalités

- Téléchargement de médias depuis une URL.
- Extraction audio au format MP3.
- Téléchargement de vidéos au format MP4.
- Récupération des métadonnées.
- Intégration des miniatures dans les fichiers.
- Affichage du titre original des médias.
- Gestion des erreurs de téléchargement.

## Technologies utilisées

| Technologie | Utilisation |
|---|---|
| PHP 8.4+ | Backend et logique applicative |
| HTML / CSS | Interface utilisateur |
| JavaScript | Interactions côté client |
| yt-dlp | Extraction et téléchargement des médias |
| FFmpeg | Conversion et traitement multimédia |
| FFprobe | Analyse des fichiers multimédias |
| Apache | Serveur web |
| Python | Environnement d'exécution de yt-dlp |

## Architecture

Le projet repose sur une architecture MVC en PHP afin de séparer les responsabilités :

- **Models** : gestion des données.
- **Views** : affichage des interfaces.
- **Controllers** : gestion des requêtes et coordination des traitements.
- **Services** : logique métier et interactions avec les outils externes.

Les traitements multimédias sont délégués à des services dédiés qui exécutent les outils système nécessaires.

## Prérequis

- PHP 8.4 ou supérieur.
- Serveur Apache avec `mod_rewrite`.
- yt-dlp.
- FFmpeg et FFprobe.
- Python 3.
- Un runtime JavaScript compatible avec yt-dlp (Node.js ou Deno).

## Installation

1. Cloner le dépôt :

   ```bash
   git clone <URL_DU_DEPOT>
   cd milestube
   ```

2. Installer et configurer les dépendances système.

3. Configurer les chemins et paramètres nécessaires à l'exécution de yt-dlp.

4. Configurer le serveur web pour pointer vers le répertoire `public/`.

5. Vérifier les permissions d'accès aux répertoires de stockage et de cache.

## Configuration

Les paramètres liés à l'environnement d'exécution doivent être adaptés à la machine hôte.

Les principaux éléments à configurer sont :

- Chemin vers l'exécutable yt-dlp.
- Chemin vers FFmpeg.
- Répertoire de stockage temporaire.
- Répertoire de cache yt-dlp.
- Fichier de cookies lorsque la source le nécessite.

## Licence et utilisation

MileTube est un projet personnel à vocation pédagogique et technique.

L'utilisateur est responsable du respect des droits d'auteur, des conditions d'utilisation des plateformes et de la législation applicable lors de l'utilisation du service.