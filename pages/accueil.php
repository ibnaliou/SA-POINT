<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "accueil";
?>
<!DOCTYPE html>
<html lang="FR-fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/MonStyle.css">
    <title>Accueil</title>
    <style>

        g text tspan {
            font-weight: bolder;
            color:orange !important;
        }
        .nonSoulign {
            text-decoration: none !important;
            
        
        }
        .margBot{
            margin-bottom:25%;
        }
        .active>.nav-link{
            background-color: #ff8200;
            border-bottom: 4px solid black;
            
        }
        .navbar-expand-lg{
                padding:0px 16px 0px 16px;
        }
    </style>
</head>

<body>
    
    <?php
    include("nav.php");
    ?>
    <header></header>
    <section class="container-fluid">
        <h1 class="textAccueil">Bienvenue sur SA-Point</h1>
        
        <?php
        // hgjh
        ///////////////////////////-------rechercher par jour---------------------//////////////////////
        echo'<form   method="POST" action="" class="monformAcc  row insc">
                <div class="col-md-3"></div>
                <div class="col-md-6 bor">';
                echo '<div class="row">
                    <div class="col-md-2"></div>
                    <input type="date" class="form-control col-md-8 espace" name="jourRech" value="';if(!isset($_POST["jourRech"])){echo date('Y-m-d');}else{echo $_POST["jourRech"];}echo'">
                </div>';
                echo '<div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Lister" name="valider">
                </div>
                </div>
            </form>';
        ///////////////////////////-------rechercher par jour---------------------//////////////////////
        ?>
        <div id="chartdiv"></div>
        <!-- ghgvj -->
        <?php
        try {
            include("connexionBDD.php");
            ///////////-----recuperation des données promo----///////////
            $codemysql = "SELECT id_promo,Nom FROM promo"; //le code mysql connexionBDD.php
            $lesPromos=recuperation($connexion,$codemysql);//lq vqriqble ]connexion se trouve dans 
            ///////////-----recuperation des données promo----///////////

            ///////////-----recuperation des données des etudiants----///////////
            $codemysql = "SELECT Nom,id_promo,NCI FROM etudiants"; //le code mysql
            $etudiants=recuperation($connexion,$codemysql);
            ///////////-----recuperation des données des etudiants----///////////

            ///////////-----recuperation des données de la table emargement----///////////
            $codemysql = "SELECT NCI,Date_emargement FROM emargement"; //le code mysql
            $emargement=recuperation($connexion,$codemysql);
            ///////////-----recuperation des données de la table emargement-----///////////
            $i=0;
            for($a=0;$a<count($lesPromos);$a++){
                $lePromo = $lesPromos[$a]["Nom"];
                $id_ref=$lesPromos[$a]["id_promo"];
                //////------compter effectif---//////
                $effectif = 0;
                for($b=0;$b<count($etudiants);$b++) {
                    $etudiant = $etudiants[$i]["Nom"];
                    $id_ref_etudiant=$etudiants[$b]["id_promo"];//la clé de son lePromo
                    if (isset($etudiant) && isset($lePromo) && $id_ref == $id_ref_etudiant) {
                        $effectif++;
                    }
                }
                //////------Fin compter effectif---//////

                ////////------compter emarger---///////
                $emarger=0;
                for($c=0;$c<count($emargement);$c++) {
                    $NCI_emarger = $emargement[$c]["NCI"];
                    $date_emargement = $emargement[$c]["Date_emargement"];
                    ///////////-----recuperation des promo des personnes qui ont emargés----///////////
                    $codemysql = "SELECT promo.Nom FROM promo INNER JOIN etudiants ON promo.id_promo=etudiants.id_promo WHERE etudiants.NCI='$NCI_emarger'"; //le code mysql
                    $le_ref_emargement=recuperation($connexion,$codemysql);
                    ///////////-----recuperation des promo des personnes qui ont emargés----///////////
                    $ref_emargement=$le_ref_emargement[0]["Nom"];
                    if (isset($ref_emargement) && isset($lePromo) && $lePromo == $ref_emargement && $date_emargement==date('Y-m-d') && !isset($_POST["valider"]) || isset($_POST["valider"]) && isset($ref_emargement) && isset($lePromo) && $lePromo == $ref_emargement && $date_emargement==$_POST["jourRech"]) {
                        $emarger++;
                    }
                }
                //////------Fin compter emarger---//////
                $absent=$effectif-$emarger;
                $i++;
                echo'<div id="present'.$i.'" class="'.$emarger.'"></div>
                    <div id="absent'.$i.'" class="'.$absent.'"></div>
                    <div id="nom_promo'.$i.'" class="'.$lePromo.'"></div>';
            }
            
            if(!isset($_POST["valider"])){
                echo'<div id="jourR" class="'.date('Y-m-d').'"></div>';
            }
            else{
                echo'<div id="jourR" class="'.$_POST["jourRech"].'"></div>';
            }
        } 
        catch (PDOException $e) {
            echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
        }

        echo "<h2 class='margBot'></h2>
        </section>";
    include("piedDePage.php");
    ?>
    <script src="../js/core.js"></script>
    <script src="../js/charts.js"></script>
    <script src="../js/animated.js"></script>
    <script src="../js/index.js"></script>
    <script src="../js/jq.js"></script><!--Non utiliser ici c'est pour la pagination mais ne pas enlever sinon il y aura une erreur dans mon js qui utilise une fonction presente ici-->
    <script src="../js/bootstrap-table-pagination.js"></script><!--Non utiliser ici c'est pour la pagination mais ne pas enlever sinon il y aura une erreur dans mon js qui utilise une fonction presente ici-->
    <script src="../js/monjs.js"></script>
</body>

</html>