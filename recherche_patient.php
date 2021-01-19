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
    $list_year = array("Indifferent");
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

    // Si l'utilisateur patientsTrouvea entre les valeurs des formulaires obligatoires
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

      // tableau qui contient tous les filtres a appliquer dans la requete SQL
      $requete_filter = array();
      // corps de base de la requete
      $requete = "SELECT Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif FROM patients";
      if(strlen($_POST["nom"]) > 0)
        array_push($requete_filter, "Nom = '". $nomPatient."'");
      if($_POST["motif"] != "Indifferent")
         array_push($requete_filter, "CodeMotif = " . $motifAdmission);
      if($_POST["pays"] != "Indifferent")
         array_push($requete_filter, "CodePays = '". $pays."'" );
      if($_POST["dateNaissance"] != "Indifferent")
         array_push($requete_filter, "DateNaiss BETWEEN '". $dateNaisseMin. "' AND '". $dateNaissMax."'");

      // concatene les contraintes
      for($i = 0 ; $i < count($requete_filter) ; $i++){
        if($i ==0)
          $requete .=  " WHERE ".$requete_filter[$i];
        else
        $requete .=  " AND ".$requete_filter[$i];
      }

      // recupere la liste des patients correspondants aux criteres
      $patientsTrouve = createPatientArray($requete);
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
        #divcodemotif, #divcodepays, #datenaissance{
          width:50%;
          margin:auto;
        }
        /* liens des patients trouves */
        a{
          text-decoration:none;
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

      <div id="divcodemotif">
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
    
      <div id="divcodepays">
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

    <!-- Affichage de la liste des patients correspondants aux criteres quand formulaire validé -->
    <?php
        $table_patients = '<h2 id="titleResultRecherche">Résultats de votre recherche : </h2>';
        $table_patients .=  '<table style="width:100%; text-align:center">';
         // Affichage de chaque lien de patient trouve
         foreach($patientsTrouve as $link){
          $table_patients .=  "<tr><td>". $link ."</td></tr>"; 
        }
        $table_patients .= "</table>";

        if(!empty($_POST["submit"]))
           echo $table_patients;
    ?>
  </body>
</html>