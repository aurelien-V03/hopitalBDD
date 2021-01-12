<?php
    // connexion MySQL
     include('ressources_communes.php');

    /* Recuperer la liste des motifs d'admission pour la liste deroulante des motifs */
    $list_motifs = array();

    $requete_motif = "SELECT Code, libellé FROM motifs";
    $request_motif = getMysqlConnection()->prepare($requete_motif);
    $request_motif->execute();

    while($row = $request_motif->fetch()){
      $list_motifs[$row["Code"]] =  $row["libellé"];
    }

    /* Recuperer la liste des noms des pays pour la liste deroulante des pays  */
    $list_pays = array();

    $requete_pays = "SELECT Code, Libelle FROM pays";
    $request_pays = getMysqlConnection()->prepare($requete_pays);
    $request_pays->execute();

    while($row = $request_pays->fetch()){
      $list_pays[$row["Code"]] =  $row["Libelle"];
    }

    /* Liste des annees pour le formulaires des dates d'anniverssaires de 1930 a 2021*/
    $list_year = array();
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
    if(!empty($_POST["dateNaissance"]) && !empty($_POST['motif']) && !empty($_POST['pays'])  )
    {
      $errorSubmit = false;
      $nomPatient= "";   // Facultatif  
      if(!empty($_POST["nom"])){
        $nomPatient = $_POST["nom"];
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
        //echo $requete;
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
        position: absolute;
        top: 60px;
        left: 347px;
        color: #4f95a7;
        
      }
</style>
  </head>
  <body>
  <h1 class="h1">Recherche des patients</h1>
    <form method="POST" action="recherche_patient.php" onsubmit="return Validate()" name="vform" align="center">
      <div id="nom">
        <label>nom</label> <br>
        <input type="text" name="nom" class="textInput" placeholder="Indifférent">
      </div>

      <div id="code-motif">
        <label>motifs</label> <br>
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
        <label for="pays">pays</label> <br>
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
          <label>date de naissance</label> <br>
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

      <input type="submit" name="envoyer" value="envoyer" class="btn">
      </div>
   </form>

    <!-- Affichage de la liste des patients correspondants aux criteres -->
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