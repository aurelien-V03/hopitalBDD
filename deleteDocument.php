<?php
// Supprime une occurrence de la table document en fonction de l'id 
include('ressources_communes.php');

$numDocToDelete = $_GET["numDocDelete"];
$requete = "DELETE FROM document where idDocument = ".$numDocToDelete;

$deleteDoc = getMysqlConnection()->prepare($requete);
$deleteDoc->execute();
?>