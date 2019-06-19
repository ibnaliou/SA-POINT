<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "stat";
if (!isset($_GET["code"])) {
    header('Location: presence.php');
    exit();
}
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
    body{
        background-color: #222222;
        background-image: none;
    }
    .statt {
        margin-top: 2%;
        margin-bottom: 5%;
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
        <?php
        try {
            include("connexionBDD.php");

            ///////////-----recuperation le nom de l'etudiant----///////////
            $NCI=$_GET["code"];
            $codemysql = "SELECT Nom FROM etudiants WHERE NCI='$NCI'"; //le code mysql
            $etudiant=recuperation($connexion,$codemysql);
            ///////////-----recuperation le nom de l'etudiant-----///////////

            echo'<h1 class="textAccueil">'.$etudiant[0]["Nom"].'</h1>';
        ?>
        <div id="chartdiv" class="statt"></div>
        <?php
        $j=0;
            ///////////-----recuperation des données de la table emargement----///////////
            $codemysql = "SELECT * FROM emargement ORDER BY Date_emargement"; //le code mysql
            $emargement=recuperation($connexion,$codemysql);
            ///////////-----recuperation des données de la table emargement-----///////////
            for($i=0;$i<count($emargement);$i++) {
                if(isset($_GET["code"]) && $_GET["code"]==$emargement[$i]["NCI"]){
                    $j++;
                echo'<div id="jour'.$j.'" class="'.$emargement[$i]["Date_emargement"].'"></div>
                     <div id="arrivee'.$j.'" class="'.$emargement[$i]["Arrivee"].'"></div>
                     <div id="depart'.$j.'" class="'.$emargement[$i]["Depart"].'"></div>';

                }
            }
            echo'<div id="jourPresent" class="'.$j.'"></div>';
        ?>
    </section>   
    <?php
        } 
        catch (PDOException $e) {
            echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
        }
    include("piedDePage.php");
    ?>
    <script src="../js/core.js"></script>
    <script src="../js/charts.js"></script>
    <script src="../js/animated.js"></script>
    <script src="../js/dark.js"></script>
    <script src="../js/stat.js"></script>
</body>

</html>