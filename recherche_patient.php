<?php
    // connexion MySQL
     include('ressources_communes.php');

    /*
    ================================================================================================
                                Recupere les donnees mysql pour les formulaires
    ================================================================================================
    */

    /* Recuperer la liste des motifs d'admission pour la liste deroulante des motifs */
    $list_motifs = array("Indifferent" => "Indifferent");

    $requete_motif = "SELECT Code, libellé FROM motifs";
    $request_motif = getMysqlConnection()->prepare($requete_motif);
    $request_motif->execute();
    
    while($row = $request_motif->fetch()){
      $list_motifs[$row["Code"]] =  $row["libellé"];
    }

    /* Recuperer la liste des noms des pays pour la liste deroulante des pays  */
    $list_pays = array("Indifferent" => "Indifferent");

    $requete_pays = "SELECT Code, Libelle FROM pays";
    $request_pays = getMysqlConnection()->prepare($requete_pays);
    $request_pays->execute();

    while($row = $request_pays->fetch()){
      $list_pays[$row["Code"]] =  $row["Libelle"];
    }

    /* Liste des annees pour le formulaires des dates d'anniverssaires de 1930 a 2021*/
    $list_year = array("Indiferent");
    for($i = 2021 ; $i >= 1900 ; $i--){
        array_push($list_year, $i);
    }


    /*
    ================================================================================================
                                               FORMULAIRE
    ================================================================================================
    */
    
    // contient les liens <a></a> des patients qui correspondent aux criteres du formulaire
    $patientsTrouve = array();

    // Si l'utilisateur a entre les valeurs des formulaires obligatoires
    if(isset($_POST["submit"]))
    {
      $nomPatient= "";   // Facultatif  
      if(!empty($_POST["nom"])){
        $nomPatient = strtoupper($_POST["nom"]);
      }

      $motifAdmission = $_POST["motif"]; 
      $pays = $_POST["pays"];
      $anneeDateNaissance = $_POST["dateNaissance"];

      // intervalle de valeur pour 1 annee entiere
      $dateNaisseMin = $anneeDateNaissance. "/01/01";
      $dateNaissMax = $anneeDateNaissance . "/12/31";

      // recherche des patients par prenom
      if(strlen($nomPatient) > 0){
        $requete = "SELECT Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif FROM patients WHERE Nom = '". $nomPatient."'
        AND CodeMotif = " . $motifAdmission . " AND CodePays = '". $pays . "' AND DateNaiss BETWEEN '". $dateNaisseMin. "' AND '". $dateNaissMax."'";
        
        $patientsTrouve = createPatientArray($requete);
      }
      // On recherche sans le nom du patient
      else{
        $requete = "SELECT Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif FROM patients WHERE CodeMotif = " . $motifAdmission . " AND CodePays = '". $pays . "' AND DateNaiss BETWEEN '". $dateNaisseMin. "' AND '". $dateNaissMax."'"   ;
       
        $patientsTrouve = createPatientArray($requete);
      }  
    }
    else {
      $errorSubmit = true;
    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Hopital</title>
    <link rel="stylesheet" href="style.css">
    <style>
        input[type=text], select {
        width: 50%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #729fb9;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: #729fb9;
      }

    input[type=date], select {
        width: 50%;
        padding: 14px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #729fb9;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: #729fb9;
      }

      input[type=number], select {
        width: 50%;
        padding: 14px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #b88296;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: #ddd5d57a;
      }
      input[type=submit] {
        width: 50%;
        background-color: #1a3e47;
        color: rgb(114, 112, 112);
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
      }

      input[type=submit]:hover {
        background-color: #61b13b;
      }
      label{
        position: absolute;
        left: 350px;
      }
      .h1{
        text-align:center;
        top: 60px;
        left: 347px;
        color: #4f95a7;
      }

      #titleResultRecherche{
        text-align:center;
      }
</style>
  </head>
  <body>
     <h1 class="h1">Recherche de patients</h1>

     <!-- Formulaire  -->
    <form method="POST" action="recherche_patient.php" name="vform" align="center">

      <div id="nom">
        <label>Nom</label> <br>
        <input type="text" name="nom" class="textInput" placeholder="Indifférent">
      </div>

      <div id="code-motif">
        <label>Motif</label> <br>
        <select name="motif" id="code-motif">
          <?php  
            // Affichage de chaque motif
            foreach ($list_motifs as $key => $value) {
              echo "<option value='".$key."'>".$value."</option>";
            }
          ?>   
        </select>
      </div>
    
      <div id="code-pays">
        <label for="pays">Pays</label> <br>
          <select name="pays" id="pays" placeholder="Indifférent">
            <?php 
              // Affichage de chaque pays 
              foreach ($list_pays as $key => $value) {
                echo "<option value='".$key."'>".$value."</option>";
              }
            ?>  
          </select>
      </div>

        <div id="datenaissance">
          <label>Date de naissance</label> <br>
          <select name="dateNaissance" id="dateNaissance">
          <?php 
            // Affichage de chaque annee de naissance 
            foreach ($list_year as $value) {
              echo "<option value='".$value."'>".$value."</option>";
            }
          ?>  
          </select>
        </div>
      <div>

      <input type="submit" name="submit" value="envoyer" class="btn">
      </div>
   </form>

    <!-- Affichage de la liste des patients correspondants aux criteres -->
    <h3 id="titleResultRecherche">Résultats de votre recherche : </h3>
    <table style="width:100%; text-align:center">
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