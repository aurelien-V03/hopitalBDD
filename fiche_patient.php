<?php
    include('ressources_communes.php');
    
    // recupere le code du patient via les parametres de l'URL (GET)
    $codePatient = $_GET["codePatient"];
  
    // requete pour recupere les infos du patient
    $requetePatientInfo = "SELECT p.Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif, m.libellé as motifLibelle, Pays.libelle as paysLibelle, s.libellé as sexeLibelle FROM patients p, motifs m, Pays, sexe s  WHERE p.Code = ".$codePatient." AND p.CodeMotif = m.Code AND Pays.Code = p.CodePays AND s.Code = p.Sexe";

    $requete = getMysqlConnection()->prepare($requetePatientInfo);
    $requete->execute();

    $row = $requete->fetch();

    // tableau associtif qui contient les lignes avec les informations du patients
    $patientArray = array(
      "Code" => $row["Code"],
      "Nom" => $row["Nom"],
      "Prenom" => $row["Prenom"],
      "Sexe" => $row["sexeLibelle"],
      "Date de naissance" => $row["DateNaiss"],
      "Numero Securite sociale" => $row["NumeroSecSoc"],
      "Pays" => $row["paysLibelle"],
      "Date Premiere entree" => $row["DatePremEntree"],
      "Motif de derniere visite" => $row["motifLibelle"]
    );
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h2 style="text-align:center">Fiche detaille du patient</h2>
    <table style="margin:auto; margin-top:30px">
        <?php 
        // Affichage des attributs du patient 
        foreach ($patientArray as $key => $value) {
          echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
         }
        ?>
    </table>

<div style="text-align:center; margin-top:40px">
  <a href="recherche_patient.php">Retour au formulaire</a>
</div>

  </body>
</html>