<?php
  
    include('ressources_communes.php');
    // recupere le code du patient via les parametres de l'URL (GET)
    $codePatient = $_GET["codePatient"];
  
    // requete pour recupere les infos du patient
    $requetePatientInfo = "SELECT p.Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif, m.libellé as motifLibelle, Pays.libelle as paysLibelle, s.libellé as sexeLibelle FROM patients p, motifs m, Pays, sexe s  WHERE p.Code = ".$codePatient." AND p.CodeMotif = m.Code AND Pays.Code = p.CodePays AND s.Code = p.Sexe";

    $requete = getMysqlConnection()->prepare($requetePatientInfo);
    $requete->execute();

    $row = $requete->fetch();

    // tableau associtif qui contient les lignes pre-formatées avec les informations du patients
    $patientArray = array(
      "Code" => "<td>".$row["Code"]."</td>",
      "Nom" => "<td>".$row["Nom"]."</td>",
      "Prenom" => "<td>".$row["Prenom"]."</td>",
      "Sexe" => "<td>".$row["sexeLibelle"]."</td>",
      "Date de naissance" => "<td>".$row["DateNaiss"]."</td>",
      "Numero Securite sociale" => "<td>".$row["NumeroSecSoc"]."</td>",
      "Pays" => "<td>".$row["paysLibelle"]."</td>",
      "Date Premiere entree" => "<td>".$row["DatePremEntree"]."</td>",
      "Motif de derniere visite" => "<td>".$row["motifLibelle"]."</td>"
    );
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
    <h1>Fiche detaille du patient</h1>
    <table>
        <?php 
        // Affichage des attributs du patient
        foreach ($patientArray as $key => $value) {
          echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
         }
        ?>
    </table>

    <a href="recherche_patient.php">Retour au formulaire</a>

  </body>
</html>