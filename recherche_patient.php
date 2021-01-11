<?php
  
    /* Connexion à la base de donne mysql avec PDO */
    /* Documentation PDO : https://www.php.net/manual/fr/book.pdo.php  */
    try{
    $user = "root";
    $pass = "";
    $bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8', 'root', '');

    /* Recuperer la liste des motifs d'admission pour la liste deroulante 1 */

    /* Recuperer la liste des noms des pays pour la liste deroulante 2  */

    }
    catch(Exception $e)
    {
    die('Erreur : ' . $e->getMessage());
    }


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
  </head>
  <body>
    <h1>Formulaire recherche patient</h1>


  </body>
</html>