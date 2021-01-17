<?php   
// Classe qui represente un document (ordonnance, prescription, carte identite) d'un patient
class Document{
    public $id;
    public $filePath;
    public $urlFormat;
    public $dateCreation;
    public $libelle;

    // constructeur
    function __construct($id, $filePath, $urlFormat, $dateCreation, $libelle)
    {
        $this->id = $id;
        $this->filePath = $filePath;
        $this->urlFormat = $urlFormat;
        $this->dateCreation = $dateCreation;
        $this->libelle = $libelle;
    }

    // retourne HTML formate pour une utilisation dans un tableau dans une balise <tr></tr>
    function getRowTable(){
        $row = "<td><a href='document_affichage.php?codeDocument=".$this->id."'>".$this->getNameOfFile()."</a></td>";
        $row .= "<td>".$this->dateCreation."</td>";
        $row .= "<td>".$this->libelle."</td>";
        return $row;
    }

    // retourne le nom du fichier sans son chemin
    function getNameOfFile(){
        $filePathCut = explode("/",$this->filePath);
        return $filePathCut[count($filePathCut)-1];
    }
}

// retourne la connection MySQL
    function getMysqlConnection(){
    try{
        $user = "user1";
        $pass = "hcetylop";

        $bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8', $user, $pass);
        
        return $bdd;
        }
        catch(Exception $e)
        {
        die('Erreur connexion MySQL : ' . $e->getMessage());
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

            // formatage du lien HTML <a></a>
            $urlPatient = "fiche_patient.php?codePatient=".$CodePatient."&nom=".$MAJnom."&prenom=".$prenom;
            $htmlPatient = "<a href='".$urlPatient  ."'>". $MAJnom." ". $prenom."</a>";

            array_push($patientArray, $htmlPatient);
        }
        return $patientArray;
    }



    // prend en parametre l'id du patient et lui revoie un tableau contenant 1 tableau pour chaque type de document
    function getPatientDocuments($patientId){
        $array_document = array(
            "Ordonnance" => array(),
            "Prescription" => array(),
            "Carte identite" => array()
        );

        $requete = "SELECT idOrdonnance, idPatient, typeDocument, filePath, urlFormat, dateCreation, td.libelle FROM document d, typeDocument td WHERE d.typeDocument = td.idTypeDocument AND idPatient = " .$patientId;

        $requestDocuments = getMysqlConnection()->prepare($requete);
        $requestDocuments->execute();

     
        while($row = $requestDocuments->fetch())
        {
            $documentOfPatient = new Document($row["idOrdonnance"],$row["filePath"],$row["urlFormat"],$row["dateCreation"],$row["libelle"]);


           $typeDocument = intval($row["typeDocument"]);
           switch($typeDocument)
            {
                case 1:
                    array_push($array_document["Ordonnance"],$documentOfPatient);
                    break;
                case 2:
                    array_push( $array_document["Prescription"],$documentOfPatient);
                    break;
                case 3:
                    array_push( $array_document["Carte identite"],$documentOfPatient);
                     break;
            }
        }
        return $array_document;
    }
   

?>