<?php
echo '<nav class="navbar navbar-expand-lg navbar-light row fixed-top" style="background-color: #008e8e;">
  <a class="navbar-brand" href="accueil.php"><img src="../img/favicon.png" alt="" srcset=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  
    <ul class="navbar-nav mr-auto">
      <li class="nav-item  ';if ($_SESSION["actif"] == "accueil") {echo 'active';}echo '">
        <a class="nav-link" href="accueil.php">Accueil<span class="sr-only">(current)</span></a>
      </li>';
      if($_SESSION["actif"] !="presence" && $_SESSION["actif"] !="stat" && $_SESSION["actif"] !="exportation" && $_SESSION["actif"] !="parametres"){
        echo'<li class="nav-item ';if ($_SESSION["actif"] == "ModifierEtudiant") {echo 'active';}echo '">
          <a class="nav-link" href="ModifierEtudiant.php">Gestion des apprenants</a>
        </li>
        <li class="nav-item ';if ($_SESSION["actif"] == "ModifierPromo") {echo 'active';}echo '">
          <a class="nav-link" href="ModifierPromo.php">Promos</a>
        </li>
        <li class="nav-item ';if ($_SESSION["actif"] == "ListerEtudiant") {echo 'active';}echo '">
          <a class="nav-link" href="ListerEtudiant.php">Liste apprenants</a>
        </li>
        <li class="nav-item ';if ($_SESSION["actif"] == "emargement") {echo 'active';}echo '">
          <a class="nav-link" href="emargement.php">Emargement</a>
        </li>
        <li class="nav-item ';if ($_SESSION["actif"] == "visiteur") {echo 'active';}echo '">
          <a class="nav-link" href="visiteur.php">Visiteurs</a>
        </li>
        <li class="nav-item ';if ($_SESSION["actif"] == "parametres") {echo 'active';}echo '">
          <a class="nav-link" href="parametres.php">Paramètres</a>
        </li>';
      }
      elseif($_SESSION["actif"] =="presence" ||$_SESSION["actif"] =="stat" ||$_SESSION["actif"] =="exportation" || $_SESSION["actif"] =="parametres"){
        echo'<li class="nav-item ';if ($_SESSION["actif"] == "parametres") {echo 'active';}echo '">
          <a class="nav-link" href="parametres.php">Paramètres</a>
        </li>';
        echo'<li class="nav-item ';if ($_SESSION["actif"] == "presence") {echo 'active';}echo '">
          <a class="nav-link" href="presence.php">Gestion des présences</a>
        </li>';
      }
      if($_SESSION["actif"] =="stat"){
        echo'<li class="nav-item ';if ($_SESSION["actif"] == "stat") {echo 'active';}echo '">
          <a class="nav-link" href="stat.php">Statistique</a>
        </li>';
      }
      if($_SESSION["actif"] =="presence" ||$_SESSION["actif"] =="stat" ||$_SESSION["actif"] =="exportation" || $_SESSION["actif"] =="parametres"){
        echo'<li class="nav-item ';if ($_SESSION["actif"] == "exportation") {echo 'active';}echo '">
          <a class="nav-link" href="exportation.php">Format PDF</a>
        </li>';
      }
      echo'<li class="nav-item">
        <a class="nav-link" href="deconnexion.php">Déconnexion</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" method="POST" action="">
        <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Search" name="aRechercher"';
        if(isset($_POST[ "recherche"]) && !empty($_POST[ "aRechercher"])){echo'value="'.$_POST["aRechercher"].'"'; } if ($_SESSION["actif"] == "stat" || $_SESSION["actif"] == "accueil" || $_SESSION["actif"] == "exportation") {echo ' readonly="readonly" ';} echo'>
        <button class="btn btn-outline-warning my-2 my-sm-0" type="submit" name="recherche"';if ($_SESSION["actif"] == "stat" || $_SESSION["actif"] == "accueil" || $_SESSION["actif"] == "exportation") {echo ' disabled ';} echo' >Rechercher</button>';
        if(isset($_POST[ "recherche"]) && !empty($_POST[ "aRechercher"])){echo'<button class="btn btn-success my-2 my-sm-0" type="submit" name="finRecherche">Fin</button>';}
      echo'</form>';

  echo'</div>
</nav>';
?>
<img src="" alt="" srcset="">