<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "exportation";
?>

<!DOCTYPE html>
<html lang="FR-fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/MonStyle.css">
    <title>Exportation</title>
    <style>
        .entrBouton{
            margin-left:3%;
        }
        .l2p{
            margin-left:2%;
        }
        .l3p{
            margin-left:3%;
        }
        label{
            font-style: italic;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bolder;
        }
        .mesTitres{
            font-style: italic;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bolder;
            text-decoration: underline dotted black;
        }
        .espace{
            margin-top: 5%;
        }
        .MonForm {
            margin-top: 7%;
        }
        .bor{
            background-color: #ff8200;
            border: 2px solid #008e8e;
        }
        .active>.nav-link{
            background-color: #ff8200;
            border-bottom: 4px solid black;
        }
        .navbar-expand-lg{
            padding:0px 16px 0px 16px;
        }
        .lesCodes{
            margin-top:1%;
        }
    </style>
</head>

<body>
    <?php
    include("nav.php");
    ?>
    <header></header>
    <section class="container-fluid cAuth">
    <?php
        try {
            include("connexionBDD.php");
            ///////////-----recuperation des promo----///////////
            $codemysql = "SELECT * FROM promo"; //le code mysql
            $promo=recuperation($connexion,$codemysql);
            ///////////-----Fin recuperation des promo----///////////
            if(!isset($_SESSION["nombre_em"])){
                $_SESSION["nombre_em"]=0;
            }
    ?> 
        <div class="MonForm row insc">
            <div class="col-md-1"></div>
            <div class="col-md-10 bor">
                <form method="POST" action="../pdf/Mes_PDF/pdf_etudiants.php" target="_blank" class="">
                    <legend class="mesTitres">Apprenants</legend>
                    <div class="row">
                        <label for="" class="col-md-2 l2p" >Référentiel</label>
                    </div>
                    <div class="row">
                        <select name="ref_ap" class="form-control col-md-2  entrBouton" id="">
                            <option value="tous">Tous les référentiels</option>
                            <?php
                            for($i=0;$i<count($promo);$i++){
                                echo'<option value="'.$promo[$i]["Nom"].'">'.$promo[$i]["Nom"].'</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" class="btn btn-outline-success col-md-1  entrBouton" value="PDF" name="pdf_ap" id="pdf_ap">
                     </div> 
                </form>
                <form method="POST" action="../pdf/Mes_PDF/pdf_emargement.php" <?php if(!isset($_GET["Noms"])){echo ' target="_blank" ';}?>class="espace">
                <!-- <form method="POST" action="" target="" class="espace"> -->
                    <legend class="mesTitres">Emargement</legend>
                     <div class="row">
                        <label for="" class="col-md-2 l2p" >Référentiel</label>
                        <label for="" class="col-md-3 l3p"  name="">Nom apprenant</label>
                        <label for="" class="col-md-2 l3p"  id="lab_dd_em">Date début</label>
                        <label for="" class="col-md-2 l3p"  id="lab_df_em">Date fin</label>
                    </div>
                    <div class="row">
                        <select name="ref_em" class="form-control col-md-2  entrBouton" id="">
                            <option value="tous">Tous les référentiels</option>
                            <?php
                            for($i=0;$i<count($promo);$i++){
                                if(isset($_GET["ref_e"]) && $_GET["ref_e"]==$promo[$i]["Nom"]){
                                    echo'<option value="'.$promo[$i]["Nom"].'" selected>'.$promo[$i]["Nom"].'</option>';
                                }
                                else{
                                    echo'<option value="'.$promo[$i]["Nom"].'">'.$promo[$i]["Nom"].'</option>';
                                }
                            }
                            ?>
                        </select>
                        <input type="text" class="form-control col-md-3 entrBouton" placeholder="Nom de l'apprenant"  name="nom_em" <?php if(isset($_GET["Noms"])){echo 'value="'.$_GET["Noms"].'"' ;} ?>>
                        <input type="date" class="form-control col-md-2 entrBouton" name="date_debut_em" id="dd_em" <?php if(isset($_GET["date_deb"])){echo ' value="'.$_GET["date_deb"].'"' ;} ?>>
                        <input type="date" class="form-control col-md-2 entrBouton" name="date_fin_em" id="df_em" <?php if(isset($_GET["date_fin"])){echo ' value="'.$_GET["date_fin"].'"' ;} ?>>
                        <input type="submit" class="btn btn-outline-success col-md-1 entrBouton" value="PDF" name="pdf_em" id="pdf_em">
                    </div>
                    <!--//////////////////////////----------Si plusieurs portent le même nom----------////////////////////////-->
                    <?php if ($_SESSION["nombre_em"]>1 && isset($_GET["Noms"])) {
                    echo'<script>alert("Plusieurs personnes portent le nom '.$_GET["Noms"].', veuillez choisir son numéro de carte d\'identité.");</script>';?>
                    <div class="row lesCodes">
                        <div class="col-md-2  entrBouton"></div>
                        <select name="nci_em" class="form-control col-md-3  entrBouton rougMoins" id="">
                            <?php
                            $nom_em=$_GET["Noms"];
                            ///////////-----recuperation des données des etudiants----///////////
                            $codemysql = "SELECT NCI FROM etudiants WHERE Nom='$nom_em'"; //le code mysql
                            $nci_etu=recuperation($connexion,$codemysql);
                            ///////////-----Fin recuperation des données des etudiants----///////
                            for($i=0;$i<count($nci_etu);$i++){
                                echo'<option value="'.$nci_etu[$i]["NCI"].'">'.$nci_etu[$i]["NCI"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                    <!--//////////////////////////----------Fin Si plusieurs portent le même nom----------////////////////////////-->
                </form> 
                <form method="POST" action="../pdf/Mes_PDF/pdf_visiteurs.php" target="_blank" class="espace">
                    <legend class="mesTitres">Visiteurs</legend>
                    <div class="row">
                        <label for="" class="col-md-2 l3p"  name="date_visiteur" id="lab_dd_vi">Date début</label>
                        <label for="" class="col-md-2 l3p"  name="date_visiteur" id="lab_df_vi">Date fin</label>
                    </div>
                    <div class="row">
                        <input type="date" class="form-control col-md-2 entrBouton"  name="date_debu_visiteur" id="dd_vi">
                        <input type="date" class="form-control col-md-2 entrBouton"  name="date_fin_visiteur" id="df_vi">
                        <input type="submit" class="btn btn-outline-success col-md-1 entrBouton" value="PDF" name="pdf_visiteur" id="pdf_vi">
                    </div>
                </form> 
            </div>
        </div>
    <?php
            echo'<div class="bas"></div>';
    ?>
    
    <?php
        }
        catch (PDOException $e) {
            echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
        }
    ?>
        
    </section>
    <?php
    include("piedDePage.php");
    if(isset($_GET["fichier_vide"])){
        echo'<script>
        alert("Désoler, aucune donnée disponible !"); 
        window.close();
        </script>';
    }
    ?>
    
    <script src="../js/jq.js"></script>
    <script src="../js/bootstrap-table-pagination.js"></script>
    <script src="../js/monjs.js"></script>
</body>

</html>