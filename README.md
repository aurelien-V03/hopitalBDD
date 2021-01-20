# hopitalBDD
Projet realisé dans le cadre de la M1 MIAGE.
Il s'agit d'une application simple qui permet à un utilisateur (hypothétiquement une personne d'un hopital)
de rechercher dans une base de donnee MySQL des informations sur certains patients avec un formulaire.
La partie front du site est realisee en PHP.

Etape pour mettre en place l'application web:

1. Mise en place de la base de donnée MySQL : executez le script base_hopital.sql dans PHPmyAdmin ou directement dans votre console MySQL
2. Ajout des fichiers PHP  : déposez les fichiers PHP suivants à la racine de WAMP dans le dossier www
            -recherche_patient.php (correspond à la racine du site web)
            -fiche_patient.php
            -document_affiche.php
            -ressources_communes.php

3. Installation de sendMail : Pour l'envoie de mail en local, il est necessaire d'utiliser le service sendMail (tuto : https://www.grafikart.fr/blog/mail-local-wamp)

Modifier le fichier "document_affiche.php" ligne 142 pour mettre votre propre adresse mail, ainsi que dans les fichiers de configuration (sebdmail.ini et php.ini).

4. Lancez votre serveur WAMP 

5. accédez au site via http://localhost
            