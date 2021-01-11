<?php
  include('ressources_communes.php');
    /* Recuperer la liste des motifs d'admission pour la liste deroulante 1 */

    /* Recuperer la liste des noms des pays pour la liste deroulante 2  */
// contient les liens <a></a> des patients qui correspondent aux criteres du formulaire
$patientsTrouve = array();

$nomPatient = "";   // Facultatif  
$motifAdmission = 3; 
$pays = "FR";
$anneeDateNaissance = "1988";


$dateNaisseMin = $anneeDateNaissance. "/01/01";
$dateNaissMax = $anneeDateNaissance . "/12/31";

// recherche des patients par prenom
if(strlen($nomPatient) > 0){

  $requete = "SELECT Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif FROM patients WHERE Nom = '". $nomPatient."'
   AND CodeMotif = " . $motifAdmission . " AND CodePays = '". $pays . "' AND DateNaiss BETWEEN '". $dateNaisseMin. "' AND '". $dateNaissMax."'";
  $patientsTrouve = createPatientArray($requete);
  //echo $requete;
}
// On recherche sans le nom du patient
else{
  $requete = "SELECT Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif FROM patients WHERE CodeMotif = " . $motifAdmission . " AND CodePays = '". $pays . "' AND DateNaiss BETWEEN '". $dateNaisseMin. "' AND '". $dateNaissMax."'"   ;
  $patientsTrouve = createPatientArray($requete);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Hopital</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
  </head>
  <body>
    <h1>Formulaire recherche patient</h1>




    <p>Liste des patients </p>
    <table>
    <?php
        // Affichage de chaque lien de patient trouve
        foreach($patientsTrouve as $link){
          $row =  "<tr><td>". $link ."</td></tr>"; 
          echo $row;
        }
    ?>
    </table>


  </body>
</html>