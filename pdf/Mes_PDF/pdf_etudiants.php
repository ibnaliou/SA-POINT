<?php
require('../fpdf.php');
try {
    $tout="";
    $monfichier=fopen("../Mes_fichiers_texte/etudiants.txt","w");
    fwrite($monfichier,trim($tout));
    fclose($monfichier);
    $tout="";
    include("../../pages/connexionBDD.php");
    
    ///////////-----recuperation de tous les etudiants----///////////
    $codemysql = "SELECT * FROM etudiants ORDER BY id_promo"; //le code mysql
    ///////////-----Fin recuperation de tous les etudiants----///////
    
    if($_POST["ref_ap"]!="tous" ){
        $nom_ref=$_POST["ref_ap"];
        ///////////-----recuperation id ref----///////////
        $codemysql = "SELECT promo.id_promo FROM promo WHERE Nom='$nom_ref'"; //le code mysql
        $id_des_ref=recuperation($connexion,$codemysql);
        ///////////-----Fin recuperation id ref----///////

        $id_ref=$id_des_ref[0]["id_promo"];
        ///////////-----recuperation des données des etudiants----///////////
        $codemysql = "SELECT * FROM etudiants WHERE id_promo='$id_ref'"; //le code mysql
        ///////////-----Fin recuperation des données des etudiants----///////
        $validation=true;
    }
    $etudiants=recuperation($connexion,$codemysql);

   
    for($i=0;$i<count($etudiants);$i++) {
        $id_ref=$etudiants[$i]["id_promo"];
        ///////////-----recuperation des données des etudiants----///////////
        $codemysql = "SELECT promo.Nom FROM promo WHERE id_promo='$id_ref'"; //le code mysql
        $nom_ref=recuperation($connexion,$codemysql);
        ///////////-----Fin recuperation des données des etudiants----///////
        $tout=$tout.$etudiants[$i]["NCI"].";".$nom_ref[0]["Nom"].";".$etudiants[$i]["Nom"].";".$etudiants[$i]["Naissance"].";".$etudiants[$i]["Telephone"].";".$etudiants[$i]["Email"].";\n";
        $monfichier=fopen("../Mes_fichiers_texte/etudiants.txt","w");
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
            $w = array(25, 25, 52, 18,18,55);//modifier le nombre d'élement max 190
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
            for($données=0;$données<=5;$données++){
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
$header = array('NCI', 'Ref', 'Nom', 'Naissance',"Telephone","Email");
// Chargement des données
$data = $pdf->LoadData('../Mes_fichiers_texte/etudiants.txt');
$pdf->SetFont('Arial','',8);
$pdf->AddPage();

$pdf->FancyTable($header,$data);
$pdf->Output();
?>