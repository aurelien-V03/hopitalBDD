<?php

function getMysqlConnection(){
try{
    $user = "user1";
    $pass = "hcetylop";
    $bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8', 'root', '');
return $bdd;
     }
    catch(Exception $e)
    {
    die('Erreur : ' . $e->getMessage());
    }
}
    // Fonction qui prend en parametre une requete MySQL et qui retourne un tableau 
    // contenant des liens html du client ou des clients de la forme <a href="fiche_patient.php?codePatient=XX">NOM prenom</a>
    function createPatientArray($requete){
        $patientArray = array();

        $requestPatient = getMysqlConnection()->prepare($requete);
        $requestPatient->execute();

        while($row = $requestPatient->fetch())
        {
            $MAJnom = strtoupper($row["Nom"]);
            $prenom = $row["Prenom"];
            $CodePatient = strval($row["Code"]);

            $urlPatient = "fiche_patient.php?codePatient=".$CodePatient;
            $htmlPatient = "<a href='".$urlPatient  ."'>". $MAJnom." ". $prenom."</a>";
            array_push($patientArray, $htmlPatient);
        }


        return $patientArray;
    }
   

?>