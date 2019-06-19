<?php
session_start();
?>
<!DOCTYPE html>
<html lang="FR-fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/MonStyle.css">
    <title>Authentification</title>
</head>

<body>
    <nav  class="container nav nav-pills nav-fill">
        <a style="background-color: #008e8e;" class="nav-link active success nav-item" href="#">Authentification</a>
    </nav>
    <header></header>
    <section class="container cAuth">
        <form method="POST" action="pages/traitement.php" class="MonForm row">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <div class="row">
                    <div class="col-md-2"></div>
                    <input <?php echo'class="form-control col-md-8 espace'; if(isset($_SESSION["ancLogin"])){echo " rougMoins";} echo'"';?> type="text" id="login" name="login" placeholder="Login" <?php if(isset($_SESSION["ancLogin"])){echo 'value="'.$_SESSION["ancLogin"].'"';}?>>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input <?php echo'class="form-control col-md-8 espace'; if(isset($_SESSION["ancMDP"])){echo " rougMoins";} echo'"';?>  type="password" id="MDP" name="MDP" placeholder="Mot de passe" <?php if(isset($_SESSION["ancMDP"])){ echo 'value="'.$_SESSION["ancMDP"].'"';}?>>
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Connexion" name="submit">
                </div>
                <?php
                    if (isset( $_SESSION["reussi"]) &&  $_SESSION["reussi"]==false) { //verification du login et du MDP
               ?> 
                            <div class='row'>
                                <div class='col-md-3'></div>
                                <p class='blocAcc'>Erreur sur le login ou le mot de passe!!</p>
                            </div>
                <?php
                    }
                ?>
            </div>
        </form>
    </section>
    <?php
    include("pages/piedDePage.php");
    ?>
</body>

</html>