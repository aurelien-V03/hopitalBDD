<?php
    // Un exemple d'url que cette page recevra
    $fileUrl = $_GET["urlDocument"];
    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <link rel="stylesheet" href="style.css">
    <style>
      #doc_img{
        width:500px;
        margin:auto;
        display:block;
        border:1px black solid;
      }
    </style>
  <script>
    // revenir en arriere 
    function goBack() {
       window.history.back();
      }
  </script>
  </head>
  <body>
  

  <!-- Affichage complet d'un document du patient (ordonnance, prescription, carte identite) -->

    <img id="doc_img" src="<?php echo $fileUrl ?>"/>
    <form action="" method="POST" id="formEmail">
        <h3>Email</h3>
        <input type="text" name="emailToSend"/>
        <input type="submit" value="Envoyer fichier" name="sendMail"/>
    </form>
    <!-- Impression du document --> 
    <!-- création de boutton imprmer--> 
    <div class="text-center">
      <button onclick="window.print()" class="btn btn-primary">imprimer</button> 
    </div>
    
      <!-- Telecharger le document -->
    <a href="<?php echo $fileUrl ?>" download>Download</a>
    <button onclick="goBack()">Revenir sur votre page</button>
  </body>
  <?php

    // envoie par mail 

    if(isset($_POST["sendMail"]) && !empty($_POST["emailToSend"])){
    $destinataire = $_POST["emailToSend"];
    $sujet = "Email de test";
    $corp = "Salut ceci est un email de test envoyer par un script PHP";
    $headers = "From: VotreGmailId@gmail.com";



    
    if (mail($destinataire, $sujet, $corp, $headers)) {
      echo "Email envoyé avec succès à $destinataire ...";
    } else {
      echo "Échec de l'envoi de l'email...";
    }
  }
    //$destinataire = "aurelienvalletcontact@gmail.com";
    //$sujet = 'un document qui vous concerne ';
    $entete = "from: ouheibitasnim99@gmail.com \n";
    $entete .= "replay-to : ouheibitasnim99@gmail.com \n";
    $delim = md5(uniqid(rand())) ;
    
    $entete = "MIMI-version: 1.0 \n";
    $entete .= "content-Type : multipart/mixed;boundary=\"$delim\"\n";
    $entete .="\n";
    $msg  ="--$delim \n";
    $msg .= "content-Type: text/plain; charset=\"utf-8\"\n";
    $msg .="content-Transfer-encoding: 8bit \n" ; 
    $msg .="\n" ;

    $msg = "document qui vous concerne " ; 
    $fichier ='files/ordonnances/ordonnanceDUPONTveronique1.jpg';
    $jointe = file_get_contents($fichier);
    $jointe = chunk_split(base64_decode($jointe));
    $msg .="--$delim--\n" ; 
    $msg .="content-Type: image/jpeg ; name\"image\" \n" ; 
    $msg .="content-Transfer-encoding: base64 \n" ; 
    $msg .="content-Disposition : inline ;filename=\"image\"\n";
    $msg .="\n" ;
    $msg .=$jointe ."\n";
    $msg .="\n" ;

    $msg .="--$delim--\n" ;
   // mail ($destinataire,$sujet,$msg,$entete);
    ?> 

    <!-- telechargement de document-->
    <?php
    /*
    $req = $db->query('SELECT name,file_url From  document');
    while ($data = getMysqlConnection()->fetch()){
      echo $data ['data'].':'.'<a href="'.$data['file_url'].'">telecharger ';$data['name'].'</a>' ;
    }
    */
    ?>





</html>