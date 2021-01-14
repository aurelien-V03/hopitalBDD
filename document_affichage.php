<?php
    // Un exemple d'url que cette page recevra
    $fileUrl = "files/ordonnances/ordonnanceDUPONTjean1.jpg";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  

  <!-- Affichage complet d'un document du patient (ordonnance, prescription, carte identite) -->


    <img src="<?php echo $fileUrl ?>"/>


  </body>
</html>