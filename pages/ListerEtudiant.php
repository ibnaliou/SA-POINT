<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "ListerEtudiant";
if (isset($_GET["ref"])) {
    $ref = $_GET["ref"];
} 
elseif (isset($_POST["ref"])) {
    $ref = $_POST["ref"];
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
    <title>Liste des étudiants</title>
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
    <section class="container-fluid pageLister">
        <form method="POST" action="ListerEtudiant.php" class="MonForm row insc">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <?php
            try {
                include("connexionBDD.php");

                ///////////////////////////-------ref---------------------//////////////////////
                ///////////-----recuperation des données de la table ref----///////////
                $codemysql = "SELECT Nom FROM promo";
                $lesPromos=recuperation($connexion,$codemysql);
                ///////////-----Fin recuperation des données de la table ref----///////////
                $nom_des_dev=$lesPromos[0]["Nom"];
                echo '<div class="row">
                        <div class="col-md-2"></div>
                        <select class="form-control col-md-8 espace" name="ref">';
                if(!isset($_POST["recherche"]) && !isset($_POST["aRechercher"])||isset($_POST["recherche"]) && empty($_POST["aRechercher"])|| isset($_POST["finRecherche"])){
                    for($i=0;$i<count($lesPromos);$i++) {
                        if ($ref == $lesPromos[$i]["Nom"]) {
                            echo '<option value="' . $lesPromos[$i]["Nom"]. '" selected>' . $lesPromos[$i]["Nom"] . '</option>';
                        } 
                        else {
                            echo '<option value="' . $lesPromos[$i]["Nom"] . '">' . $lesPromos[$i]["Nom"] . '</option>';
                        }
                    }
                }
                elseif(isset($_POST["recherche"])){
                    echo '<option value="">Rechercher dans tous les référentiels</option>';
                }
                echo '</select>
                    </div>';
                ///////////////////////////-------Fin ref---------------------//////////////////////

                ///////////////////////////---------Nom---------------------//////////////////////
                echo '<div class="row">
                <div class="col-md-2"></div>
                <input type="text" class="form-control col-md-8 espace" '; if(isset($_POST["nom"])){echo' value="'.$_POST["nom"].'" ';}  echo' name="nom" placeholder="Nom de l\'apprenant">
                </div>';
                ///////////////////////////-------Fin Nom---------------------//////////////////////

                echo '<div class="row">
                <div class="col-md-3"></div>
                <input type="submit" class="form-control col-md-6 espace" value="Lister" name="valider">
                </div>';
                ?>
            </div>
        </form>
        <?php
        
            echo '<table class="col-12 table tabliste table-hover">
            <thead class="">
                <tr class="row">
                    <td class="col-md-2 text-center gras">N° CI</td>
                    <td class="col-md-2 text-center gras">Référentiel</td>
                    <td class="col-md-2 text-center gras">Nom</td>
                    <td class="col-md-2 text-center gras">Téléphone</td>
                    <td class="col-md-3 text-center gras">Email</td>
                    <td class="col-md-1 text-center gras">Emarger</td>
                </tr>
            </thead>
            <tbody id="developers">';

            /////////////////////////////////////////------Debut Affichage-----///////////////////////// 
            $actualisation=false;
            $nbr=0;
            
            ///////////-----recuperation des données des etudiants----///////////
            $codemysql = "SELECT * FROM etudiants"; //le code mysql
            $etudiants=recuperation($connexion,$codemysql);
            ///////////-----Fin recuperation des données des etudiants----///////
            
            for($i=0;$i<count($etudiants);$i++) {
                ///////////-----recuperation des données de la table ref----///////////
                $NCI_etudiant=$etudiants[$i]["NCI"];
                $codemysql = "SELECT promo.Nom FROM promo INNER JOIN etudiants ON promo.id_promo=etudiants.id_promo WHERE etudiants.NCI='$NCI_etudiant'"; //le code mysql
                $le_ref_etudiant=recuperation($connexion,$codemysql);
                ///////////-----Fin recuperation des données de la table ref----////////
                $ligne = $NCI_etudiant." ".$le_ref_etudiant[0]["Nom"]." ".$etudiants[$i]["Nom"]." ".$etudiants[$i]["Telephone"]." ".$etudiants[$i]["Email"];
                if (!isset($_POST["valider"]) && isset($le_ref_etudiant[0]["Nom"]) && $le_ref_etudiant[0]["Nom"] == $nom_des_dev && !isset($_GET["ref"])){
                    $ref=$nom_des_dev;
                    $actualisation=true;
                }
                if ($actualisation==true && !isset($_POST["recherche"])&& $le_ref_etudiant[0]["Nom"] == $nom_des_dev||isset($ref) && isset($le_ref_etudiant[0]["Nom"]) && $le_ref_etudiant[0]["Nom"] == $ref && empty($_POST["nom"]) && !isset($_POST["recherche"])|| 
                isset($ref) && isset($le_ref_etudiant[0]["Nom"]) && $le_ref_etudiant[0]["Nom"] == $ref && !empty($_POST["nom"]) && strstr(strtolower($etudiants[$i]["Nom"]),strtolower($_POST["nom"]))&& !isset($_POST["recherche"])||
                isset($_POST["recherche"]) && !empty($_POST["aRechercher"]) && strstr(strtolower($ligne), strtolower($_POST["aRechercher"])) || 
                $NCI_etudiant != "" && isset($_POST["recherche"]) && empty($_POST["aRechercher"])&& $le_ref_etudiant[0]["Nom"] == $nom_des_dev) {
                    
                    echo
                        '<tr class="row">
                            <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $NCI_etudiant . '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $le_ref_etudiant[0]["Nom"] . '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $etudiants[$i]["Nom"]. '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $etudiants[$i]["Telephone"] . '</a></td>
                            <td class="col-md-3 text-center"><a class="lesliens" href="stat.php?code=' . $NCI_etudiant .'" >' . $etudiants[$i]["Email"] . '</a></td>
                            <td class="col-md-1 text-center"><a href="emargement.php?code=' . $NCI_etudiant . '&ref=' . $le_ref_etudiant[0]["Nom"] .  '"   id="' . $NCI_etudiant . '" ><button class="btn btn-outline-success" >Emarger</button></a></td>
                        </tr>';
                        $nbr++;
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