<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "ModifierEtudiant";
?>
<!DOCTYPE html>
<html lang="FR-fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/MonStyle.css">
    <title>Gestion des étudiants</title>
    <style>
        .nonSoulign {
            text-decoration: none !important;
        }
        .page_link,.prev_link,.next_link{
            border:1px solid #ff8200;
            border-radius: 50px;
            font-size:30px;
            background-color: #008e8e;
            padding:2px 10px 3px 10px;
            text-decoration: none ;
            color: #ff8200;
        }
        .pager{
            justify-content: center;
        }
        .page_link:hover,.prev_link:hover,.next_link:hover{
            text-decoration: none;
            color: #ff8200;
        }
        .pager>.active>a{
            border-radius: 50px;
            background-color: #ff8200;
            color: #008e8e;
        }
        .table {
            margin-bottom: 2em;
        }
        .active>.nav-link{
            background-color: #ff8200;
            border-bottom: 4px solid black;
        }
        .navbar-expand-lg{
            padding:0px 16px 0px 16px;
        }
        .lesliens, .lesliens:hover{
            text-decoration: none ;
            color: #212529;
        }

    </style>
</head>

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
                $tableVide=true;
                $existeDeja = false;
                $confirmer = false;
                $nombre = 0;
                $valAjout = false;
                $ne_pas_ajouter_code_existe=false;
                try {
                    include("connexionBDD.php");
                    ############################--Debut contenu fichier--############################
                    ///////////-----recuperation des données des etudiants----///////////
                    $codemysql = "SELECT * FROM etudiants"; //le code mysql
                    $etudiants=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des données des etudiants----///////
                    if(isset($etudiants[0][1])){
                        $tableVide=false;
                    }
                    ############################--Fin contenu fichier--##############################


                    ///////////////////////////////----Validation des élements avant ajout definitif------/////////////////
                    if (isset($_POST["AjouterFin"]) || isset($_POST["valider"]) && isset($_POST["ref"])) {
                        if (!empty($_POST["code"]) && !empty($_POST["nom"]) && !empty($_POST["dateNaiss"]) && !empty($_POST["tel"]) && !empty($_POST["email"]) && !empty($_POST["ref"])||!empty($_POST["ancienCode"]) && !empty($_POST["nom"]) && !empty($_POST["dateNaiss"]) && !empty($_POST["tel"]) && !empty($_POST["email"]) && !empty($_POST["ref"])) {
                            $valAjout = true;
                        }
                        if(isset($_POST["AjouterFin"])){
                            ///////////-----recuperation des données des etudiants----///////////
                            $le_code=$_POST["code"];
                            $codemysql = "SELECT * FROM etudiants WHERE NCI='$le_code'"; //le code mysql
                            $etudiants=recuperation($connexion,$codemysql);
                            ///////////-----Fin recuperation des données des etudiants----///////
                            if(isset($etudiants[0][1])){
                                $valAjout = false;//le code existe deja ne pas ajouter
                                $ne_pas_ajouter_code_existe=true;
                            }
                            
                        }
                    }
                    ////////////////////////////----Fin de la validation des élements avant ajout definitif------///////

                    if (isset($_POST["premierValidation"])) {
                        ////////////----même nom----//////////////////
                        for($i=0;$i<count($etudiants);$i++) {
                            if ($tableVide==false && strtolower($etudiants[$i]["Nom"]) == strtolower($_POST["nom"])) {
                                $nombre++;
                                $existeDeja = true;
                            }
                        }

                        ////////////----Fin même nom----//////////////

                        ////////////----Recupération anciennes données---//////////////
                        
                        for($i=0;$i<count($etudiants);$i++)  {

                            if ($tableVide==false && strtolower($etudiants[$i]["Nom"]) == strtolower($_POST["nom"]) && $nombre == 1 || isset($_POST["ancienCode"]) && strtolower($etudiants[$i]["Nom"]) == strtolower($_POST["nom"]) && $nombre > 1 && $_POST["ancienCode"] == $etudiants[$i]["NCI"]) {
                                //soit on cherche avec le nom si il y a une seule personne qui porte ce nom soit avec le nom et le code si plusieurs personnes ont ce nom
                                
                                ///////////-----recuperation des données de la table ref----///////////
                                $NCI_etudiant=$etudiants[$i]["NCI"];
                                $codemysql = "SELECT promo.Nom FROM promo INNER JOIN etudiants ON promo.id_promo=etudiants.id_promo WHERE etudiants.NCI='$NCI_etudiant'"; //le code mysql
                                $le_ref_etudiant=recuperation($connexion,$codemysql);
                                ///////////-----Fin recuperation des données de la table ref----////////

                                $_POST["nom"] = $etudiants[$i]["Nom"]; //pouvoir utiliser le bon nom
                                $ancDNaiss = $etudiants[$i]["Naissance"];
                                $ancTel = $etudiants[$i]["Telephone"];
                                $ancEmail = $etudiants[$i]["Email"];
                                $ancieref = $le_ref_etudiant[0]["Nom"];
                                $confirmer = true;
                            }
                        }
                    
                        ////////////----Fin Recupération anciennes données---//////////////
                    }

                    //////////////////////////-------Code----------------------//////////////////////
                    if (isset($_POST["premierValidation"]) && $existeDeja == true || isset($_POST["valider"]) && $valAjout == false) {
                        echo '<div class="row">
                            <div class="col-md-2"></div>
                            <select class="form-control col-md-8 espace" name="ancienCode" >';
                        for($i=0;$i<count($etudiants);$i++)  {
                            $ligne = fgets($monfichier);
                            if ($etudiants[$i]["Nom"]== $_POST["nom"] && !isset($_POST["ancienCode"])) {
                                echo '<option value="' . $etudiants[$i]["NCI"] . '" selected>' . $etudiants[$i]["NCI"] . '</option>';
                            } 
                            elseif (isset($_POST["ancienCode"]) && $etudiants[$i]["NCI"] == $_POST["ancienCode"]) { //apres validation du code le selectionné
                                echo '<option value="' . $_POST["ancienCode"] . '" selected>' . $_POST["ancienCode"] . '</option>';
                            }
                        }
                        echo '</select>
                        </div>';
                    }
                    if (isset($_POST["Ajouter"])|| isset($_POST["AjouterFin"]) && $valAjout == false) {
                            echo '<div class="row">
                            <div class="col-md-2"></div>';
                        if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"])) {
                            echo '<input class="form-control col-md-8 espace" name="code" placeholder="Numéro carte d\'identité" ';
                        } 
                        elseif (isset($_POST["AjouterFin"]) && empty($_POST["code"]) || isset($_POST["valider"]) && empty($_POST["code"])) { //si le téléphone vide lors de l'ajout
                            echo '<input class="form-control col-md-8 espace rougMoins" type="text" name="code" placeholder="Remplir le numéro de le carte d\'identité" ';
                        } 
                        elseif (isset($_POST["AjouterFin"]) && $valAjout == false && $ne_pas_ajouter_code_existe==false|| isset($_POST["valider"]) && $valAjout == false) { //si il manque des informations avant l'ajout remettre le téléphone
                            echo '<input class="form-control col-md-8 espace" type="text"  name="code" placeholder="Numéro carte d\'identité" value ="' . $_POST["code"] . '" ';
                        }
                        elseif (isset($_POST["AjouterFin"]) && $valAjout == false && $ne_pas_ajouter_code_existe==true) { //si il manque des informations avant l'ajout remettre le téléphone
                            echo '<input class="form-control col-md-8 espace rougMoins" type="text"  name="code" placeholder ="' . $_POST["code"] . ' existe déja !" ';
                        }
                        echo '">
                        </div>';
                    }
                    //////////////////////////-------Fin Code----------------------//////////////////////

                    //////////////////////////-------Nom----------------------/////////////////////////
                    echo '<div class="row">
                        <div class="col-md-2"></div>
                        <input  type="text" id="nom" name="nom" ';
                    if (isset($_POST["premierValidation"]) || isset($_POST["Ajouter"])) {
                        if (empty($_POST["nom"]) && !isset($_POST["Ajouter"])) {
                            echo ' class="form-control col-md-8 espace rougMoins" placeholder= "Entrez le nom de l\'apprenant à modifier !"';
                        } 
                        else { //si on ajoute ou on modifie
                            if ($existeDeja == false && !isset($_POST["Ajouter"])) { //si on essaie de modifier une personne qui n'existe pas
                                echo ' class="form-control col-md-8 espace rougMoins" placeholder= "' . $_POST["nom"] . ' ne fait pas partie des apprenants"';
                            } 
                            elseif ($existeDeja == true || isset($_POST["Ajouter"]) && !empty($_POST["nom"])) { //soit on veut modifier une personne qui existe soit on veut ajouter une personne dont on a écrit le nom
                                echo ' class="form-control col-md-8 espace" placeholder= "Nom et prénom" value="' . $_POST["nom"] . '"';
                            } 
                            else {
                                echo ' placeholder="Nom et prénom" class="form-control col-md-8 espace" ';
                            }
                        }
                    } 
                    elseif (isset($_POST["AjouterFin"]) && empty($_POST["nom"]) || isset($_POST["valider"]) && empty($_POST["nom"])) { //si on enregistre alors que le nom est vide
                        echo ' class="form-control col-md-8 espace rougMoins" placeholder= "Entrez le nom de l\'apprenant à ajouter !"';
                    } 
                    elseif (isset($_POST["AjouterFin"]) && $valAjout == false  || isset($_POST["valider"]) && $valAjout == false) { //si on enregistre alors que le nom n'etait pas vide on y remet sa valeur
                        echo ' class="form-control col-md-8 espace " placeholder= "Nom et prénom" value="' . $_POST["nom"] . '" ';
                    } 
                    else { //chargement de la page 
                        echo ' placeholder="Nom et prénom" class="form-control col-md-8 espace" ';
                    }
                    echo '>
                    </div>';
                    //////////////////////////-------Fin Nom----------------------/////////////////////////

                    if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"]) || isset($_POST["AjouterFin"]) && $valAjout == false || isset($_POST["valider"]) && $valAjout == false) {
                        //////////////////////////-------Date de naissance----------------------//////////////////////
                        echo '<div class="row">
                            <div class="col-md-2"></div>';
                        if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"])) {
                            echo '<input class="form-control col-md-8 espace" type="date" id="dateNaiss" name="dateNaiss" ';
                            if ($existeDeja == true) {
                                echo 'value="' . $ancDNaiss . '" ';
                            }
                        } 
                        elseif (isset($_POST["AjouterFin"]) && empty($_POST["dateNaiss"]) || isset($_POST["valider"]) && empty($_POST["dateNaiss"])) { //si la date de naissance vide lors de l'ajout
                            echo '<input class="form-control col-md-8 espace rougMoins" type="date" id="dateNaiss" name="dateNaiss" ';
                        } 
                        elseif (isset($_POST["AjouterFin"]) && $valAjout == false || isset($_POST["valider"]) && $valAjout == false) { //si il manque des informations avant l'ajout remettre la date de naissance
                            echo '<input class="form-control col-md-8 espace " type="date" id="dateNaiss" name="dateNaiss" value ="' . $_POST["dateNaiss"] . '" ';
                        }
                        echo '>
                        </div>';
                        //////////////////////////-------Fin Date de naissance----------------------//////////////////////

                        //////////////////////////-------Telephone----------------------//////////////////////
                        echo '<div class="row">
                            <div class="col-md-2"></div>';
                        if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"])) {
                            echo '<input class="form-control col-md-8 espace" type="number" id="tel" name="tel" placeholder="Téléphone" ';
                            if ($existeDeja == true) {
                                echo 'value="' . $ancTel . '" ';
                            }
                        } 
                        elseif (isset($_POST["AjouterFin"]) && empty($_POST["tel"]) || isset($_POST["valider"]) && empty($_POST["tel"])) { //si le téléphone vide lors de l'ajout
                            echo '<input class="form-control col-md-8 espace rougMoins" type="number" id="tel" name="tel" placeholder="Remplir le numéro de téléphone" ';
                        } 
                        elseif (isset($_POST["AjouterFin"]) && $valAjout == false || isset($_POST["valider"]) && $valAjout == false) { //si il manque des informations avant l'ajout remettre le téléphone
                            echo '<input class="form-control col-md-8 espace" type="number" id="tel" name="tel" placeholder="Téléphone" value ="' . $_POST["tel"] . '" ';
                        }

                        echo '">
                        </div>';
                        //////////////////////////-------Fin Telephone----------------------//////////////////////


                        //////////////////////////-------Email---------------------//////////////////////
                        echo '<div class="row">
                            <div class="col-md-2"></div>';
                        if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"])) {
                            echo '<input class="form-control col-md-8 espace" type="email" id="email" name="email" placeholder="Email" ';
                            if ($existeDeja == true) {
                                echo 'value="' . $ancEmail . '" ';
                            }
                        } 
                        elseif (isset($_POST["AjouterFin"]) && empty($_POST["email"]) || isset($_POST["valider"]) && empty($_POST["email"])) { //si email vide lors de l'ajout
                            echo '<input class="form-control col-md-8 espace rougMoins" type="email" id="email" name="email" placeholder="Remplir l\'email" ';
                        } 
                        elseif (isset($_POST["AjouterFin"]) && $valAjout == false || isset($_POST["valider"]) && $valAjout == false) { //si il manque des informations avant l'ajout remettre l'email
                            echo '<input class="form-control col-md-8 espace" type="email" id="email" name="email" placeholder="Email" value ="' . $_POST["email"] . '" ';
                        }

                        echo '>
                        </div>';
                        //////////////////////////-------Fin Email---------------------//////////////////////

                        //////////////////////////-------ref---------------------//////////////////////
                        echo '<div class="row">
                            <div class="col-md-2"></div>
                            <select class="form-control col-md-8 espace" name="ref" >';
                        ///////////-----recuperation des données de la table ref----///////////
                        $codemysql = "SELECT Nom FROM promo";
                        $lesReferentiel=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des données de la table ref----///////////

                        for($i=0;$i<count($lesReferentiel);$i++){
                            if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"])) {
                                if (isset($_POST["premierValidation"]) && $ancieref == $lesReferentiel[$i]["Nom"]) {
                                    echo '<option value="' . $lesReferentiel[$i]["Nom"] . '" selected>' . $lesReferentiel[$i]["Nom"]. '</option>';
                                } 
                                else {
                                    echo '<option value="' . $lesReferentiel[$i]["Nom"] . '">' . $lesReferentiel[$i]["Nom"] . '</option>';
                                }
                            } 
                            elseif (isset($_POST["AjouterFin"]) && $valAjout == false || isset($_POST["valider"]) && $valAjout == false) { //si il manque des informations avant l'ajout remettre la ref
                                if ($_POST["ref"] == $lesReferentiel[$i]["Nom"]) { //selectionner la bonne ref
                                    echo '<option value="' . $lesReferentiel[$i]["Nom"] . '" selected>' . $lesReferentiel[$i]["Nom"] . '</option>';
                                } 
                                else {
                                    echo '<option value="' . $lesReferentiel[$i]["Nom"] . '">' . $lesReferentiel[$i]["Nom"] . '</option>';
                                }
                            }
                        }
                        echo '</select>
                        </div>';
                        ///////////////////////////-------Fin ref---------------------//////////////////////
                    }
                    ?>
                    <div class="row">
                        <?php
                        ////////////////////////////////////////------Gestion des submit-------///////////////////////
                        if (isset($_POST["Ajouter"]) || isset($_POST["AjouterFin"]) && $valAjout == false) {
                            echo '<div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace" value="Annuller" name="Annuller">
                            <input type="submit" class="form-control col-md-4 espace" value="Enregister" name="AjouterFin">';
                        } 
                        elseif (isset($_POST["premierValidation"]) &&  $existeDeja == true && $nombre == 1 || isset($_POST["ancienCode"]) && isset($_POST["premierValidation"]) || isset($_POST["valider"]) && $valAjout == false) {
                            echo '<div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace" value="Annuller" name="Annuller">
                            <input type="submit" class="form-control col-md-4 espace" value="Enregister" name="valider">';
                        } 
                        elseif (isset($_POST["premierValidation"]) &&  $existeDeja == true && $nombre > 1) {
                            echo '<div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace" value="Annuller" name="Annuller">
                            <input type="submit" class="form-control col-md-4 espace" value="Confirmer" name="premierValidation">';
                        } 
                        else {
                            echo '<div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace" value="Ajouter" name="Ajouter">
                            <input type="submit" class="form-control col-md-4 espace" value="Modifier" name="premierValidation">';
                        }
                        ////////////////////////////////////////------Fin Gestion des submit-------///////////////////////
                        ?>
                    </div>
                </div>
                <?php

                ///////////////////////////////////------Debut Ajouter-----////////////////////////////////
                if (isset($_POST["AjouterFin"]) && $valAjout == true) {
                    $code = securisation($_POST["code"]);
                    ///////////-----recuperation des données de la table ref----////////////
                    $ref = securisation($_POST["ref"]);
                    $codemysql = "SELECT id_promo FROM promo WHERE Nom='$ref'"; //le code mysql
                    $id_referentiel=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des données de la table ref----////////
                    $nom = securisation($_POST["nom"]);
                    $dateNaiss = securisation($_POST["dateNaiss"]);
                    $tel = securisation($_POST["tel"]);
                    $email = securisation($_POST["email"]);
                    $codemysql = "INSERT INTO `etudiants` (NCI,id_promo,Nom,Naissance,Telephone,Email)
                            VALUES(:NCI,:id_promo,:Nom,:Naissance,:Telephone,:Email)"; //le code mysql
                    $requete = $connexion->prepare($codemysql);
                    $requete->bindParam(":id_promo", $id_referentiel[0]["id_promo"]);
                    $requete->bindParam(":NCI", $code);
                    $requete->bindParam(":Nom", $nom);
                    $requete->bindParam(":Naissance", $dateNaiss);
                    $requete->bindParam(":Telephone", $tel);
                    $requete->bindParam(":Email", $email);
                    $requete->execute(); //excecute la requete qui a été preparé
                    
                }
                ####################################------Fin Ajouter-----#################################

                ///////////////////////////////////------Debut Modification-----///////////////////////////
                if (isset($_POST["valider"])  && $valAjout == true) {
                        $NCI_etudiant=$_POST["ancienCode"];
                        ///////////-----recuperation des données de la table ref----////////////
                        $ref=securisation($_POST["ref"]);
                        $codemysql = "SELECT id_promo FROM promo WHERE Nom='$ref'"; //le code mysql
                        $id_referentiel=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des données de la table ref----////////
                        $id_ref=$id_referentiel[0]["id_promo"];
                        $nom_etudiant=securisation($_POST["nom"]);
                        $naiss_etudiant=securisation($_POST["dateNaiss"]); 
                        $tel_etudiant=securisation($_POST["tel"]);
                        $email_etudiant=securisation($_POST["email"]);
                        $codemysql = "UPDATE `etudiants` SET id_promo='$id_ref',Nom='$nom_etudiant',Naissance='$naiss_etudiant',Telephone='$tel_etudiant',Email='$email_etudiant' WHERE NCI='$NCI_etudiant' ";
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
                echo'<table class="col-12 table tabliste table-hover">
                    <thead class="">
                        <tr class="row">
                            <td class="col-md-2 text-center gras">N° CI</td>
                            <td class="col-md-2 text-center gras">Référentiel</td>
                            <td class="col-md-2 text-center gras">Nom</td>
                            <td class="col-md-2 text-center gras">Date de naissance</td>
                            <td class="col-md-1 text-center gras">Téléphone</td>
                            <td class="col-md-3 text-center gras">Email</td>
                        </tr>
                    </thead>
                    <tbody id="developers">';
                }   
                $nbr=0;
                ///////////-----recuperation des données des etudiants----///////////
                $codemysql = "SELECT * FROM etudiants ORDER BY id_promo"; //le code mysql
                $etudiants=recuperation($connexion,$codemysql);
                ///////////-----Fin recuperation des données des etudiants----//////
                if(isset($_POST["AjouterFin"]) && $valAjout == true || isset($_POST["valider"]) && $valAjout == true ){//si ajouter ou modifier n'afficher que la ligne
                    $datN = new DateTime($_POST["dateNaiss"]);
                    $date = $datN->format('d-m-Y');
                    if(isset($_POST["ancienCode"])){
                        $leCode=$_POST["ancienCode"];
                    }
                    else{
                        $leCode=$_POST["code"];
                    }
                    echo
                                '<tr class="row">
                                    <td class="col-md-2 text-center"><a class="lesliens" href="ModifierEtudiant.php" >' .securisation($leCode). '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="ModifierEtudiant.php" >' . securisation($_POST["ref"]). '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="ModifierEtudiant.php" >' . securisation($_POST["nom"]) . '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="ModifierEtudiant.php" >' . securisation($date) . '</a></td>
                                    <td class="col-md-1 text-center"><a class="lesliens" href="ModifierEtudiant.php" >' . strip_tags($_POST["tel"]) . '</a></td>
                                    <td class="col-md-3 text-center"><a class="lesliens" href="ModifierEtudiant.php" >' . securisation($_POST["email"]) . '</a></td>
                                    
                                </tr>';
                }
                else{
                    for($i=0;$i<count($etudiants);$i++){
                        ///////////-----recuperation des données de la table ref----///////////
                        $NCI_etudiant=$etudiants[$i]["NCI"];
                        $codemysql = "SELECT promo.Nom FROM promo INNER JOIN etudiants ON promo.id_promo=etudiants.id_promo WHERE etudiants.NCI='$NCI_etudiant'"; //le code mysql
                        $le_ref_etudiant=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des données de la table ref----////////
                        $ligne = $NCI_etudiant." ".$le_ref_etudiant[0]["Nom"]." ".$etudiants[$i]["Nom"]." ".$etudiants[$i]["Naissance"]." ".$etudiants[$i]["Telephone"]." ".$etudiants[$i]["Email"];
                        if ($tableVide==false && !isset($_POST["recherche"]) || isset($_POST["recherche"]) && !empty($_POST["aRechercher"]) && strstr(strtolower($ligne), strtolower($_POST["aRechercher"])) || $tableVide==false && isset($_POST["recherche"]) && empty($_POST["aRechercher"])) {
                        //si le code n'est pas vide et que on ne recherche rien                          //si on recherche une chose non vide et que cela face partie de la ligne                                 //si on appuis sur le bouton rechercher alors qu'on n'a rien ecrit afficher tous les éléments                                      
                            $datN = new DateTime($etudiants[$i]["Naissance"]);
                            $date = $datN->format('d-m-Y');
                            echo
                                '<tr class="row">
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $NCI_etudiant . '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $le_ref_etudiant[0]["Nom"] . '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $etudiants[$i]["Nom"] . '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $date . '</a></td>
                                    <td class="col-md-1 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $etudiants[$i]["Telephone"] . '</a></td>
                                    <td class="col-md-3 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $etudiants[$i]["Email"] . '</a></td>
                                    
                                </tr>';
                                $nbr++;
                        }
                    } 
                }
                ####################################------Fin Affichage-----#################################
                echo'</tbody>
                    </table>';
                    if($nbr>8){
                        echo'<div class="col-md-12 text-center">
                            <ul class="pagination pagination-sm pager" id="developer_page"></ul>
                        </div>';
                    }
                    echo'<div class="bas"></div>';
        }
        catch (PDOException $e) {
            echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
        }
            ?>
    </section>
    <?php
    include("piedDePage.php");
    ?>
    <script src="../js/jq.js"></script>
    <script src="../js/bootstrap-table-pagination.js"></script>
    <script src="../js/monjs.js"></script>
</body>

</html>