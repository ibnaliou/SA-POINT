<?php
require('../fpdf.php');
try {
    $tout="";
    $monfichier=fopen("../Mes_fichiers_texte/visiteurs.txt","w");
    fwrite($monfichier,trim($tout));
    fclose($monfichier);
    $ladate_depart=$_POST["date_debu_visiteur"];
    $ladate_fin=$_POST["date_fin_visiteur"];

    $tout="";
    include("../../pages/connexionBDD.php");
    /////////-----recuperation des données des etudiants----///////////
    if(isset($_POST["pdf_visiteur"]) && $_POST["date_debu_visiteur"]!="" && $_POST["date_fin_visiteur"]=="") {
        $codemysql = "SELECT * FROM visiteurs WHERE Date>='$ladate_depart' ORDER BY Date ASC";
    }
    elseif(isset($_POST["pdf_visiteur"]) && $_POST["date_debu_visiteur"]!="" && $_POST["date_fin_visiteur"]!="") {
        $codemysql = "SELECT * FROM visiteurs WHERE Date>='$ladate_depart' AND Date<='$ladate_fin' ORDER BY Date ASC";
    }
    elseif(isset($_POST["pdf_visiteur"]) && $_POST["date_debu_visiteur"]=="" && $_POST["date_fin_visiteur"]!="") {
        $codemysql = "SELECT * FROM visiteurs WHERE Date<='$ladate_fin' ORDER BY Date ASC";
    }
    else{
        $codemysql = "SELECT * FROM visiteurs ORDER BY Date ASC";
    }
    $visiteurs=recuperation($connexion,$codemysql);
    ///////////-----Fin recuperation des données des etudiants----///////
    for($i=0;$i<count($visiteurs);$i++) {
        $tout=$tout.$visiteurs[$i]["id_visiteurs"].";".$visiteurs[$i]["Nom"].";".$visiteurs[$i]["Date"].";".$visiteurs[$i]["Heure"].";".$visiteurs[$i]["Telephone"].";".$visiteurs[$i]["Email"].";".$visiteurs[$i]["Code_agents"].";\n";
        $monfichier=fopen("../Mes_fichiers_texte/visiteurs.txt","w");
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
            $w = array(15, 50, 20,15, 22,50,15);//modifier le nombre d'élement max 190
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
            for($données=0;$données<=6;$données++){
                $this->Cell($w[$données],6,pour_conversion($row[$données]),'LR',0,'C',$fill);
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
$header = array('id','Nom','Date','Heure',"Telephone","Email","Agent");
// Chargement des données
$data = $pdf->LoadData('../Mes_fichiers_texte/visiteurs.txt');
$pdf->SetFont('Arial','',9);
$pdf->AddPage();

$pdf->FancyTable($header,$data);
$pdf->Output();
?>