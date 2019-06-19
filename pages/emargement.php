<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "emargement";
$ref="";
if (isset($_GET["ref"])) {
    $ref = $_GET["ref"];
} 
elseif (isset($_POST["ref"])) {
    $ref = $_POST["ref"];
}

if(isset($_POST["validerRechJour"])){//pour barre de recherche
    $_SESSION["jourChercjer"]=$_POST["jourRech"];
}
elseif(!isset($_POST["validerRechJour"]) && !isset($_POST["recherche"])){
    $_SESSION["jourChercjer"]=date("Y-m-d");
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
    <title>Emargement</title>
    <style>
        .nonSoulign {
            text-decoration: none !important;
        }
        .nonSoulign:hover{
            color:#495057;
        }
        .page_link,.prev_link,.next_link{
            border:1px solid #ff8200;
            border-radius: 50px;
            font-size:30px;
            background-color: #ff8200;
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
        .entrBouton{
            margin-right:0.2%;
        }
        .bsup{
            width:100%;
            padding-left:8%;
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
    <section class="container-fluid  pageLister">
        <?php
        try {
            include("connexionBDD.php");
            $tableVide=true;
            $sortie=false;
            $heureDepart="";
            ///////////-----recuperation des données de la table emargement----///////////
            $codemysql = "SELECT * FROM emargement"; //le code mysql
            $emargement=recuperation($connexion,$codemysql);
            ///////////-----recuperation des données de la table emargement-----///////////
            for($i=0;$i<count($emargement);$i++) {
                if(isset($emargement[0][1])){
                    $tableVide=false;
                }
                if (isset($_GET["code"]) && $emargement[$i]["NCI"]==$_GET["code"] && $emargement[$i]["Date_emargement"]==date('Y-m-d')||isset($_POST["code"]) && $emargement[$i]["NCI"]==$_POST["code"] && $emargement[$i]["Date_emargement"]==date('Y-m-d')){
                    $sortie=true;
                    $heurArrive=$emargement[$i]["Arrivee"];
                }
            }
            if(isset($_GET["code"]) || isset($_GET["aModifier"])){
                
                echo'<form method="POST" action="emargement.php" class="MonForm row insc">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 bor">';
                        $codeRecup="";
                        $nomRecup="";
                        $dateNow="";
                        $heureNow="";
                        
                        ///////////////////////////-------ref---------------------//////////////////////
                        echo '<div class="row">
                                <div class="col-md-2"></div>
                                <select class="form-control col-md-8 espace" name="ref" readonly="readonly">';
                                    echo '<option value="' . $ref. '" selected>' . $ref. '</option>';
                            echo'</select>
                            </div>';
                        ///////////////////////////-------Fin ref---------------------//////////////////////
                        

                        ///////////////////////////-------Récupération informations---------------------//////////////////////

                        
                        if (!isset($_POST["valider"]) && isset($_POST["ref"]) || !isset($_POST["valider"]) && isset($_GET["ref"])) {
                            ///////////-----recuperation des données des etudiants----///////////
                            $codemysql = "SELECT NCI,Nom FROM etudiants"; //le code mysql
                            $etudiants=recuperation($connexion,$codemysql);
                            ///////////-----Fin recuperation des données des etudiants----///////
                            for($i=0;$i<count($etudiants);$i++){
                                if(isset($_GET["code"]) && $etudiants[$i]["NCI"]==$_GET["code"] || isset($_POST["code"]) && $etudiants[$i]["NCI"]==$_POST["code"] ){
                                    $nomRecup=$etudiants[$i]["Nom"];
                                    $codeRecup=$etudiants[$i]["NCI"];
                                }
                            }
                            $dateNow = date('Y-m-d');
                            if($sortie==false){
                                $heureNow=date("H:i");
                            }
                            else{
                                $heureNow=$heurArrive;
                                $heureDepart=date("H:i");
                            }

                        }
                        ///////////////////////////-------Récupération informations---------------------//////////////////////
                        
                        ///////////////////////////-------recup information si modification---------------------//////////////////////
                        if(isset($_GET["aModifier"])){
                            for($i=0;$i<count($emargement);$i++) {
                                if($emargement[$i]["NCI"]==$_GET["aModifier"] && $emargement[$i]["Date_emargement"]==$_GET["date"]){
                                    $codeRecup=$emargement[$i]["NCI"];
                                    ///////////-----recuperation des données des etudiants----///////////
                                    $codemysql = "SELECT Nom FROM etudiants WHERE NCI='$codeRecup'"; //le code mysql
                                    $nom_etudiants=recuperation($connexion,$codemysql);
                                    ///////////-----Fin recuperation des données des etudiants----///////
                                    $nomRecup = $nom_etudiants[0]["Nom"];
                                    $dateNow = $emargement[$i]["Date_emargement"];
                                    $heureNow = $emargement[$i]["Arrivee"];
                                    $heureDepart = $emargement[$i]["Depart"];
                                }
                            }
                        }
                        ///////////////////////////-------recup information si modification----------------------//////////////////////

                        ///////////////////////////-------Code---------------------//////////////////////
                        echo'<div class="row">
                                <div class="col-md-2"></div>
                                <input type="" class="form-control col-md-8 espace" name="code" value="'.$codeRecup.'" readonly="readonly" placeholder="Code">
                            </div>';
                        ///////////////////////////-------Code---------------------//////////////////////

                        ///////////////////////////-------Nom---------------------//////////////////////
                        echo'<div class="row">
                                <div class="col-md-2"></div>
                                <input type="" class="form-control col-md-8 espace" name="nom" value="'.$nomRecup.'" readonly="readonly" placeholder="Nom">
                            </div>';
                        ///////////////////////////-------Nom---------------------//////////////////////

                        ///////////////////////////-------Date---------------------//////////////////////
                        echo'<div class="row">
                                <div class="col-md-2"></div>
                                <input type="date" class="form-control col-md-8 espace" name="auj" value="'.$dateNow.'" readonly="readonly">
                            </div>';
                        ///////////////////////////-------Date---------------------//////////////////////

                        ///////////////////////////-------Arrivée---------------------//////////////////////
                        echo'<div class="row">
                                <div class="col-md-2"></div>
                                <input type="time" class="form-control col-md-8 espace" name="arrivee" value="'.$heureNow.'"   ';if($sortie==true && !isset($_GET["aModifier"])){echo' readonly="readonly"';} echo'>
                            </div>';
                        ///////////////////////////-------Arrivée---------------------//////////////////////

                        ///////////////////////////-------Sortie---------------------//////////////////////
                        echo'<div class="row">
                                <div class="col-md-2"></div>
                                <input type="time" class="form-control col-md-8 espace" name="depart" value="'.$heureDepart.'"   ';if($sortie==false && !isset($_GET["aModifier"])){echo' readonly="readonly"';} echo'>
                            </div>';
                        ///////////////////////////-------Sortie---------------------//////////////////////

                        ///////////////////////////-------EnregistrerEnregistrer---------------------//////////////////////
                        echo '<div class="row">
                            <div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace" value="Annuller" name="Annuller">
                            <input type="submit" class="form-control col-md-4 espace" value="Enregistrer" name="valider">
                        </div>';
                        ///////////////////////////-------Enregistrer---------------------//////////////////////
                        
                        echo'</div>
                    </form>';
                }
                ///////////////////////////-------Continuer emargement---------------------//////////////////////
                elseif(!isset($_GET["ref"]) && isset($_POST["valider"]) || !isset($_GET["ref"]) && isset($_POST["Annuller"])){
                        echo'<form method="POST" action="ListerEtudiant.php?ref='.$_POST["ref"].'" class="MonForm row insc">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 bor">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <label for="" class="form-control col-md-8 text-center">Voulez-vous continuer les émargements ?</label>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <input type="submit" class="form-control col-md-4 entrBouton" value="Oui" name="contEmarg">
                                <a href="emargement.php" class="nonSoulign col-md-4 form-control text-center">Non</a>
                            </div>
                        </div>
                    </form>';
                }
                ///////////////////////////-------Continuer emargement---------------------//////////////////////

                ///////////////////////////-------rechercher par jour---------------------//////////////////////
                else{
                echo'<form method="POST" action="emargement.php" class="MonForm row insc">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 bor">';
                        echo '<div class="row">
                            <div class="col-md-2"></div>
                            <input type="date" class="form-control col-md-8 espace" name="jourRech"'; 
                            if(!isset($_POST["jourRech"]) && !isset($_POST["recherche"])){echo' value="'.date('Y-m-d').'" ';}
                            elseif(isset($_POST["jourRech"])){echo' value="'.$_POST["jourRech"].'" ';} 
                            elseif(isset($_POST["recherche"]) && isset($_SESSION["jourChercjer"])){echo' value="'.$_SESSION["jourChercjer"].'" ';} 
                            echo'>
                        </div>';
                        echo '<div class="row">
                            <div class="col-md-3"></div>
                            <input type="submit" class="form-control col-md-6 espace" value="Lister" name="validerRechJour">
                        </div>
                        </div>
                    </form>';
                }
                ///////////////////////////-------rechercher par jour---------------------//////////////////////

            ?>
            <?php
            if (isset($_POST["ref"]) || isset($_GET["ref"])) {

                ///////////////////////////////////------Debut Ajouter-----////////////////////////////////
                if(isset($_POST["valider"]) && $sortie==false) {
                    $NCI_etudiant=securisation($_POST["code"]);
                    $datEmar=securisation($_POST["auj"]);
                    ///////////-----recuperation des données de la table emargement----///////////
                    $codemysql = "SELECT id_emargement FROM emargement WHERE NCI='$NCI_etudiant' AND Date_emargement='$datEmar' "; //le code mysql
                    $id_emargement=recuperation($connexion,$codemysql);
                    ///////////-----recuperation des données de la table emargement-----///////////
                    if(!isset($id_emargement[0]["id_emargement"])){//donc il n a pas encore emarger
                        $code= securisation($_POST["code"]);
                        $date_emar = securisation($_POST["auj"]);
                        $hArriv = securisation($_POST["arrivee"]);
                        $hDepart = securisation($_POST["depart"]);
                        $codemysql = "INSERT INTO `emargement` (NCI,Date_emargement,Arrivee,Depart,Code_agents_arrivee)
                            VALUES(:NCI,:Date_emargement,:Arrivee,:Depart,:Code_agents_arrivee)"; //le code mysql
                        $requete = $connexion->prepare($codemysql); //Prépare la requête $codemysql à l'exécution
                        $requete->bindParam(":NCI", $code);
                        $requete->bindParam(":Date_emargement", $date_emar);
                        $requete->bindParam(":Arrivee", $hArriv);
                        $requete->bindParam(":Depart", $hDepart);
                        $requete->bindParam(":Code_agents_arrivee", $_SESSION["Code_agents"]);
                        $requete->execute(); //excecute la requete qui a été preparé
                    }
                    else{//donc il a emarger et on le modifie
                        $NCI_etudiant=securisation($_POST["code"]);
                        $datEmar=securisation($_POST["auj"]);
                        $hArriv=securisation($_POST["arrivee"]);
                        $hDepart=securisation($_POST["depart"]);
                        $Code_agents_arrivee=$_SESSION["Code_agents"];
                        ///////////-----recuperation des données de la table emargement----///////////
                        $codemysql = "SELECT id_emargement FROM emargement WHERE NCI='$NCI_etudiant' AND Date_emargement='$datEmar' "; //le code mysql
                        $id_emargement=recuperation($connexion,$codemysql);
                        ///////////-----recuperation des données de la table emargement-----///////////
                        $id_emarg=$id_emargement[0]["id_emargement"];
                        $codemysql = "UPDATE `emargement` SET Date_emargement='$datEmar',Arrivee='$hArriv',Depart='$hDepart',Code_agents_arrivee='$Code_agents_arrivee' WHERE id_emargement='$id_emarg'";
                        $requete = $connexion->prepare($codemysql);
                        $requete->execute();
                    }
                }
                ####################################------Fin Ajouter-----#################################

                ///////////////////////////////////------Sortie-----///////////////////////////
                if (isset($_POST["valider"])  && $sortie == true) {
                    for($i=0;$i<count($emargement);$i++) {
                        if ($emargement[$i]["NCI"]== $_POST["code"] &&$_POST["auj"]==$emargement[$i]["Date_emargement"] && !isset($_GET["aModifier"])|| isset($_GET["aModifier"]) && $emargement[$i]["NCI"] == $_GET["aModifier"] && $_POST["auj"]==$emargement[$i]["Date_emargement"] ) {//modifier si le code correspond                             
                            $NCI_etudiant=securisation($_POST["code"]);
                            $datEmar=securisation($_POST["auj"]);
                            $hArriv=securisation($_POST["arrivee"]);
                            $hDepart=securisation($_POST["depart"]);
                            $Code_agents_depart=$_SESSION["Code_agents"];
                            ///////////-----recuperation des données de la table emargement----///////////
                            $codemysql = "SELECT id_emargement FROM emargement WHERE NCI='$NCI_etudiant' AND Date_emargement='$datEmar' "; //le code mysql
                            $id_emargement=recuperation($connexion,$codemysql);
                            ///////////-----recuperation des données de la table emargement-----///////////
                            $id_emarg=$id_emargement[0]["id_emargement"];
                            $codemysql = "UPDATE `emargement` SET Date_emargement='$datEmar',Arrivee='$hArriv',Depart='$hDepart',Code_agents_depart='$Code_agents_depart' WHERE id_emargement='$id_emarg'";
                            $requete = $connexion->prepare($codemysql);
                            $requete->execute();
                        }
                    }
                }
                ####################################------Sortie----#############################
            }
                if($tableVide==false && !isset($_GET["aModifier"]) || isset($_GET["code"]) || isset($_POST["valider"])){
                echo '<table class="col-12 table tabliste table-hover">
                <thead class="">
                    <tr class="row">
                        <td class="col-md-2 text-center gras">N° CI</td>
                        <td class="col-md-2 text-center gras">Référentiel</td>
                        <td class="col-md-2 text-center gras">Nom</td>
                        <td class="col-md-2 text-center gras">Date</td>
                        <td class="col-md-1 text-center gras">Arrivée</td>
                        <td class="col-md-1 text-center gras">Sortie</td>
                        <td class="col-md-1 text-center gras">Modification</td>
                        <td class="col-md-1 text-center gras">Suppression</td>
                    </tr>
                </thead>
                <tbody id="developers">';
                }
                $nbr=0;
                /////////////////////////////////////////------Debut Affichage-----///////////////////////// 
                if(!isset($_POST["valider"]) && !isset($_GET["aModifier"])){
                    ///////////-----recuperation des données de la table emargement----///////////
                    $codemysql = "SELECT * FROM emargement"; //le code mysql
                    $emargement=recuperation($connexion,$codemysql);
                    ///////////-----recuperation des données de la table emargement-----///////////
                    $nbr=0;
                    for($i=0;$i<count($emargement);$i++){
                        $leNCI=$emargement[$i]["NCI"];

                        ///////////-----recuperation des données des etudiants----///////////
                        $codemysql = "SELECT Nom FROM etudiants WHERE NCI='$leNCI'"; //le code mysql
                        $etudiants=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des données des etudiants----///////

                        ///////////-----recuperation des données de la table ref----///////////
                        $codemysql = "SELECT promo.Nom FROM promo INNER JOIN etudiants ON promo.id_promo=etudiants.id_promo WHERE etudiants.NCI='$leNCI'"; //le code mysql
                        $le_ref_etudiant=recuperation($connexion,$codemysql);
                        ///////////-----Fin recuperation des données de la table ref----////////

                        $ligne = $emargement[$i]["NCI"].$etudiants[0]["Nom"]." ".$le_ref_etudiant[0]["Nom"]." ".$emargement[$i]["Date_emargement"]." ".$emargement[$i]["Arrivee"]." ".$emargement[$i]["Depart"];
                        if(isset($_POST["validerRechJour"]) && $tableVide==false && $emargement[$i]["Date_emargement"]==$_POST["jourRech"]||
                        !isset($_POST["validerRechJour"]) && $tableVide==false && !isset($_POST["recherche"]) && $emargement[$i]["Date_emargement"]==date('Y-m-d')||
                        !isset($_POST["validerRechJour"]) && $tableVide==false && isset($_POST["recherche"]) && !empty($_POST["aRechercher"]) && strstr(strtolower($ligne), strtolower($_POST["aRechercher"])) && $_SESSION["jourChercjer"]==$emargement[$i]["Date_emargement"] ||
                        !isset($_POST["validerRechJour"]) && $tableVide==false && $le_ref_etudiant[0]["Nom"] == $ref && isset($_POST["recherche"]) && empty($_POST["aRechercher"])) {
                            $datN = new DateTime($emargement[$i]["Date_emargement"]);
                            $date = $datN->format('d-m-Y');
                            $nbr++;
                            echo
                                '<tr class="row">
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $leNCI  .'" >' . $leNCI . '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $leNCI  .'" >' . $le_ref_etudiant[0]["Nom"]. '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $leNCI  .'" >' . $etudiants[0]["Nom"] . '</a></td>
                                    <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $leNCI  .'" >' . $date . '</a></td>
                                    <td class="col-md-1 text-center"><a class="lesliens" href="stat.php?code=' . $leNCI  .'" >' . $emargement[$i]["Arrivee"] . '</a></td>
                                    <td class="col-md-1 text-center"><a class="lesliens" href="stat.php?code=' . $leNCI  .'" >' . $emargement[$i]["Depart"] . '</a></td>
                                    <td class="col-md-1 text-center"><a href="emargement.php?aModifier='.$leNCI.'&ref='.$le_ref_etudiant[0]["Nom"].'&&date='.$emargement[$i]["Date_emargement"].'"><button class="btn btn-outline-primary" >Modifier</button></a></td>';
                                    echo'<td class="col-md-1 text-center"><a href="emargement.php?supp_em='. $leNCI.'&&date='.$emargement[$i]["Date_emargement"].'"><button class="btn btn-outline-danger bsup">Supprimer</button></a></td>';        
                        echo'</tr>';
                                
                        }
                    }
                }
                elseif(isset($_POST["valider"])){
                    $datN = new DateTime($_POST["auj"]);
                    $date = $datN->format('d-m-Y');
                    echo
                        '<tr class="row">
                            <td class="col-md-2 text-center"><a class="lesliens" href="emargement.php" >' . $_POST["code"] . '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="emargement.php" >' . $_POST["ref"] . '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="emargement.php" >' . $_POST["nom"]  . '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="emargement.php" >' . $date . '</a></td>
                            <td class="col-md-1 text-center"><a class="lesliens" href="emargement.php" >' . $_POST["arrivee"]  . '</a></td>
                            <td class="col-md-1 text-center"><a class="lesliens" href="emargement.php" >' . $_POST["depart"]  . '</a></td>
                            <td class="col-md-1 text-center"><a href="emargement.php?aModifier='.$_POST["code"] .'&ref='.$_POST["ref"].'&&date='.$_POST["auj"].'"><button class="btn btn-outline-primary" >Modifier</button></a></td>
                            <td class="col-md-1 text-center"><a href="emargement.php?supp_em='. $_POST["code"] .'&&date='.$_POST["auj"].'"><button class="btn btn-outline-danger bsup">Supprimer</button></a></td>
                        </tr>';
                        $nbr++;
                }
                ####################################------Fin Affichage-----#################################
            echo'</tbody>
                    <div id="le_nombre" class="'.$nbr.'"></div>
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
    
    if(isset($_GET["supp_em"])){
        $sonId=$_GET["supp_em"];
        $ladate=$_GET["date"];
        $sup='supp_em='.$sonId.'&&date='.$ladate
        ?>
        <script>
            if(confirm("Confirmer la suppression ?")){
                document.location.href = "traitement.php?<?php echo "$sup"; ?>"
            }
            else{
                document.location.href = "emargement.php";
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