<?php




namespace Nb;

class Nb
{

    private $erreur_pages;
    private $erreur_tables;
    private $erreur_nom;
    private $erreur_globale;
    private $erreur_chemin;

    public function generate_Nb()
    {
        session_start();


        // On recupere les valeurs 
        $nb_pages = filter_input(INPUT_POST, "nb_pages", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nb_tables = filter_input(INPUT_POST, "nb_tables", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $chemin = filter_input(INPUT_POST, "path", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reg_nb = filter_input(INPUT_POST, "reg_nb", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Vérification de l'integrité des donnés 
        if (isset($reg_nb)) {
            $erreur = 0;
            $erreur_pages = "";
            $erreur_tables = "";
            $erreur_nom = "";
            $erreur_chemin = "";


            if (!isset($nb_pages)) {
                $erreur_pages = "Nb Tables manquant *";
                $erreur++;
            } else {
                if (!is_numeric($nb_pages)) {
                    $erreur_pages = "Ce champ n'est pas un nombre *";
                    $erreur++;
                } else {
                    if ($nb_pages > 15) {
                        $erreur_pages = "Ce champ est trop grand *";
                        $erreur++;
                    } else {
                        if ($nb_pages <= 0){
                            $erreur_pages = "Ce champ est trop petit";
                            $erreur++;
                        }
                    }
                }
            }

            if (!isset($nom) or $nom == "") {
                $erreur_nom = "Nom manquant *";
                $erreur++;
            }

            if (!isset($chemin) or $chemin == "") {
                $erreur_chemin = "Chemin manquant *";
                $erreur++;
            } else {
                if (!file_exists($chemin)) {
                    $erreur_chemin = "Chemin incorrecte *";
                    $erreur++;
                }
            }

            if (!isset($nb_tables)) {
                $erreur_tables = "Nb Tables manquant *";
                $erreur++;
            } else {
                if (!is_numeric($nb_tables)) {
                    $erreur_tables = "Ce champ n'est pas un nombre *";
                    $erreur++;
                } else {
                    if ($nb_tables > 15) {
                        $erreur_tables = "Ce champ est trop grand *";
                        $erreur++;
                    }
                }
            }

            $this->erreur_pages = $erreur_pages;
            $this->erreur_tables = $erreur_tables;
            $this->erreur_nom = $erreur_nom;
            $this->erreur_chemin = $erreur_chemin;

            // Si il n'y a pas d'erreur 
            if ($erreur == 0) {
                $_SESSION['nb_pages'] = $nb_pages;
                $_SESSION['nb_tables'] = $nb_tables;
                $_SESSION['nom_projet'] = $nom;
                $_SESSION['chemin'] = $chemin;
                header('location:./generate');
            } else {
                $erreur_globale = "Champs incorrecte *";
                $this->erreur_globale = $erreur_globale;
            }
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

    public function getErreurNom()
    {
        return $this->erreur_nom;
    }

    public function getErreurChemin()
    {
        return $this->erreur_chemin;
    }

    public function getErreur()
    {
        return $this->erreur_globale;
    }
}
