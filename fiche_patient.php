<?php

    include('ressources_communes.php');
    
    $patientArray = array();     // tableau associtif qui contient les informations du patients ( type information => valeur information)
    $arrayDocument = array();    // tableau associatif qui contient les documents du clients ( type document => documents de ce type)

    // recupere les informations du patients et ses documents grace au parametre $_GET
    if(!empty($_GET["codePatient"])){
      $codePatient = $_GET["codePatient"];
  
      // requete pour recupere les infos du patient
      $requetePatientInfo = "SELECT p.Code, Nom, Prenom, Sexe, DateNaiss, NumeroSecSoc, CodePays, DatePremEntree, CodeMotif, m.libellé as motifLibelle, Pays.libelle as paysLibelle, s.libellé as sexeLibelle FROM patients p, motifs m, Pays, sexe s  WHERE p.Code = ".$codePatient." AND p.CodeMotif = m.Code AND Pays.Code = p.CodePays AND s.Code = p.Sexe";

      $requete = getMysqlConnection()->prepare($requetePatientInfo);
      $requete->execute();

      $row = $requete->fetch();

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

      $arrayDocument =  getPatientDocuments(array($codePatient), array());
  }
 
  //
  //          formulaire d'upload validé
  //
if(isset($_POST["submit"]) && !empty($_POST["typeDocument"])) {
  
  // dossier ou le fichier sera enregistre
  $target_dir = "files/".$_POST["typeDocument"]."s/";
  // chemin du fichier à upload
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  // si l'upload s'est bien deroulé
  $uploadOk = 1;
  // extension du fichier à upload
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }
    // Allow certain file formats (only png / pdf / jpg)
    if($imageFileType != "jpg" && $imageFileType != "pdf" && $imageFileType != "png" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }
    // Check file size > 1000 ko
    if ($_FILES["fileToUpload"]["size"] > 1000000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } 
  else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

    // enregistrer l'url du document dans la base de donnee après que celui-ci a ete enregistré en local
    $typeDocument;
    if($_POST["typeDocument"] == "ordonnance")
      $typeDocument = 1;
    else if($_POST["typeDocument"] == "prescription")
      $typeDocument = 2;
    else
      $typeDocument = 3;

    // Date d'upload du fichier au format yyyy-mm-dd
    $date_creation = date("Y-m-d");  

    // insertion en base de donnee
    $requete_insert_document = "INSERT INTO Document (idPatient,typeDocument,filePath, urlFormat, dateCreation) VALUES(".$codePatient.",".$typeDocument.",'".$target_file."','".$imageFileType."','".$date_creation. "')";
    $insertDocumentRequest = getMysqlConnection()->prepare($requete_insert_document);
    $insertDocumentRequest->execute();

    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <!-- librairie CDN pour les icones -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="ajax.js">
    
     
    
    </script>
    <style>
    #buttonUpload{
      width:100%;
      padding: 10px;
    }
    
    </style>
  </head>
  <body>

    <!-- Titre -->
    <h2 style="text-align:center">Fiche detaillée de <?php
      $nom = !empty($_GET["nom"]) ? $_GET["nom"] : "";
      $prenom = !empty($_GET["prenom"]) ? $_GET["prenom"] : "";
      echo($nom." ".$prenom) ?>
    </h2>

    <!-- Affichage des informations du patient -->
    <table class="tableInformation">
        <?php 
        foreach ($patientArray as $key => $value) {
          echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
         }
        ?>
    </table>

      <!-- Affichage des documents du patient -->
      <h2 style="text-align:center">Historique des documents</h2>
        <table class="tableInformation">
         <tbody>
            <tr><th>Nom fichier</th><th>Date creation</th><th>Type document</th></tr>
          <?php
              // Pour chaque categorie de document
              foreach ($arrayDocument as  $key => $documentType) {
                  $documentHTML =  "";
                  // Pour chaque document de cette categorie
                  foreach($documentType as $doc)
                  {                 
                    $documentHTML .= "<tr>".$doc->getRowTable()."</tr>";
                  }
                  echo $documentHTML;  
              }
          ?>
         </tbody>
        </table>

        <!-- Formulaire d'upload -->
        <h2 style="text-align:center">Upload document</h2>
        <div style="width:40%; margin:auto">

        <form action="" method="post" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" class="inputfile" style="display:block">
            <select name="typeDocument" style="display:block">
                <option value="">Choisissez le type de document</option>
                <option value="ordonnance">ordonnance</option>
                <option value="prescription">prescription</option>
                <option value="cartesIdentite">carte identite</option>
            </select>
            <input id="buttonUpload" type="submit" value="Upload Image" name="submit">
         </form>

        </div>
        
    <!-- Bouton retour -->
    <div style="text-align:center; margin-top:40px">
      <a href="recherche_patient.php">Retour au formulaire</a>
    </div>

  </body>
</html>