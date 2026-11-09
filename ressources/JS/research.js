

document.addEventListener('DOMContentLoaded', () => {
    // Références au formulaire et à la zone où les résultats seront rendus.
        console.log("Fichier JS chargé !");
    const formulaire = document.getElementById('form-recherche');
    const champRecherche = document.getElementById('recherche-input');
    const resultats = document.getElementById('resultats-recherche');

    // Intercepte le formulaire pour afficher les résultats sans recharger la page.
    formulaire.addEventListener('submit', async (event) => {


        event.preventDefault();

        console.log("Formulaire intercepté par JavaScript !");

        event.preventDefault();

        const query = champRecherche.value.trim();

        if (!query) return;

        resultats.innerHTML = '<p>Recherche en cours...</p>';

        try {

            // Envoi de la recherche à PHP
            const reponse = await fetch(formulaire.action || window.location.href, {
                method: 'POST',
                body: new FormData(formulaire)
            });

            const data = await reponse.json();

            if (!reponse.ok || !data.success) {
                throw new Error(data.message || 'Erreur lors de la recherche.');
            }

            // Affichage des résultats
            resultats.innerHTML = '';

            if (data.videos.length === 0) {
                resultats.textContent = 'Aucune vidéo trouvée.';
                return;
            }

            // Construit une carte par vidéo et mémorise son URL pour le téléchargement.
            data.videos.forEach(video => {

                const snippet = video.snippet;

                const carte = document.createElement('article');
                carte.classList.add('video-card');

                const image = document.createElement('img');
                image.src = snippet.thumbnails.high.url;
                image.alt = '';

                const titre = document.createElement('h3');
                titre.textContent = snippet.title;

                const chaine = document.createElement('p');
                chaine.textContent = snippet.channelTitle;

                const bouton = document.createElement('button');
                bouton.textContent = 'Télécharger';

                bouton.classList.add('bouton-telecharger');
                bouton.dataset.url = `https://www.youtube.com/watch?v=${video.id.videoId}`;

                carte.append(image, titre, chaine, bouton);

                resultats.appendChild(carte);

            });

        } catch (erreur) {

            resultats.textContent = erreur.message;

        }

    });

    // Un seul écouteur gère les boutons créés dynamiquement dans les cartes.
    resultats.addEventListener('click', (event) => {

    const bouton = event.target.closest('.bouton-telecharger');

    if (!bouton) return;

    const url = bouton.dataset.url;

    // On transmet l'URL au système de téléchargement
    document.getElementById('url-input').value = url;

    document.getElementById('bouton-lancer').click();

});

});
