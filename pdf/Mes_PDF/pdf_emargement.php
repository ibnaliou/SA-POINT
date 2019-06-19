<?php
session_start();
require('../fpdf.php');
try {
    $tout="";
    $monfichier=fopen("../Mes_fichiers_texte/emargement.txt","w");
    fwrite($monfichier,trim($tout));
    fclose($monfichier);

    $tout="";
    $nbr=0;
    include("../../pages/connexionBDD.php");

    $ladate_depart=$_POST["date_debut_em"];
    $ladate_fin=$_POST["date_fin_em"];
    $nom_em=$_POST["nom_em"];
    $ref_em=$_POST["ref_em"];
    if($nom_em!=""){
        if($ref_em!="tous"){
            ///////////-----recuperation des données des etudiants----///////////
            // // $codemysql = "SELECT etudiants.NCI FROM etudiants INNER JOIN promo ON etudiants.id_promo=promo.id_promo WHERE etudiants.Nom='$nom_em' AND promo.Nom='.$ref_em.'"; //le code mysql
            // $nci_etu=recuperation($connexion,$codemysql);
            ///////////-----Fin recuperation des données des etudiants----///////

            // ///////////-----recuperation ref etudiants----///////////
            $codemysql = "SELECT id_promo FROM promo WHERE Nom='$ref_em'"; //le code mysql
            $id_r=recuperation($connexion,$codemysql);
            // ///////////-----Fin recuperation ref etudiants----///////

            $id_r=$id_r[0]["id_promo"];
            ///////////-----recuperation des données des etudiants----///////////
            $codemysql = "SELECT NCI FROM etudiants WHERE Nom='$nom_em' AND id_promo='.$id_r.'"; //le code mysql
            $nci_etu=recuperation($connexion,$codemysql);
            // ///////////-----Fin recuperation des données des etudiants----///////
        }
        else{
            ///////////-----recuperation des données des etudiants----///////////
            $codemysql = "SELECT NCI FROM etudiants WHERE Nom='$nom_em'"; //le code mysql
            $nci_etu=recuperation($connexion,$codemysql);
            ///////////-----Fin recuperation des données des etudiants----///////
        }
        for($i=0;$i<count($nci_etu);$i++){//connaist si plusieurs personnes n'ont pas le même nom
            $lesnci=$nci_etu[$i]["NCI"];
            $nbr++;
        } 
        if( $nbr>1 && !isset($_POST["nci_em"])){//s'il son plusieurs repartir et demander le NCI
            $_SESSION["nombre_em"]=$nbr;
            header('Location: ../../pages/exportation.php?Noms='.$nom_em.'&ref_e='.$_POST["ref_em"].'&date_deb='.$_POST["date_debut_em"].'&date_fin='.$_POST["date_fin_em"].'');
            exit();
        }
        unset($_SESSION["nombre_em"]);//detruire la variable session pour recacher les NCI sur notre page
        if(!isset($_POST["nci_em"])){//car si il existe ca veut dire qu'ils sont plusieurs et qu'ont a choisi un NCI
            if(isset($nci_etu[0]["NCI"])) 
                $nci=$nci_etu[0]["NCI"];
            else 
                $nci="";//il n'existe pas
        }
        else{
            $nci=$_POST["nci_em"];
        }
    }
    if($ref_em!="tous"){
        ///////////-----recuperation des promo des personnes qui ont emargés----///////////
        $codemysql = "SELECT id_promo FROM promo WHERE Nom='$ref_em'"; //le code mysql
        $id_ref=recuperation($connexion,$codemysql);
        ///////////-----Fin recuperation des promo des personnes qui ont emargés----///////////
        $id_ref=$id_ref[0]["id_promo"];
    }
    /////////-----recuperation des données des etudiants----///////////
    if(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin=="" && $nom_em=="" && $ref_em=="tous") {//1
        $codemysql = "SELECT * FROM emargement WHERE Date_emargement>='$ladate_depart' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin!="" && $nom_em=="" && $ref_em=="tous"){//2
        $codemysql = "SELECT * FROM emargement WHERE Date_emargement<='$ladate_fin' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin=="" && $nom_em!="" && $ref_em=="tous"){//3
        $codemysql = "SELECT * FROM emargement WHERE NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin=="" && $nom_em=="" && $ref_em=="tous"){//4
      $codemysql = "SELECT * FROM emargement ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin=="" && $nom_em=="" && $ref_em!="tous"){//4
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI WHERE etudiants.id_promo='$id_ref' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin!="" && $nom_em=="" && $ref_em=="tous"){//12
        $codemysql = "SELECT * FROM emargement WHERE Date_emargement>='$ladate_depart' AND Date_emargement<='$ladate_fin' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin=="" && $nom_em!="" && $ref_em=="tous"){//13
        $codemysql = "SELECT * FROM emargement WHERE Date_emargement>='$ladate_depart' AND NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin=="" && $nom_em=="" && $ref_em!="tous"){//14
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI WHERE emargement.Date_emargement>='$ladate_depart' AND etudiants.id_promo='$id_ref' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin!="" && $nom_em!="" && $ref_em=="tous"){//123
        $codemysql = "SELECT * FROM emargement WHERE Date_emargement>='$ladate_depart' AND Date_emargement<='$ladate_fin' AND NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin!="" && $nom_em=="" && $ref_em!="tous"){//124
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI WHERE emargement.Date_emargement>='$ladate_depart' AND Date_emargement<='$ladate_fin' AND etudiants.id_promo='$id_ref' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin!="" && $nom_em!="" && $ref_em!="tous"){//1234
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI  WHERE emargement.Date_emargement>='$ladate_depart' AND Date_emargement<='$ladate_fin' AND etudiants.id_promo='$id_ref'  AND etudiants.NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin!="" && $nom_em!="" && $ref_em!="tous"){//234
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI  WHERE emargement.Date_emargement<='$ladate_fin' AND etudiants.id_promo='$id_ref'  AND etudiants.NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart!="" && $ladate_fin=="" && $nom_em!="" && $ref_em!="tous"){//134
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI WHERE emargement.Date_emargement>='$ladate_depart' AND etudiants.id_promo='$id_ref' AND etudiants.NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
     elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin!="" && $nom_em=="" && $ref_em!="tous"){//24
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI WHERE emargement.Date_emargement<='$ladate_fin' AND etudiants.id_promo='$id_ref' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin!="" && $nom_em!="" && $ref_em=="tous"){//23
        $codemysql = "SELECT * FROM emargement WHERE Date_emargement<='$ladate_fin' AND NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
    elseif(isset($_POST["pdf_em"]) && $ladate_depart=="" && $ladate_fin=="" && $nom_em!="" && $ref_em!="tous"){//34
        $codemysql = "SELECT * FROM emargement INNER JOIN etudiants ON emargement.NCI=etudiants.NCI  WHERE etudiants.id_promo='$id_ref'  AND etudiants.NCI='$nci' ORDER BY emargement.Date_emargement ASC";
    }
    else{
        $codemysql = "SELECT * FROM emargement ORDER BY Date_emargement ASC";
    }
    $emargement=recuperation($connexion,$codemysql);
    ///////////-----Fin recuperation des données des etudiants----///////
    for($i=0;$i<count($emargement);$i++) {
        $nci=$emargement[$i]["NCI"];

        ///////////-----recuperation des données des etudiants----///////////
        $codemysql = "SELECT etudiants.Nom,etudiants.id_promo FROM etudiants WHERE NCI='$nci'"; //le code mysql
        $etudiants=recuperation($connexion,$codemysql);
        ///////////-----Fin recuperation des données des etudiants----///////
        
        $id_ref=$etudiants[0]["id_promo"];

        ///////////-----recuperation des données des etudiants----///////////
        $codemysql = "SELECT promo.Nom FROM promo WHERE id_promo='$id_ref'"; //le code mysql
        $nom_ref=recuperation($connexion,$codemysql);
        ///////////-----Fin recuperation des données des etudiants----///////

        $tout=$tout.$emargement[$i]["NCI"].";".$nom_ref[0]["Nom"].";".$etudiants[0]["Nom"].";".$emargement[$i]["Date_emargement"].";".$emargement[$i]["Arrivee"].";".$emargement[$i]["Depart"].";".$emargement[$i]["Code_agents_arrivee"].";".$emargement[$i]["Code_agents_depart"].";\n";
        $monfichier=fopen("../Mes_fichiers_texte/emargement.txt","w");
        fwrite($monfichier,trim($tout));
        fclose($monfichier);
    }
    if($tout==""){
         header('Location: ../../pages/exportation.php?fichier_vide=true');
    }
}
catch (PDOException $e) {
    echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
} 

function pour_conversion($value){//pour consersion en utf-8
    $value = mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
    return $value;
}
class PDF extends FPDF
{
    // Chargement des données
    function LoadData($file){
        // Lecture des lignes du fichier
        $lines = file($file);
        $data = array();
        foreach($lines as $line)
            $data[] = explode(';',trim($line));
        return $data;
    }

    // Tableau coloré
    function FancyTable($header, $data)
    {
        // Données
        $fill = false;
        $a=0;
        foreach($data as $row)
        {
            $w = array(27, 28, 55, 22,17,17,14,14);//modifier le nombre d'élement max 190
            if($a!=0 && $a%43==0){
                $this->Cell(array_sum($w),0,' ','T');//tracer jusqu'a la fin
                $this->Cell(-array_sum($w),0,' ','');//revenir à la ligne
            }
                
            if($a==0||$a%43==0){
                // Couleurs, épaisseur du trait et police grasse
                $this->SetFillColor(0,123,255);
                $this->SetTextColor(255);
                $this->SetDrawColor(128,0,0);
                $this->SetLineWidth(.3);
                $this->SetFont('','B');
                // En-tête
                
                
                for($i=0;$i<count($header);$i++){
                    $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
                }
                $this->Ln();
                
                // Restauration des couleurs et de la police
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->SetFont('');
            }
            
            $a++;
            for($données=0;$données<=7;$données++){
                $this->Cell($w[$données],6,pour_conversion($row[$données]),'LR',0,'L',$fill);
            } 
            $this->Ln();
            $fill = !$fill;
        }
        // Trait de terminaison
        $this->Cell(array_sum($w),0,'','T');
    }
}

$pdf = new PDF();
// Titres des colonnes
$header = array('NCI', 'Ref', 'Nom', 'Date',"Arrive","Depart","Agent 1","Agent 2");
// Chargement des données
$data = $pdf->LoadData('../Mes_fichiers_texte/emargement.txt');
$pdf->SetFont('Arial','',8);
$pdf->AddPage();

$pdf->FancyTable($header,$data);
$pdf->Output();
?>