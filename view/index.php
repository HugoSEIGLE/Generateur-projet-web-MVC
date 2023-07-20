<?php

// Controller 
require('../Controller/Nb_Ctrl.php');

// Composer 
require('../vendor/autoload.php');

use Nb\Nb;

$nb = new Nb;

$nb->generate_Nb();

?>


<!DOCTYPE html>
<html lang="en">
    
<!-- 
#      Hugo seigle
#         _, _,
#       .' .' |
#      /  /   /
#     _|_/__.'
#    .'   `\
#   ( ^     \
#    '.__    \
#       _)    '.
#   _.-'/       '.
#  (__.'          \
#   .' .-.         ;
#  (_.'   \        |`\
#       .--\      /   |
#      (__.-'    /'--'
#    (______( 
-->

<head>
    <?php include('./partials/head.php') ?>
    <title>Générateur de projet, Hugo Seigle</title>
</head>

<body class="bg-dark">
    <header>
        <?php include('./partials/header.php') ?>
    </header>

    <main>
        <form action="" method="post" class="mt-3 mb-3">
            <center><?php echo $nb->getErreur(); ?></center>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nom du projet</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nom du projet" name="nom">
                <span><?php echo $nb->getErreurNom(); ?></span>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Chemin</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Chemin ex: C:\example\projet\" name="path">
                <span><?php echo $nb->getErreurChemin(); ?></span>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nombre de pages</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nombre de pages" name="nb_pages">
                <span><?php echo $nb->getErreurPages(); ?></span>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nombre de tables</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nombre de tables" name="nb_tables">
                <span><?php echo $nb->getErreurTables(); ?></span>
            </div>

            <button type="sumbit" class="btn btn-primary" name="reg_nb">Générer</button>

        </form>
    </main>

    <footer class="bg-dark">
        <?php include('./partials/footer.php') ?>
    </footer>
</body>

</html>