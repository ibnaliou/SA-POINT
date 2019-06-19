<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "ModifierPromo";
?>
<!DOCTYPE html>
<html lang="FR-fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/MonStyle.css">
    <title>Gestion des promos</title>
</head>
<style>
    .active>.nav-link{
        background-color: #ff8200;
        border-bottom: 4px solid black;
    }
    .navbar-expand-lg{
        padding:0px 16px 0px 16px;
    }
    .boutList{
        width:60%;
    }
    .entrebou{
        margin-right:0.2%;
    }
</style>
<body>
    <?php
    include("nav.php");
    ?>
    <header></header>
    <section class="container-fluid cAuth">
        <form method="POST" action="" class="MonForm row insc">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <?php
                $existeDeja = false;
                $promoDejaAjouter = false;
                $nepasModif = false;
                $tableVide=true;
                try {
                    include("connexionBDD.php");
                    ///////////-----recuperation des promo----///////////
                    $codemysql = "SELECT * FROM promo"; //le code mysql
                    $promos=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des promo----///////////

                    /////////////////--Debut contenu fichier--//////////
                    if(isset($promos[0][1])){
                        $tableVide=false;
                    }
                    /////////////////--Fin contenu fichier--////////////

                    if (isset($_POST["premierValidation"]) || isset($_POST["AjouterFin"]) || isset($_POST["valider"])) {
                        for($i=0;$i<count($promos);$i++){

                            if ($tableVide==false && strtolower($promos[$i]["Nom"]) == strtolower($_POST["nom"])) {
                                $_POST["nom"] = $promos[$i]["Nom"]; //pouvoir utiliser le bon nom
                                $ancdebut_promo = $promos[$i]["debut_promo"];
                                $ancfin_promo = $promos[$i]["fin_promo"];
                                $anc_id_promo=$promos[$i]["id_promo"];
                                $existeDeja = true;
                            }
                        }
                    }
                    if (isset($_POST["AjouterFin"]) && $existeDeja == true) {
                        $promoDejaAjouter = true;
                    }
                    if (isset($_POST["valider"]) && $existeDeja == false) {
                        $nepasModif = true; //si on veut modifier alors qu'il n existe pas
                    }
                    ///////////////////////////////////////-------Nom------////////////////////
                    echo '<div class="row">
                        <div class="col-md-2"></div>
                        <input  type="text" id="nom" name="nom" '; if(isset($_POST["premierValidation"]) && $existeDeja == true){echo' readonly="readonly"  ';}
                    if (isset($_POST["premierValidation"]) || isset($_POST["Ajouter"]) || $nepasModif == true) {
                        if (empty($_POST["nom"]) && !isset($_POST["Ajouter"]) || $nepasModif == true) {
                            echo ' class="form-control col-md-8 espace rougMoins" placeholder= "Entrez le nom de la promo à modifier !"';
                        } 
                        else {
                            if ($existeDeja == false && !isset($_POST["Ajouter"])) {
                                echo ' class="form-control col-md-8 espace rougMoins" placeholder= "La promo ' . $_POST["nom"] . ' n\'existe pas"';
                            } 
                            elseif ($existeDeja == true || isset($_POST["Ajouter"]) && !empty($_POST["nom"])) {
                                echo ' placeholder="Nom de la promo" class="form-control col-md-8 espace" value="' . $_POST["nom"] . '"';
                            } 
                            else {
                                echo ' placeholder="Nom de la promo" class="form-control col-md-8 espace" ';
                            }
                        }
                    } 
                    elseif ($promoDejaAjouter == true) {
                        echo ' class="form-control col-md-8 espace rougMoins" placeholder= "Cette promo existe déja !"';
                    } 
                    elseif (isset($_POST["AjouterFin"]) && empty($_POST["nom"]) || isset($_POST["valider"]) && empty($_POST["nom"])) { //si on enregistre alors que le nom est vide
                        echo ' class="form-control col-md-8 espace rougMoins" placeholder= "Entrez le nom de la promo !"';
                    } 
                    elseif (isset($_POST["AjouterFin"]) && empty($_POST["nom"])  || isset($_POST["valider"]) && empty($_POST["nom"])) { //si on enregistre alors que le nom n'etait pas vide on y remet sa valeur
                        echo ' class="form-control col-md-8 espace " placeholder= "Nom de la promo" value="' . $_POST["nom"] . '" ';
                    } 
                    else {
                        echo ' placeholder="Nom de la promo" class="form-control col-md-8 espace" ';
                    }
                    echo '>
                    </div>';
                    ///////////////////////////////////////-------Nom------/////////////////////////


                    if (isset($_POST["premierValidation"]) && $existeDeja == true && !empty($_POST["nom"]) || isset($_POST["Ajouter"]) || isset($_POST["AjouterFin"]) && empty($_POST["nom"]) || $promoDejaAjouter == true || isset($_POST["valider"]) && empty($_POST["nom"])) {
                        if (!isset($_POST["premierValidation"])){
                        ///////////-----recuperation des promo----///////////
                        $codemysql = "SELECT Nom FROM referentiels"; //le code mysql
                        $les_reff=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des promo----///////////
                        echo '<div class="row">
                            <div class="col-md-2"></div>
                             <select class="form-control col-md-8 espace" name="ref">';
                            
                            for($i=0;$i<count($les_reff);$i++) {
                                if ($ref == $les_reff[$i]["Nom"]) {
                                    echo '<option value="' . $les_reff[$i]["Nom"]. '" selected>' . $les_reff[$i]["Nom"] . '</option>';
                                } 
                                else {
                                    echo '<option value="' . $les_reff[$i]["Nom"] . '">' . $les_reff[$i]["Nom"] . '</option>';
                                }
                            }
                            
                            echo '</select>
                        </div>';
                        }
                        ///////////////////////////////////////-------debut_promo------////////////////////
                        
                        echo '<div class="row">
                            <div class="col-md-2"></div>
                            <label class="form-control col-md-4 espace entrebou" >Début</label>
                            <input type="date" name="debut_promo"';
                            if(isset($_POST["Ajouter"])){
                                echo' class="form-control col-md-4 espace" value="'.date("Y-m-d").'"> ';
                            }
                             
                            elseif(isset($_POST["premierValidation"])){
                                echo' class="form-control col-md-4 espace" value="'.$ancdebut_promo.'"> ';
                            }
                            elseif(isset($_POST["AjouterFin"]) && $promoDejaAjouter == true){
                                echo' class="form-control col-md-4 espace" value="'.$_POST["debut_promo"].'"> ';
                            }
                        echo' </div>';

                        ///////////////////////////////////////-------debut_promo------////////////////////


                        ///////////////////////////////////////-------Année fin------////////////////////
                        echo '<div class="row">
                            <div class="col-md-2"></div>
                            <label class="form-control col-md-4 espace entrebou" >Fin</label>
                            <input type="date" name="fin_promo"';
                            if(isset($_POST["Ajouter"])){
                                echo' class="form-control col-md-4 espace" value="'.date("Y-m-d").'"> ';
                            }
                            elseif(isset($_POST["premierValidation"])){
                                echo' class="form-control col-md-4 espace" value="'.$ancfin_promo .'"> ';
                            }
                            elseif(isset($_POST["AjouterFin"]) && $promoDejaAjouter == true){
                                echo' class="form-control col-md-4 espace" value="'.$_POST["fin_promo"].'"> ';
                            }
                        
                        echo '
                        </div>';
                        ///////////////////////////////////////-------Année fin------////////////////////

                    }
                    ?>
                    <div class="row">

                        <?php

                        if (isset($_POST["Ajouter"]) || isset($_POST["AjouterFin"]) && empty($_POST["nom"]) || $promoDejaAjouter == true) {
                            echo '<div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace entrebou" value="Annuller" name="Annuller">
                            <input type="submit" class="form-control col-md-4 espace" value="Enregister" name="AjouterFin">';
                        } 
                        elseif (isset($_POST["premierValidation"]) &&  $existeDeja == true || isset($_POST["valider"]) && empty($_POST["nom"])) {
                            echo '<div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace entrebou" value="Annuller" name="Annuller">
                            <input type="submit" class="form-control col-md-4 espace" value="Enregister" name="valider">';
                        } 
                        else {
                            echo '<div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace entrebou" value="Ajouter" name="Ajouter">
                            <input type="submit" class="form-control col-md-4 espace" value="Modifier" name="premierValidation">';
                        }
                        ?>
                    </div>
                </div>
                <?php

                $existeDeja = 0;
                ///////////////////////////////////------Debut Ajouter-----////////////////////////////////
                if (isset($_POST["AjouterFin"]) && !empty($_POST["nom"]) && $promoDejaAjouter == false) {
                    $nom = securisation($_POST["nom"]);
                    $debut_promo = securisation($_POST["debut_promo"]);
                    $fin_promo = securisation($_POST["fin_promo"]);
                    $codemysql = "INSERT INTO `promo` (Nom,id_referentiels,debut_promo,fin_promo) VALUES(:Nom,:id_referentiels,:debut_promo,:fin_promo)"; //le code mysql
                    $requete = $connexion->prepare($codemysql);
                    $requete->bindParam(":Nom", $nom);
                    $requete->bindParam(":debut_promo", $debut_promo);
                    $requete->bindParam(":fin_promo", $fin_promo);

                    $nom_ref=securisation($_POST["ref"]);
                    ///////////-----recuperation des promo----///////////
                    $codemysql = "SELECT id_referentiels FROM referentiels WHERE Nom='$nom_ref'"; //le code mysql
                    $les_reff=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des promo----///////////
                    $id_referentiels=$les_reff[0]["id_referentiels"];
                    $requete->bindParam(":id_referentiels", $id_referentiels);
                    $requete->execute(); //excecute la requete qui a été preparé
                }
                ####################################------Fin Ajouter-----#################################

                ///////////////////////////////////------Debut Modification-----///////////////////////////
                if (isset($_POST["valider"]) && !empty($_POST["nom"])) {
                    $id_promo=securisation($_POST["code_promo"]);
                    $nom = securisation($_POST["nom"]);
                    $debut_promo = securisation($_POST["debut_promo"]);
                    $fin_promo = securisation($_POST["fin_promo"]);
                    $codemysql = "UPDATE `promo` SET Nom='$nom',debut_promo='$debut_promo',fin_promo='$fin_promo' WHERE id_promo='$id_promo' ";
                    $requete = $connexion->prepare($codemysql);
                    $requete->execute();
                }
                ####################################------Fin Modification----#############################S
                ?>
                </div>
            </form>
            <!-- ///////////////////////////////////------Debut Affichage-----//////////////////////// -->
            <?php
            if($tableVide==false){
            echo'<table class="col-12 tabliste table">
                <thead class="thead-dark">
                    <tr class="row">
                        <td class="col-md-2 text-center gras">Code</td>
                        <td class="col-md-2 text-center gras">Nom</td>
                        <td class="col-md-2 text-center gras">Début</td>
                        <td class="col-md-2 text-center gras">Fin</td>
                        <td class="col-md-2 text-center gras">Effectif</td>
                        <td class="col-md-2 text-center gras">Lister</td>
                    </tr>
                </thead>';
            }

            ///////////-----recuperation des données promo----///////////
            $codemysql = "SELECT * FROM promo"; //le code mysql
            $lesPromos=recuperation($connexion,$codemysql);
            ///////////-----recuperation des données promo----///////////

            ///////////-----recuperation des données des etudiants----///////////
            $codemysql = "SELECT Nom,id_promo,NCI FROM etudiants"; //le code mysql
            $etudiants=recuperation($connexion,$codemysql);
            ///////////-----recuperation des données des etudiants----///////////

            $i=0;
            for($a=0;$a<count($lesPromos);$a++){
                $referentiel = $lesPromos[$a]["Nom"];
                $id_ref=$lesPromos[$a]["id_promo"];
                //////------compter effectif---//////
                $effectif = 0;
                for($b=0;$b<count($etudiants);$b++) {
                    $etudiant = $etudiants[$i]["Nom"];
                    $id_ref_etudiant=$etudiants[$b]["id_promo"];//la clé de son referentiel
                    if (isset($etudiant) && isset($referentiel) && $id_ref == $id_ref_etudiant) {
                        $effectif++;
                    }
                }
                //////------Fin compter effectif---//////
                    $ligne=$lesPromos[$a]["Nom"]." ".$lesPromos[$a]["debut_promo"]." ".$lesPromos[$a]["fin_promo"]." ".$effectif;
                    ######-------fin compter effectif####
                    if ( $tableVide==false && !isset($_POST["recherche"]) || isset($_POST["recherche"]) &&  !empty($_POST["aRechercher"]) && strstr(strtolower($ligne), strtolower($_POST["aRechercher"])) || $tableVide==false && isset($_POST["recherche"]) && empty($_POST["aRechercher"])) {
                        echo
                            '<tr class="row">
                                <td class="col-md-2 text-center">' . $lesPromos[$a]["id_promo"] . '</td>
                                <td class="col-md-2 text-center">' . $lesPromos[$a]["Nom"] . '</td>
                                <td class="col-md-2 text-center">' . $lesPromos[$a]["debut_promo"]. '</td>
                                <td class="col-md-2 text-center">' . $lesPromos[$a]["fin_promo"]. '</td>
                                <td class="col-md-2 text-center"> ' . $effectif . '</td>
                                <td class="col-md-2 text-center"><a href="ListerEtudiant.php?ref=' . $lesPromos[$a]["Nom"]  . ' "  id="' . $lesPromos[$a]["id_promo"] . '" ><button class="btn btn-outline-success boutList">Liste</button></a></td>
                            </tr>';
                    }
                }
                ####################################------Fin Affichage-----#################################
            }
            catch (PDOException $e) {
                echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
            }
            ?>
        </table>
    </section>
    <?php
    include("piedDePage.php");
    ?>
</body>

</html>