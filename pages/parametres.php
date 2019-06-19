<?php
session_start();
if (!isset($_SESSION["nom"])) {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
$_SESSION["actif"] = "parametres";
$admin=false;
if($_SESSION["acces"]=="admin"){
    $admin=true;
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
        .entrBouton{
            margin-left:0.2%;
        }
        .coulBout{
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
        <?php
            $tableVide=true;
            $existeDeja = false;
            $login_existe=false;
            $bon_mdp=false;
            try {
                include("connexionBDD.php");
                ############################--Debut contenu table--############################
                ///////////-----recuperation des données des agents----///////////
                $codemysql = "SELECT * FROM agents"; //le code mysql
                $agents=recuperation($connexion,$codemysql);
                ///////////-----Fin recuperation des données des agents----///////
                if(isset($agents[0][1])){
                    $tableVide=false;
                }
                ############################--Fin contenu table--##############################



                ///////////////////------Données pour modification-------//////////////////
                
                    $agent_connecte=$_SESSION["Code_agents"];
                    ///////////-----recuperation des données des agents----///////////
                    $codemysql = "SELECT * FROM agents WHERE Code_agents='$agent_connecte'"; //le code mysql
                    $donnes_agents=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des données des agents----///////
                    
                    $nom_agent_co=$donnes_agents[0]["Nom"];
                    $tel_agent_co=$donnes_agents[0]["Telephone"];
                    $login_agent_co=$donnes_agents[0]["Login"];
                    $mdp_agent_co=$donnes_agents[0]["MDP"];
                
                    ////////////////////////------mofifier son mdp si non admin--------///////////////
                    if(isset($_POST["modif_mdp"]) && $mdp_agent_co == md5($_POST["ancien_mdp"]) || isset($_POST["valider_modif"]) && $mdp_agent_co == md5($_POST["ancien_mdp"]) ){
                        $bon_mdp=true;
                    }
                    ////////////////////////------fin mofifier son mdp si non admin--------//////////////////////////            
                
                ///////////////////----Fin données pour modification-------//////////////////

                ///////////////////////----Verification du login----///////////////////////////
                if(isset($_POST["valider_ajout"]) || isset($_POST["valider_modif"]) ){
                    for($i=0;$i<count($agents);$i++){
                        if($admin==true && $agents[$i]["Login"]==$_POST["login"] && !isset($_POST["valider_modif"]) || $admin==false && $_POST["login"]!=$login_agent_co && $agents[$i]["Login"]==$_POST["login"]|| $admin==true && $_POST["login"]!=$login_agent_co && $agents[$i]["Login"]==$_POST["login"] ){
                            $login_existe=true;
                        }
                    }
                }
                ############################--Verification du login--##############################
                
        ?>
        
        <?php  if($admin==true && !isset($_POST["ajouter"]) && !isset($_POST["modifier"]) && !isset($_POST["valider_modif"]) && !isset($_POST["valider_ajout"]) && !isset($_POST["modif_mdp"]) || $admin==true && isset($_POST["valider_modif"]) && $login_existe==false && $bon_mdp==true || isset($_POST["valider_ajout"]) && $login_existe==false ) {?>
            <form method="POST" action="" class="MonForm row insc">
                <div class="col-md-3"></div>
                <div class="col-md-6 bor">
                    <div class="row">
                        <div class="col-md-2"></div>
                            <input type="submit" class="form-control col-md-4 espace" value="Ajouter un agent" name="ajouter">
                            <input type="submit" id="valider_ajout_ag" class="form-control col-md-4 espace entrBouton" value="Modifier mes informations" name="modifier">
                        </div>
                    </div>
                </div>
            </form>
        <?php } 
        elseif(isset($_POST["ajouter"]) || isset($_POST["modifier"]) || $admin==false || $admin==true && $login_existe==true || isset($_POST["valider_modif"]) && $admin==true && $bon_mdp==false || isset($_POST["modif_mdp"]) && $login_existe == false) {?>
            <form method="POST" action="" class="MonForm row insc">
                <div class="col-md-3"></div>
                <div class="col-md-6 bor">
                    <!--///////////////////////////////-------Nom------///////////////////////////////////-->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <input  type="text" id="nom_ag" name="nom" class="form-control col-md-8 espace" placeholder= "Nom de l'agent" <?php 
                        if($login_existe==true || isset($_POST["modif_mdp"])|| isset($_POST["valider_modif"]) && $bon_mdp==false){
                            echo ' value="'.$_POST["nom"].'"';
                        }
                        elseif(!isset($_POST["ajouter"]) ){
                            echo ' value="'.$nom_agent_co.'"';
                        }
                             ?>>
                    </div>
                    <!--################################------Fin Nom------##############################-->

                    <!--///////////////////////////////-------Telephone------///////////////////////////////////-->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <input  type="number" id="tel_ag" name="tel" class="form-control col-md-8 espace" placeholder= "Téléphone" <?php 
                        if($login_existe==true || isset($_POST["modif_mdp"])|| isset($_POST["valider_modif"]) && $bon_mdp==false){echo ' value="'.$_POST["tel"].'"';}
                        elseif(!isset($_POST["ajouter"]) ){echo ' value="'.$tel_agent_co.'"';}?>>
                    </div>
                    <!--################################-----Fin Telephone-------###################################-->

                    <!--///////////////////////////////-------Login------///////////////////////////////////-->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <?php if($login_existe==false){ ?>
                        <input  type="text" id="login_ag" name="login" class="form-control col-md-8 espace" placeholder= "Login" <?php 
                        if(isset($_POST["modif_mdp"])|| isset($_POST["valider_modif"]) && $bon_mdp==false){echo ' value="'.$_POST["login"].'"';} elseif(!isset($_POST["modif_mdp"]) && !isset($_POST["ajouter"])){echo ' value="'.$login_agent_co.'"';}  ?>>
    
                        <?php } else { 
                        echo'<input type="text" id="login_ag" name="login" class="form-control col-md-8 espace rougMoins" placeholder="Login" value= "'.$_POST["login"].' existe déjà">';
                        }?>
                    </div>
                    <!--################################------Fin Login-------###################################-->

                    <!--///////////////////////////////-------Ancien mot de passe------///////////////////////////////////-->
                    <?php if($admin==false || $admin==true && isset($_POST["modifier"]) || $admin==true && isset($_POST["valider_modif"]) && $login_existe==true || $admin==true && isset($_POST["valider_modif"]) && $bon_mdp==false || isset($_POST["modif_mdp"]) && $bon_mdp==false || isset($_POST["modif_mdp"]) && $bon_mdp==true){?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <?php if(!isset($_POST["modif_mdp"]) && !isset($_POST["valider_modif"]) || isset($_POST["modif_mdp"]) && $bon_mdp==true|| isset($_POST["valider_modif"]) && $bon_mdp==true ){?>
                                
                                <input type="password" id="ancien_mdp_ag" name="ancien_mdp" class="form-control col-md-4 espace" placeholder= "Ancien mot de passe" <?php if(isset($_POST["modif_mdp"]) && $bon_mdp==true){echo'value="'.$_POST['ancien_mdp'].'"';}?>>
                            <?php }elseif(isset($_POST["modif_mdp"]) && $bon_mdp==false || isset($_POST["valider_modif"]) && $bon_mdp==false ) { ?>
                                    
                                <input type="password" id="ancien_mdp_ag" name="ancien_mdp" class="form-control col-md-4 espace rougMoins" placeholder= "Erreur sur le mot de passe">
                            <?php }?>
                            <input type="submit" id="" class="form-control col-md-4 espace entrBouton" value="Modifier mot de passe" name="modif_mdp">
                        </div>
                    <?php } ?>
                    <!--##############################------Fin Ancien mot de passe-------###################################-->

                    <!--///////////////////////////////------changer Mot de passe et confirmation-------///////////////////////////////////-->
                    <?php if(isset($_POST["modif_mdp"]) && $bon_mdp==true || $admin==true && isset($_POST["ajouter"]) || $admin==true && isset($_POST["valider_ajout"]) && $login_existe==true){ ?>
                        
                        <!--///////////////////////////////------Nouveau mdp-------///////////////////////////////////-->
                        <div class="row">
                            <div class="col-md-2"></div>
                            <input  type="password" id="mdp_ag" name="mdp" class="form-control col-md-8 espace" placeholder= "Nouveau mot de passe" <?php 
                            if($login_existe==true){echo ' value="'.$_POST["mdp"].'"';}?>>
                        </div>
                        <!--################################------Fin Nouveau mdp-------###################################-->

                        <!--///////////////////////////////-------Confirmation mdp------///////////////////////////////////-->
                        <div class="row">
                            <div class="col-md-2"></div>
                            <input  type="password" id="confMdp_ag" name="confMdp" class="form-control col-md-8 espace" placeholder= "Confirmez le mot de passe" <?php if($login_existe==true){echo ' value="'.$_POST["confMdp"].'"';} ?>>
                        </div>
                        <!--##############################------Fin Confirmation mdp-------###################################-->
                    
                    <?php } ?>
                    <!--################################------Fin changer Mot de passe et confirmation-------###################################-->

                    <!--///////////////////////////////------Les boutons-------///////////////////////////////////-->
                    <div class="row">
                        <div class="col-md-2"></div>

                            <input type="submit" class="form-control col-md-4 espace" value="Annuller" name="Annuller">
                            <!--///////////////////////////////--------Lors de l'ajout-------///////////////////////////////////-->
                            <?php if(isset($_POST["ajouter"]) && $admin==true || isset($_POST["valider_ajout"]) && $admin==true && $login_existe==true){ ?>
                                <input type="submit" id="valider_ajout_ag" class="form-control col-md-4 espace" value="Ajouter" name="valider_ajout">
                            <?php }
                            ################################-----Fin Lors de l'ajout--------###################################-->

                            ///////////////////////////////-------Lors de la modification------///////////////////////////////////-->
                            elseif(!isset($_POST["ajouter"]) && isset($_POST["modifier"])|| $admin==false|| $admin==true && $login_existe==true || isset($_POST["modif_mdp"]) && $login_existe == false || isset($_POST["valider_modif"]) && $bon_mdp==false) { ?>
                                <input type="submit"  class="form-control col-md-4 espace entrBouton" value="Modifier" name="valider_modif" 
                                <?php if($admin==false) {?> id="valider_modif_ag" <?php } else { ?> id="valider_modif_adm"<?php }?>>
                            <?php } ?>
                            <!--################################------Fin lors de la modification-------###################################-->

                        </div>
                    </div>
                    <!--################################------Fin Les boutons-------###################################-->
                </div>
            </form>
        <?php } ?>
            <?php
            
            ///////////////////////////////////------Debut Ajouter-----////////////////////////////////
            if (isset($_POST["valider_ajout"]) && $login_existe == false) {
                ///////////-----recuperation des données des agents----///////////
                $codemysql = "SELECT Code_agents FROM agents"; //le code mysql
                $code_des_agents=recuperation($connexion,$codemysql);
                ///////////-----Fin recuperation des données des agents----///////
                if(isset($code_des_agents[0]["Code_agents"])){
                    $Code_agents=$code_des_agents[count($code_des_agents)-1]["Code_agents"];//l'id du dernier visiteur
                    $Code_agents=str_replace(" AS","",$Code_agents);
                    $Code_agents=$Code_agents+1;
                    $Code_agents=$Code_agents." AS";
                }
                else{
                    $Code_agents="1 AS";
                }
                $nom = securisation($_POST["nom"]);
                $tel = securisation($_POST["tel"]);
                $login = securisation($_POST["login"]);
                $mdp = md5(securisation($_POST["mdp"]));//chiffrer en md5
                $statut="Actif";
                $acces="user";
                $codemysql = "INSERT INTO `agents` (Code_agents,Nom,Telephone,Login,MDP,statut,acces)
                            VALUES(:Code_agents,:Nom,:Telephone,:Login,:MDP,:statut,:acces)"; //le code mysql
                $requete = $connexion->prepare($codemysql);
                $requete->bindParam(":Code_agents", $Code_agents);
                $requete->bindParam(":Nom", $nom);
                $requete->bindParam(":Telephone", $tel);
                $requete->bindParam(":Login", $login);
                $requete->bindParam(":MDP", $mdp);
                $requete->bindParam(":statut", $statut);
                $requete->bindParam(":acces", $acces);
                $requete->execute(); //excecute la requete qui a été preparé
            }
            ####################################------Fin Ajouter-----#################################

            // ///////////////////////////////////------Debut Modification-----///////////////////////////
            if (isset($_POST["valider_modif"])  && $admin == false && $login_existe==false && $bon_mdp==true ||isset($_POST["valider_modif"])  && $admin == true && $login_existe==false && $bon_mdp==true) {
                $sonId=$_SESSION["Code_agents"];
                $nom = securisation($_POST["nom"]);
                $tel = securisation($_POST["tel"]);
                $login = securisation($_POST["login"]);
                
                $codemysql = "UPDATE `agents` SET Nom='$nom',Telephone='$tel',Login='$login' WHERE Code_agents='$sonId' ";
                $requete = $connexion->prepare($codemysql);
                $requete->execute();                   
                
                if(isset($_POST["mdp"]) && !empty($_POST["mdp"])){
                    $mdp = md5(securisation($_POST["mdp"]));
                    $codemysql = "UPDATE `agents` SET mdp='$mdp' WHERE Code_agents='$sonId' ";
                    $requete = $connexion->prepare($codemysql);
                    $requete->execute();
                }
               echo'<script>alert("Modification réussie");</script>';
               if($admin == false){
                   echo'<script>document.location.href="parametres.php";</script>';
               }
            }
            ####################################------Fin Modification----#############################

            if($admin==true) {
                ///////////////////////////////////------Debut Affichage-----////////////////////////
                $nbr=0;
                if($tableVide==false || isset($_POST["valider_ajout"]) && $login_existe == false){
                    echo'<table class="col-12 table tabliste table-hover">
                    <thead class="">
                        <tr class="row">
                            
                            <td class="col-md-2 text-center gras">Code</td>
                            <td class="col-md-2 text-center gras">Login</td>
                            <td class="col-md-2 text-center gras">Nom</td>
                            <td class="col-md-2 text-center gras">Téléphone</td>
                            <td class="col-md-2 text-center gras">Statut</td>
                            <td class="col-md-2 text-center gras">Supprimer</td>
                        </tr>
                    </thead>
                    <tbody id="developers">';
                }    
                if(isset($_POST["valider_ajout"]) && $login_existe == false){
                    $login=$_POST["login"];
                    ///////////-----recuperation des données des agents----///////////
                    $codemysql = "SELECT Code_agents,statut FROM agents WHERE Login='$login'"; //le code mysql
                    $inf_agents=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des données des agents----///////
                    echo
                        '<tr class="row">
                            <td class="col-md-2 text-center"><a class="lesliens" href="parametres.php">' . securisation($inf_agents[0]["Code_agents"]) . '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="parametres.php" >' . securisation($_POST["login"]) . '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="parametres.php" >' . securisation($_POST["nom"]). '</a></td>
                            <td class="col-md-2 text-center"><a class="lesliens" href="parametres.php" >' . securisation($_POST["tel"]). '</a></td>
                            <td class="col-md-2 text-center"><a class="nonSoulign" href="traitement.php?code_agents=' . $inf_agents[0]["Code_agents"] . '&statut='.$inf_agents[0]["statut"].'" ><button class="btn '; if($inf_agents[0]["statut"]=="Actif"){echo'btn-outline-success';} else{echo'btn-outline-danger';} echo' coulBout">'.$inf_agents[0]["statut"].'</button></a></td>
                            <td class="col-md-2 text-center"><a class="nonSoulign" href="parametres.php?code_agents_a_supp=' . $inf_agents[0]["Code_agents"]. '" ><button class="btn btn-outline-danger ">Supprimer</button></a></td> 
                        </tr>';
                }
                else{
                    ///////////-----recuperation des données des agents----///////////
                    $codemysql = "SELECT * FROM agents"; //le code mysql
                    $agents=recuperation($connexion,$codemysql);
                    ///////////-----Fin recuperation des données des agents----///////

                    for($i=0;$i<count($agents);$i++) {
                        $ligne = $agents[$i]["Code_agents"]." ".$agents[$i]["Login"]." ".$agents[$i]["Nom"]." ".$agents[$i]["Telephone"]." ".$agents[$i]["statut"];
                        if ($tableVide==false && !isset($_POST["recherche"]) || isset($_POST["recherche"]) && !empty($_POST["aRechercher"]) && strstr(strtolower($ligne), strtolower($_POST["aRechercher"])) || $tableVide==false && isset($_POST["recherche"]) && empty($_POST["aRechercher"])) {
                        //si la table n'est pas vide et que on ne recherche rien                          //si on recherche une chose non vide et que cela face partie de la ligne                                 //si on appuis sur le bouton rechercher alors qu'on n'a rien ecrit afficher tous les éléments                                      
                            echo
                                '<tr class="row">
                                    <td class="col-md-2 text-center">' . $agents[$i]["Code_agents"] . '</td>
                                    <td class="col-md-2 text-center">' . $agents[$i]["Login"] . '</td>
                                    <td class="col-md-2 text-center">' . $agents[$i]["Nom"]. '</td>
                                    <td class="col-md-2 text-center">' . $agents[$i]["Telephone"] . '</td>
                                    <td class="col-md-2 text-center"><a class="nonSoulign" href="traitement.php?code_agents=' . $agents[$i]["Code_agents"]. '&statut='.$agents[$i]["statut"].'" ><button class="btn '; if($agents[$i]["statut"]=="Actif"){echo'btn-outline-success';} else{echo'btn-outline-danger';} echo' coulBout">'.$agents[$i]["statut"] .'</button></a></td>
                                    <td class="col-md-2 text-center"><a class="nonSoulign" href="parametres.php?code_agents_a_supp=' . $agents[$i]["Code_agents"]. '" ><button class="btn btn-outline-danger ">Supprimer</button></a></td> 
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
            if($admin==false){
                echo'<div class="bas"></div>';
            }
        }
        catch (PDOException $e) {
            echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
        }
            ?>
        
    </section>
    <?php
    include("piedDePage.php");
    if(isset($_GET["code_agents_a_supp"]) && $_GET["code_agents_a_supp"]!=$_SESSION["Code_agents"]){
        $sonId=$_GET["code_agents_a_supp"];
        $sup='code_agents_a_supp='.$sonId
        ?>
        <script>
            if(confirm("Confirmer la suppression ?")){
                 document.location.href = "traitement.php?<?php echo "$sup"; ?>"
            }
            else{
                document.location.href = "parametres.php";
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