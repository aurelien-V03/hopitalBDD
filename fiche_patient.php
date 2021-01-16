<?php
    include('ressources_communes.php');
    
    $patientArray = array();
    $arrayDocument = array();

    // recupere le code du patient via les parametres de l'URL (GET)
    if(!empty($_GET["codePatient"])){
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

    $arrayDocument =  getPatientDocuments($codePatient);
  }

 
if(isset($_POST["submit"]) && !empty($_POST["typeDocument"])) {
  // dossier ou le fichier sera enregistre
  $target_dir = "files/".$_POST["typeDocument"]."/";
  // chemin du fichier à upload
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  // extension du fichier à upload
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
        

    } else {
      echo "File is not an image.";
      $uploadOk = 0;
    }

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
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
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
    <link rel="stylesheet" href="style.css">
    <style>
      table{
        margin:auto;  
      }

      .tableInformation {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: auto;
}

.tableInformation td, .tableInformation th {
  border: 1px solid #ddd;
  padding: 8px;
}

.tableInformation tr:nth-child(even){background-color: #f2f2f2;}

.tableInformation tr:hover {background-color: #ddd;}

.tableInformation th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
h2 { color: #ff4411; font-size: 30; font-family: 'Signika', sans-serif; padding-bottom: 10px; }
    </style>
  </head>
  <body>
    <h2 style="text-align:center">Fiche detaillée du patient</h2>
    <table class="tableInformation">
        <?php 
        // Affichage des attributs du patient 
        foreach ($patientArray as $key => $value) {
          echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
         }
        ?>
    </table>

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
        
        <h2 style="text-align:center">Upload document</h2>

        <form action="" method="post" enctype="multipart/form-data">
        Select 
        <input type="file" name="fileToUpload" id="fileToUpload">
        <select name="typeDocument">
            <option value="">Choisissez le type de document</option>
            <option value="ordonnances">Ordonnance</option>
            <option value="prescriptions">Prescription</option>
            <option value="cartesIdentites">Carte identite</option>
        </select>

        <input type="submit" value="Upload Image" name="submit">
      </form>

<div style="text-align:center; margin-top:40px">
  <a href="recherche_patient.php">Retour au formulaire</a>
</div>

  </body>
</html>