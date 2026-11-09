<?php
// Utilisation d'un autoloader en PHP>5
if (defined("CHARGE_AUTOLOAD"))
{ // Chargement de l'autoload si et seulement si la constante a été définie
  set_autoload();
}
else
	die(""); // Arrêt en erreur, en mode silence (pas de message)


// fonction de chargement de classe en fonction de la version de php

function set_autoload()
{
    // L'autoloader cherche la classe demandée dans les dossiers du projet.
    function my_autoloader($classname) 
    {
        // L'ordre définit les dossiers parcourus lors de la résolution d'une classe.
        $folders = [
            './class/',
            './class/views/',
            './class/models/',
            './class/controllers/'
        ];

        foreach ($folders as $folder) {
            $filename = $folder . $classname . '.class.php';
            if (file_exists($filename)) {
                include_once($filename);
                return; // on s'arrête dès qu'on a trouvé la classe
            }
        }

        // Aucun fichier trouvé
        die("Erreur fichier inconnu : ".$classname);
    }

    spl_autoload_register('my_autoloader'); //PHP > 5
}

	
?>
