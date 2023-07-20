<?php

namespace Gene;

class Gene
{

    private $erreur_pages;
    private $erreur_tables;
    private $erreur_globale;

    public function generate_Nb()
    {
        session_start();
        if (isset($_SESSION['nb_pages']) and isset($_SESSION['nb_tables']) and isset($_SESSION['nom_projet']) and isset($_SESSION['chemin'])) {

            // Récupération des données 
            $nom = $_SESSION['nom_projet'];
            $chemin = $_SESSION['chemin'];
            $reg_generate = filter_input(INPUT_POST, "reg_generate", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (isset($reg_generate)) {

                $erreur = 0;
                $erreur_globale = "";

                $nom_bd = filter_input(INPUT_POST, "nom_bd", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $user_bd = filter_input(INPUT_POST, "user_bd", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $password_bd = filter_input(INPUT_POST, "password_bd", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $hote_bd = filter_input(INPUT_POST, "hote_bd", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // Pages
                for ($i = 0; $i < $_SESSION['nb_pages']; $i++) {
                    if (!isset($_POST['pages'][$i]) or $_POST['pages'][$i] == "") {
                        $erreur++;
                    }
                }

                // Table 
                for ($i = 0; $i < $_SESSION['nb_tables']; $i++) {
                    if (!isset($_POST['tables'][$i]) or $_POST['tables'][$i] == "") {
                        $erreur++;
                    }
                }





                // On créé le projet si il n'y a aucune erreur 
                if ($erreur == 0) {

                    // Vérifie si le répertoire existe :
                    if (is_dir($nom)) {

                        echo 'Le répertoire existe déjà!';
                    } else {



                        // Si le fichier n'existe pas on le créé 
                        if (!file_exists("$chemin/$nom")) {

                            // Création du nouveau répertoire
                            if (!mkdir("$chemin/$nom")) {

                                $erreur++;
                            } else {
                                $chemin_ini = $chemin;


                                $erreur_globale = "Le projet a été créé *";

                                for ($y = 0; $y < 2; $y++) {
                                    $chemin = "$chemin_ini/$nom";

                                    if ($y == 1) {
                                        break;
                                    }

                                    // On créé tout les sous-dossier et fihiers nécessaire 
                                    mkdir("$chemin/view");

                                    // Pages 
                                    for ($i = 0; $i < $_SESSION['nb_pages']; $i++) {

                                        $nom_pages = $_POST['pages'][$i];
                                        $Nom_view = ucfirst($nom_pages);
                                        $Nom_fichier = $Nom_view;
                                        $controller = $Nom_fichier . "Controller";
                                        $view = fopen("$chemin/view/$nom_pages.php", 'w');

                                        // ECRITURE DE LA VUE
                                        if ($_SESSION['nb_tables'] > 0) {
                                            fwrite($view, "<?php

// Database connexion 
require('../Config/setup.php');
                                            
// Controller 
require('../Controller/$controller.php');
                                            
                                            
// utilisation de contact class 
use $Nom_view\\$Nom_view;
                                            
// appel de la class
$$nom_pages = new $Nom_view;
                                            
// Lancement de la fonction
//\$nom_pages->nom_methode();
                                            
                                            
?>


<!DOCTYPE html>
<html lang='fr'>

<head>

    <?php include('./partials/head.php') ?>
    <title>Title</title>

</head>

<body>

    <!-- Le haut de page -->
    <header>
        <?php include('./partials/header.php') ?>
    </header>

    <!-- Le contenue principal de la page -->
    <main>

    </main>

    <!-- Le bas de page -->
    <footer>
        <?php include('./partials/footer.php') ?>
    </footer>


</body>

</html>");
} else {
fwrite($view, "<?php
                                            
// Controller 
require('../Controller/$controller.php');
                                            
                                            
// utilisation de contact class 
use $Nom_view\\$Nom_view;
                                            
// appel de la class
$$controller = new $Nom_view;
                                            
?>
");
}
}

mkdir("$chemin/view/partials");
fopen("$chemin/view/partials/footer.php", 'w');
fopen("$chemin/view/partials/header.php", 'w');
fopen("$chemin/view/partials/head.php", 'w');

mkdir("$chemin/assets");
mkdir("$chemin/assets/css");
fopen("$chemin/assets/css/style.css", 'w');

mkdir("$chemin/assets/images");

// Dztabase
if ($_SESSION['nb_tables'] > 0) {

// Config database
mkdir("$chemin/Config");

$core = fopen("$chemin/Config/Core.php", 'c+b');

// ECRITURE DU CORE
fwrite($core, "<?php 

class Core{

    static \$bdd;
                                    
    static function getDatabase(){
                                        
        if(!self::\$bdd){
            return new Database('$user_bd','$password_bd', '$nom_bd');
            }
        return self::\$bdd;
    }
}");

                                        $database = fopen("$chemin/Config/Database.php", 'c+b');

                                        // ECRITURE DE LA BDD 
                                        fwrite($database, "<?php

require('../Controller/functions/historisation.php');

class Database
{
                                    
    private \$bdd;
    private \$erreur_requete;
                                        
    public function __construct(\$user, \$password, \$db_name, \$host = '$hote_bd')
    {
        try {
            // Connexion bd 
            \$this->bdd = new PDO('mysql:host='.\$host.';dbname='.\$db_name.'', \$user, \$password);
            // set the PDO error mode to exception
            \$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException \$e) {
        echo 'Connection failed: ' . \$e->getMessage();
        }
    }

    public function query(\$query, \$param = array())
    {

        try {
            if (\$param) {
                // Condition 
                \$sql = \$this->bdd->prepare(\$query);
                \$sql->execute(\$param);
            } else {
                // Condition 

                \$sql = \$this->bdd->prepare(\$query);
                \$sql->execute();
            }

            return \$sql;
        } catch (PDOException \$e) {
            //SI IL Y A UNE ERREUR L'ENSEMBLE DES REQUETES EST ANNULEE ON REVIENT A L'ETAT INITIAL
            \$this->bdd->beginTransaction();
            // \$this->bdd->rollBack();
            //GESTION DES ERREURS
            \$erreur =  '--ERREUR REQUETE LE ' . date('d/m/y H:i:s') . '--\n';
            \$erreur = \$erreur . '[FICHIER] : ' . \$e->getFile() . '\n';
            \$erreur = \$erreur . '[LIGNE] : ' . \$e->getLine() . '\n';
            \$erreur = \$erreur . '[CODE] : ' . \$e->getCode() . '\n';
            \$erreur = \$erreur . '[MESSAGE] : ' . \$e->getMessage() . '\n';
            // LA NOTATION .= revient a la meme chose qu'au dessus
            \$erreur .=  '[IP USER] : ' . \$_SERVER['REMOTE_ADDR'] . '\n';
            // historisation('log', 'erreur_requete', \$erreur);
            //ERREUR POUR L'ECRAN USER
            echo \"<div style='margin-top: 100px; margin-left:10px; color:red; font-weight:700;'>UNE ERREUR EST SURVENUE **</div>\";
        }
    }
}");


                                        $setup = fopen("$chemin/Config/setup.php", 'c+b');
                                        fwrite($setup, "<?php \n require('../Config/Core.php'); \n require('../Config/Database.php');");

                                        // Model 
                                        mkdir("$chemin/Model");

                                        for ($i = 0; $i < $_SESSION['nb_tables']; $i++) {

                                            $nom_tables = $_POST['tables'][$i];
                                            $Name_tables = ucfirst($nom_tables);
                                            $Name_page = $Name_tables . "Model";
                                            $model = fopen("$chemin/Model/$Name_page.php", 'c+b');

                                            // ECRITURE DU MODEL 
                                            fwrite($model, "<?php

// Permet d'avoir le fichier nommé contact un seul fois le rendre unique
namespace Model$Name_tables;
                                        
use Core;

class Model$Name_tables {

    // Select
    public function select$Name_tables()
        {
            // Connexion a la bd
            \$bdd = Core::getDatabase();
            // Requête SQL
            \$sql = \$bdd->query(\"SELECT * FROM $nom_tables\");
            \$result = \$sql->fetchAll();
            return \$result;
        }
}");
                                        }
                                    }


                                    mkdir("$chemin/assets/js");

                                    mkdir("$chemin/Controller");

                                    for ($i = 0; $i < $_SESSION['nb_pages']; $i++) {

                                        $nom_pages = $_POST['pages'][$i];
                                        $Name_pages = ucfirst($nom_pages);
                                        $Nom_fichier = $Name_pages . "Controller";
                                        $controller = fopen("$chemin/Controller/$Nom_fichier.php", 'c+b');

                                        // ECRITURE CONTROLLER 
                                        fwrite($controller, "<?php


// Permet d'avoir le fichier nommé contact un seul fois le rendre unique 
namespace $Name_pages;
                
// require('../Model/NomModel.php');

// use ModelNom\ModelNom;
                                    

                                    
                                    
// class 
class $Name_pages
{
    public function __construct() 
    {
        // Your code PHP Here
    }
}");
                                    }
                                    mkdir("$chemin/Controller/functions");
                                    $controller = fopen("$chemin/Controller/functions/historisation.php", 'c+b');
                                    fwrite($controller, "<?php 

function historisation(\$dossier, \$fichier, \$message) {
    // NOM DU DOSSIER AVEC / au bout  (\$dossier = log2/)
    \$dossier = \$dossier . '/';
    //FICHIER ( concatenation valeur dossier avec / + nom fichier + extension (.txt))
    // \$fichier = log2/loguser.txt
    \$fichier = \$dossier . \$fichier . '.txt';
    //AJOUTE UN RETOUR A LA LIGNE AU MESSAGE DONNE PAR LA PERSONNE QUI LANCE LA FONCTION
    // \$message = Inscription ok 
    \$message = \$message . '\n';
                                        
    //SI LE DOSSIER N'EXISTE PAS, PHP LE CREE AVEC MKDIR
    // is_dir(\$dossier) renvoi false si le dossier n'existe
    //est-ce que false == false 
    // c'est vrai donc j'execute les instructions du alors
    if (is_dir(\$dossier) == false) {
        mkdir(\$dossier);
    }
                                        
    //POUR LE FICHIER
    if (file_exists(\$fichier)) {
        //RECUPERATION DU CONTENU
        \$contenu = file_get_contents(\$fichier);
        // DANS LA VARIABLE ON AJOUTE LA NOUVELLE LIGNE
        \$message = \$contenu . \$message;
        /*
        \$CONTENU = 'DEPART : \n' et en plus 'info blablabla [02/03/22 09:30:20] \n'
        */
    }
                                        
    //SI LE FICHIER EXISTE PAS, LA FONCTION CREE LE FICHIER AVEC LE CONTENU DONNE
    //SI LE FICHIER EXISTE, ECRASE LE FICHIER EXISTANT AVEC LE CONTENU DONNE
    file_put_contents(\$fichier, \$message);
    }");
                                }



                                $this->erreur_globale = $erreur_globale;
                            }
                        } else {
                            $erreur_globale = "Le dossier existe déjà *";
                            $this->erreur_globale = $erreur_globale;
                        }
                    }
                } else {
                    $erreur_globale = "Champs invalide *";
                    $this->erreur_globale = $erreur_globale;
                }
            }
        } else {
            header('location:../');
        }
    }

    public function getErreurPages()
    {
        return $this->erreur_pages;
    }

    public function getErreurTables()
    {
        return $this->erreur_tables;
    }

    public function getErreur()
    {
        return $this->erreur_globale;
    }
}