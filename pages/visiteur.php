<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "visiteur";
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
        .page_link,.prev_link,.next_link{
            border:1px solid #007bffb9;
            border-radius: 50px;
            font-size:30px;
            background-color: #d0c9d6;
            padding:2px 10px 3px 10px;
            text-decoration: none ;
            color: #212529;
        }
        .pager{
            justify-content: center;
        }
        .page_link:hover,.prev_link:hover,.next_link:hover{
            text-decoration: none;
            color: #212529;
        }
        .pager>.active>a{
            border-radius: 50px;
            background-color: #007bffb9;
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
        .bsup{
            width:50%;
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
                try {
                    include("connexionBDD.php");
                    ############################--Debut contenu table--############################
                    ///////////-----recuperation des données des etudiants----///////////
                    $codemysql = "SELECT * FROM visiteurs"; //le code mysql
                    $visiteurs=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des données des etudiants----///////
                    if(isset($visiteurs[0][1])){
                        $tableVide=false;
                    }
                    ############################--Fin contenu table--##############################


                ///////////////////////////////----Validation des élements avant ajout definitif------/////////////////
                if (isset($_POST["AjouterFin"]) || isset($_POST["valider"])) {
                    if (!empty($_POST["nom"]) && !empty($_POST["datevisite"])) {
                        $valAjout = true;
                    }
                }
                ////////////////////////////----Fin de la validation des élements avant ajout definitif------///////

                if (isset($_POST["premierValidation"])) {
                    ////////////----même nom----//////////////////
                   for($i=0;$i<count($visiteurs);$i++) {
                        if ($tableVide==false && strtolower($visiteurs[$i]["Nom"]) == strtolower($_POST["nom"])) {
                            $nombre++;
                            $existeDeja = true;
                        }
                    }
                    ////////////----Fin même nom----//////////////

                    ////////////----Recupération anciennes données---//////////////
                    for($i=0;$i<count($visiteurs);$i++) {
                        if ($tableVide==false && strtolower($visiteurs[$i]["Nom"]) == strtolower($_POST["nom"]) && $nombre == 1 || isset($_POST["ancienCode"]) && strtolower($visiteurs[$i]["Nom"]) == strtolower($_POST["nom"]) && $nombre > 1 && $_POST["ancienCode"] == $visiteurs[$i]["id_visiteurs"]) {
                            //soit on cherche avec le nom si il y a une seule personne qui porte ce nom soit avec le nom et le code si plusieurs personnes ont ce nom
                            $_POST["nom"] = $visiteurs[$i]["Nom"]; //pouvoir utiliser le bon nom
                            $date_deVisite =$visiteurs[$i]["Date"];
                            $ancTel = $visiteurs[$i]["Telephone"];
                            $ancEmail = $visiteurs[$i]["Email"];
                            $confirmer = true;
                        }
                    }
                    ////////////----Fin Recupération anciennes données---//////////////
                }
                //////////////////////////-------Code----------------------//////////////////////
                if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre>1 || isset($_POST["valider"]) && $valAjout == false) {
                    echo '<div class="row">
                        <div class="col-md-2"></div>
                        <select class="form-control col-md-8 espace" name="ancienCode" >';

                   for($i=0;$i<count($visiteurs);$i++) {
                        if ($visiteurs[$i]["Nom"] == $_POST["nom"] && !isset($_POST["ancienCode"])) {
                            echo '<option value="' . $visiteurs[$i]["id_visiteurs"]. '" selected>' . $visiteurs[$i]["id_visiteurs"] . '</option>';
                        } 
                        elseif (isset($_POST["ancienCode"]) && $visiteurs[$i]["id_visiteurs"] == $_POST["ancienCode"]) { //apres validation du code le selectionné
                            echo '<option value="' . $_POST["ancienCode"] . '" selected>' . $_POST["ancienCode"] . '</option>';
                        }
                    }
                    echo '</select>
                    </div>';
                }
                //////////////////////////-------Fin Code----------------------//////////////////////

                //////////////////////////-------Nom----------------------/////////////////////////
                echo '<div class="row">
                    <div class="col-md-2"></div>
                    <input  type="text" id="nom" name="nom" ';
                if (isset($_POST["premierValidation"]) || isset($_POST["Ajouter"])) {
                    if (empty($_POST["nom"]) && !isset($_POST["Ajouter"])) {
                        echo ' class="form-control col-md-8 espace rougMoins" placeholder= "Entrez le nom du visiteur à modifier !"';
                    } 
                    else { //si on ajoute ou on modifie
                        if ($existeDeja == false && !isset($_POST["Ajouter"])) { //si on essaie de modifier une personne qui n'existe pas
                            echo ' class="form-control col-md-8 espace rougMoins" placeholder= "' . $_POST["nom"] . ' n\'existe pas"';
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
                    echo ' class="form-control col-md-8 espace rougMoins" placeholder= "Entrez le nom du visiteur !"';
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
                    //////////////////////////-------date visite----------------------//////////////////////
                    echo '<div class="row">
                        <div class="col-md-2"></div>';
                    if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"])) {
                        echo '<input class="form-control col-md-8 espace" type="date" id="datevisite" name="datevisite" ';
                        if ($existeDeja == true) {
                            echo 'value="' . $date_deVisite . '" ';
                        }
                        elseif(isset($_POST["Ajouter"])){
                             echo 'value="' . date("Y-m-d") . '" ';
                        }
                    } 
                    elseif (isset($_POST["AjouterFin"]) && empty($_POST["datevisite"]) || isset($_POST["valider"]) && empty($_POST["datevisite"])) { //si la date visite vide lors de l'ajout
                        echo '<input class="form-control col-md-8 espace rougMoins" type="date" id="datevisite" name="datevisite" ';
                    } 
                    elseif (isset($_POST["AjouterFin"]) && $valAjout == false || isset($_POST["valider"]) && $valAjout == false) { //si il manque des informations avant l'ajout remettre la date visite
                        echo '<input class="form-control col-md-8 espace " type="date" id="datevisite" name="datevisite" value ="' . $_POST["datevisite"] . '" ';
                    }
                    echo '>
                    </div>';
                    //////////////////////////-------Fin date visite----------------------//////////////////////

                    //////////////////////////-------Telephone----------------------//////////////////////
                    echo '<div class="row">
                        <div class="col-md-2"></div>';
                    if (isset($_POST["premierValidation"]) && $existeDeja == true && $nombre == 1 || isset($_POST["premierValidation"]) && $existeDeja == true && $nombre > 1 && $confirmer == true || isset($_POST["Ajouter"])) {
                        echo '<input class="form-control col-md-8 espace" type="number" id="tel" name="tel" placeholder="Téléphone" ';
                        if ($existeDeja == true) {
                            echo 'value="' . $ancTel . '" ';
                        }
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
                    elseif (isset($_POST["AjouterFin"]) && $valAjout == false || isset($_POST["valider"]) && $valAjout == false) { //si il manque des informations avant l'ajout remettre l'email
                        echo '<input class="form-control col-md-8 espace" type="email" id="email" name="email" placeholder="Email" value ="' . $_POST["email"] . '" ';
                    }

                    echo '>
                    </div>';
                    //////////////////////////-------Fin Email---------------------//////////////////////
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
            $existeDeja = 0;
            $nouv = "";


            ///////////////////////////////////------Debut Ajouter-----////////////////////////////////
            if (isset($_POST["AjouterFin"]) && $valAjout == true) {
                ///////////-----recuperation des données des etudiants----///////////
                $codemysql = "SELECT id_visiteurs FROM visiteurs"; //le code mysql
                $id_des_visiteurs=recuperation($connexion,$codemysql);
                ///////////-----Fin recuperation des données des etudiants----///////
                if(isset($id_des_visiteurs[0]["id_visiteurs"])){
                    $id_visiteurs=$id_des_visiteurs[count($id_des_visiteurs)-1]["id_visiteurs"];//l'id du dernier visiteur
                    $id_visiteurs=str_replace(" V-SA","",$id_visiteurs);
                    $id_visiteurs=$id_visiteurs+1;
                    $id_visiteurs=$id_visiteurs." V-SA";
                }
                else{
                    $id_visiteurs="1 V-SA";
                }
                $nom = securisation($_POST["nom"]);
                $datVis = securisation($_POST["datevisite"]);

                $tel = securisation($_POST["tel"]);
                $email = securisation($_POST["email"]);
                $agent=$_SESSION["Code_agents"];
                $HeureVis=date("H:i");
                $codemysql = "INSERT INTO `visiteurs` (id_visiteurs,Nom,Date,Heure,Telephone,Email,Code_agents)
                            VALUES(:id_visiteurs,:Nom,:Date,:Heure,:Telephone,:Email,:Code_agents)"; //le code mysql
                $requete = $connexion->prepare($codemysql);
                $requete->bindParam(":id_visiteurs", $id_visiteurs);
                $requete->bindParam(":Nom", $nom);
                $requete->bindParam(":Date", $datVis);
                $requete->bindParam(":Heure", $HeureVis);
                $requete->bindParam(":Telephone", $tel);
                $requete->bindParam(":Email", $email);
                $requete->bindParam(":Code_agents", $agent);
                $requete->execute(); //excecute la requete qui a été preparé
            }
            ####################################------Fin Ajouter-----#################################

            ///////////////////////////////////------Debut Modification-----///////////////////////////
            if (isset($_POST["valider"])  && $valAjout == true) {
                $nom = securisation($_POST["nom"]);
                $datVis = securisation($_POST["datevisite"]);
                $tel = securisation($_POST["tel"]);
                $email = securisation($_POST["email"]);
                if ( isset($_POST["ancienCode"])) {//ils sont plusieurs à avoir ca nom
                    $sonId=securisation($_POST["ancienCode"]);
                    $codemysql = "UPDATE `visiteurs` SET Nom='$nom',Date='$datVis',Telephone='$tel',Email='$email' WHERE id_visiteurs='$sonId' ";
                    $requete = $connexion->prepare($codemysql);
                    $requete->execute();                   
                }
                elseif(!isset($_POST["ancienCode"])){//le nom est unique
                    $sonNom=securisation($_POST["nom"]);
                    $codemysql = "UPDATE `visiteurs` SET Nom='$nom',Date='$datVis',Telephone='$tel',Email='$email' WHERE Nom='$sonNom' ";
                    $requete = $connexion->prepare($codemysql);
                    $requete->execute();
                }
            }
            ####################################------Fin Modification----#############################S
            ?>
            </div>
        </form>
        <!-- ///////////////////////////////////------Debut Affichage-----//////////////////////// -->
        <?php
        if($tableVide==false || isset($_POST["AjouterFin"]) && $valAjout == true){
        echo'<table class="col-12 table tabliste table-hover">
            <thead class="">
                <tr class="row">
                    <td class="col-md-2 text-center gras">Code</td>
                    <td class="col-md-2 text-center gras">Nom</td>
                    <td class="col-md-1 text-center gras">heure</td>
                    <td class="col-md-1 text-center gras">Date</td>
                    <td class="col-md-2 text-center gras">Téléphone</td>
                    <td class="col-md-2 text-center gras">Email</td>
                    <td class="col-md-2 text-center gras">Suppression</td>
                </tr>
            </thead>
            <tbody id="developers">';
        }    
            $nbr=0;
            if(isset($_POST["AjouterFin"]) && $valAjout == true || isset($_POST["valider"]) && $valAjout == true ){//si ajouter ou modifier n'afficher que la ligne
                $datN = new DateTime($_POST["datevisite"]);
                    $date = $datN->format('d-m-Y');
                    $heurev=date("H:i");
                    if(isset($_POST["ancienCode"])){//modification : ils sont plusieurs à avoir ce nom
                        $leCode=$_POST["ancienCode"];
                        ///////////-----recuperation des données des etudiants----///////////
                        $codemysql = "SELECT Heure FROM visiteurs WHERE id_visiteurs='$leCode'"; //le code mysql
                        $id_des_visiteurs=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des données des etudiants----///////
                        $heurev=$id_des_visiteurs[0]["Heure"];
                    }
                    elseif(isset($_POST["valider"])){//modification : un seul à ce nom
                        $nom_visiteur=$_POST["nom"];
                        ///////////-----recuperation des données des etudiants----///////////
                        $codemysql = "SELECT id_visiteurs,Heure FROM visiteurs WHERE Nom='$nom_visiteur'"; //le code mysql
                        $id_des_visiteurs=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des données des etudiants----///////
                        $leCode=$id_des_visiteurs[0]["id_visiteurs"];
                        $heurev=$id_des_visiteurs[0]["Heure"];
                    }
                    elseif(isset($_POST["AjouterFin"])){//on viens de l'ajouter
                        $leCode=$id_visiteurs;
                    }
                    echo
                    '<tr class="row">
                        <td class="col-md-2 text-center"><a class="lesliens" href="visiteur.php" >' . $leCode. '</a></td>
                        <td class="col-md-2 text-center"><a class="lesliens" href="visiteur.php" >' . $_POST["nom"] . '</a></td>
                        <td class="col-md-1 text-center"><a class="lesliens" href="visiteur.php" >' . $date . '</a></td>
                        <td class="col-md-1 text-center"><a class="lesliens" href="visiteur.php" >' .$heurev.'</a></td>
                        <td class="col-md-2 text-center"><a class="lesliens" href="visiteur.php" >' . $_POST["tel"] . '</a></td>
                        <td class="col-md-2 text-center"><a class="lesliens" href="visiteur.php" >' . $_POST["email"] . '</a></td>
                        <td class="col-md-2 text-center"><a class="nonSoulign" href="visiteur.php?code_visiteur_a_supp=' . $leCode . '" ><button class="btn btn-outline-danger bsup ">Supprimer</button></a></td>
                    </tr>';
            }
            else{
                ///////////-----recuperation des données des etudiants----///////////
                $codemysql = "SELECT * FROM visiteurs"; //le code mysql
                $visiteurs=recuperation($connexion,$codemysql);
                ///////////-----Fin recuperation des données des etudiants----///////
                for($i=0;$i<count($visiteurs);$i++) {
                    $ligne = $visiteurs[$i]["id_visiteurs"]." ".$visiteurs[$i]["Nom"]." ".$visiteurs[$i]["Date"]." ".$visiteurs[$i]["Telephone"]." ".$visiteurs[$i]["Email"];
                    if ($tableVide==false && !isset($_POST["recherche"]) || isset($_POST["recherche"]) && !empty($_POST["aRechercher"]) && strstr(strtolower($ligne), strtolower($_POST["aRechercher"])) || $tableVide==false && isset($_POST["recherche"]) && empty($_POST["aRechercher"])) {
                    //si la table n'est pas vide et que on ne recherche rien                          //si on recherche une chose non vide et que cela face partie de la ligne                                 //si on appuis sur le bouton rechercher alors qu'on n'a rien ecrit afficher tous les éléments                                      
                        $datN = new DateTime($visiteurs[$i]["Date"]);
                        $heurev=$visiteurs[$i]["Heure"];
                        $datev = $datN->format('d-m-Y');
                        echo
                            '<tr class="row">
                                <td class="col-md-2 text-center">' . $visiteurs[$i]["id_visiteurs"] . '</td>
                                <td class="col-md-2 text-center">' . $visiteurs[$i]["Nom"]. '</td>
                                <td class="col-md-1 text-center">' . $datev. '</td>
                                <td class="col-md-1 text-center">' .$heurev.'</td>
                                <td class="col-md-2 text-center">' . $visiteurs[$i]["Telephone"] . '</td>
                                <td class="col-md-2 text-center">' . $visiteurs[$i]["Email"] . '</td>
                                 <td class="col-md-2 text-center"><a class="nonSoulign" href="visiteur.php?code_visiteur_a_supp=' . $visiteurs[$i]["id_visiteurs"] . '" ><button class="btn btn-outline-danger bsup ">Supprimer</button></a></td>
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
    if(isset($_GET["code_visiteur_a_supp"])){
        $sonId=$_GET["code_visiteur_a_supp"];
        $sup='code_visiteur_a_supp='.$sonId
        ?>
        <script>
            if(confirm("Confirmer la suppression ?")){
                 document.location.href = "traitement.php?<?php echo "$sup"; ?>"
            }
            else{
                document.location.href = "visiteur.php"
            }
        </script>
        <?php
    }
    ?>
    
    <script src="../js/jq.js"></script>
    <script src="../js/bootstrap-table-pagination.js"></script>
    <script src="../js/monjs.js"></script>
</body>

</html>