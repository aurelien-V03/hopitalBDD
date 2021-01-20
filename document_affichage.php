<?php
    // Un exemple d'url que cette page recevra
    $fileUrl = $_GET["urlDocument"];

    // affiche image ou pdf
    $doc_url_array = explode(".",$fileUrl);
    $extension_img = $doc_url_array[count($doc_url_array)-1];

    $img_to_display ="";
    if($extension_img == "pdf")
        $img_to_display = "<iframe src='".$fileUrl."' style='width:600px; height:500px; margin:auto; display:block;' frameborder='0'></iframe>";
    else
        $img_to_display = "<img id='doc_img' onclick='displayDocument()' src='".$fileUrl."'/>";
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
        border:2px black solid;
        border-radius:5px;
      }

      #doc_img:hover{
        cursor:zoom-in;
      }

      a, button{
      background-color: #008CBA; /* Green */
      border: none;
      color: white;
      padding: 5px 10px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin-top:10px;
      }

      /* Formulaire mail */
      #divFormEmail{
        width:30%;
        margin:auto;
        margin-top:30px;
          border:2px grey solid;
          padding:10px 85px;
          border-radius:5px;
      }

      #divFormEmail input[type=submit]{
        padding:5px;
      }
    </style>
  <script>
      // revenir en arriere 
      function goBack() {
        window.history.back();
      }

      // fonction pour imprimer l'image du document
      function PrintElem()
      {
      var mywindow = window.open('', 'PRINT', 'height=400,width=600');

      mywindow.document.write('<html><head><title>' + document.title  + '</title>');
      mywindow.document.write('</head><body >');

      var img = document.getElementById("doc_img").cloneNode(true);


      mywindow.document.body.appendChild(img);
      mywindow.document.write('</div></html>');

      mywindow.document.close(); // necessary for IE >= 10
      mywindow.focus(); // necessary for IE >= 10*/

      mywindow.print();
     // mywindow.close();

      return true;
    }

    // affiche l'image cliquee dans une nouvelle fenetre
    function displayDocument(){
      var mywindow = window.open('', 'PRINT', 'height=400,width=600');

      mywindow.document.write('<html><head><title>' + document.title  + '</title>');
      mywindow.document.write('</head><body >');

      var img = document.getElementById("doc_img").cloneNode(true);

      mywindow.document.body.appendChild(img);
      mywindow.document.write('</div></html>');

      mywindow.document.close(); // necessary for IE >= 10
      mywindow.focus(); // necessary for IE >= 10*/
    }
     
  </script>
  </head>
    <body>
        <!-- Affichage complet d'un document du patient (ordonnance, prescription, carte identite) -->
        <div style="text-align:center">
          <h2>Document</h2>
        </div>
        
        <!-- Affichage de l'image ou du pdf-->
        <?php 
          echo $img_to_display;
        ?>

        <!-- Formulaire envoi email-->
        <div id="divFormEmail">
              <form action="" method="POST" id="formEmail">
              <h4>Envoyer votre document par mail</h4>
              <input type="text" name="emailToSend" id="inputTextEmail"/>
              <input type="submit" value="Envoyer fichier" name="sendMail"/>
          </form>
        </div>

        <!-- bouton imprimer -->
          <div style="text-align:center">
            <button onclick="PrintElem()">Imprimer</button>

        <!-- Bouton download document -->
          <a href="<?php echo $fileUrl ?>" download>Telecharger la fiche</a>
          </div>
        
      <div style="text-align:center">
        <button onclick="goBack()" >Revenir sur votre page</button>
      </div>
  </body>

  <?php
    // envoie par mail 
    if(isset($_POST["sendMail"]) && !empty($_POST["emailToSend"])){

      $destinataire = $_POST["emailToSend"];
      $sujet = "Envoie fichier patient";
      

      $filename = $fileUrl;
      // on recupere le nom du fichier de l'url
      $array_name =  explode("/", $filename);
      $fname = $array_name[count($array_name) -1];

      $message = "Vous trouverez ci-joint votre document";

      $from = "sendmailvallet@gmail.com";
      $headers = "From: $from"; 

      // boundary 
      $semi_rand = md5(time()); 
      $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

      // headers for attachment 
      $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

      // multipart boundary 
      $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
      $message .= "--{$mime_boundary}\n";

      // preparing attachments            
          $file = fopen($filename,"rb");
          $data = fread($file,filesize($filename));
          fclose($file);
          $data = chunk_split(base64_encode($data));
          $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"".$fname."\"\n" . 
          "Content-Disposition: attachment;\n" . " filename=\"$fname\"\n" . 
          "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
          $message .= "--{$mime_boundary}--\n";
          

      if (mail($destinataire, $sujet, $message, $headers)) {
        echo "Email envoyé avec succès à $destinataire ...";
      } else {
        echo "Échec de l'envoi de l'email...";
      }
  }
    ?> 

</html>