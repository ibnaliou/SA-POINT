<?php
            $serveur = "localhost";
            $Monlogin = "root";
            $Monpass = "";
            $connexion = new PDO("mysql:host=$serveur;dbname=sonatelacad;charset=utf8", $Monlogin, $Monpass); //se connecte au serveur mysquel
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute — Configure l'attribut PDO $connexion


            function recuperation($la_connexion,$le_codemysql){
                $requete = $la_connexion->prepare($le_codemysql); //Prépare la requête $codemysql à l'exécution
                $requete->execute();
                $donnee=$requete->fetchAll();
                return $donnee;
            }
             function securisation($donnees){
                        $donnees = trim($donnees); //trim supprime les espaces (ou d'autres caractères) en début et fin de chaîne
                        $donnees = stripslashes($donnees); //Supprime les antislashs d'une chaîne
                        $donnees = strip_tags($donnees); //neutralise le code html et php
                        $donnees = addcslashes($donnees, '%_'); //pour gerer les injections sql qui visent notamment à surcharger notre serveur en alourdissant notre requête. Ce type d'injection utilise les caractères % et _.
                        return $donnees;
            }

?>