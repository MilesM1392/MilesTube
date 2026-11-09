document.addEventListener('DOMContentLoaded', () => {

    //fonctionnera quand j'aurai fini la partie recherche et que je pourrai récupérer le formulaire et le bouton de lancement
    const bouton = document.getElementById('bouton-lancer');
    const formulaire = bouton.closest('form');
    const urlForm = document.getElementById('url-input');
    const zoneLancement = document.getElementById('zone-lancement');

    // Contrôle navigateur complémentaire à la validation PHP dans Downloader.
    const domainesAutorises = [
        'youtube.com',
        'www.youtube.com',
        'm.youtube.com',
        'youtu.be',
        'www.youtu.be'
    ];

    formulaire.addEventListener('submit', async (event) => {

        event.preventDefault();

        // Vérification de l'URL
        try {
            const url = new URL(urlForm.value.trim());

            if (
                !['http:', 'https:'].includes(url.protocol) ||
                !domainesAutorises.includes(url.hostname)
            ) {
                alert("Veuillez saisir une URL YouTube valide.");
                return;
            }

        } catch {
            alert("URL invalide.");
            return;
        }

        // Affiche une surcouche pendant que le serveur exécute yt-dlp.
        zoneLancement.innerHTML = `
            <main class="ecran-lancement">
                <div class="lancement-contenu">
                    <div class="spinner" role="status"></div>
                    <p>Conversion et téléchargement en cours...</p>
                </div>
            </main>
        `;

        bouton.disabled = true;

        try {

            // Envoi de la requête à PHP
            const reponse = await fetch(formulaire.action || window.location.href, {
                method: 'POST',
                body: new FormData(formulaire)
            });

            if (!reponse.ok) {
                throw new Error("Erreur lors du téléchargement.");
            }

            // Récupération du fichier
            const fichier = await reponse.blob();

            // Récupération du nom du fichier
            const disposition = reponse.headers.get('Content-Disposition');
            const correspondance = disposition?.match(/filename="(.+)"/);

            const nomFichier = correspondance
                ? correspondance[1]
                : 'telechargement';

            // Déclenche le téléchargement du Blob reçu sans naviguer vers une page audio.
            const lien = document.createElement('a');
            const urlFichier = URL.createObjectURL(fichier);

            lien.href = urlFichier;
            lien.download = nomFichier;

            document.body.appendChild(lien);
            lien.click();
            lien.remove();

            URL.revokeObjectURL(urlFichier);

            // Fin du traitement
            zoneLancement.innerHTML = `
                <p>Téléchargement terminé !</p>
            `;

            console.log("Téléchargement terminé, attente du rafraîchissement...");

            setTimeout(() => {
                console.log("Rafraîchissement en cours !");
                window.location.assign(window.location.href);
            }, 2000);

        } catch (erreur) {

            zoneLancement.innerHTML = `
                <p>Une erreur est survenue : ${erreur.message}</p>
            `;

        } finally {

            bouton.disabled = false;

        }

    });

});
