<?php

// Controller 
require('../Controller/Generate_Ctrl.php');

// Composer 
require('../vendor/autoload.php');

use Gene\Gene;

$name = new Gene;

$name->generate_Nb();

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
    <title>Générateur MVC, Hugo Seigle</title>
</head>

<body class="bg-dark">
    <header>
        <?php include('./partials/header.php') ?>
    </header>

    <main>
        <form action="" method="post" class="mt-3 mb-3">
            <center><?php echo $name->getErreur(); ?></center>
            <h2>Pages :</h2>
            <?php for ($i = 0; $i < $_SESSION['nb_pages']; $i++) { ?>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nom de la page <?php echo $i + 1 ?> </label>
                <input type="text" class="form-control" id="exampleFormControlInput1"
                    placeholder="(ex: contact) sans .php, .html, etc" name="pages[]"
                    value="<?php if (isset($_POST['pages'][$i])) {
                                                                                                                                                                        echo $_POST['pages'][$i];
                                                                                                                                                                    } ?>">
                <?php
                    if (isset($_POST['reg_generate'])) {
                        if (!isset($_POST['pages'][$i]) or $_POST['pages'][$i] == "") {
                    ?> <span>Nom Manquant *</span> <?php
                                                }
                                            } ?>

            </div>
            <?php } ?>

            <hr>

            <?php
            if ($_SESSION['nb_tables'] > 0) { ?>

            <h2 style="margin-top: 40px;">Base de donnée :</h2>

            <div class="mb-3">


                <label for="exampleFormControlInput1" class="form-label">Nom de la base de donnée </label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nom" name="nom_bd"
                    value="<?php if (isset($_POST['nom_bd'])) {
                                                                                                                                        echo $_POST['nom_bd'];
                                                                                                                                    } ?>"
                    required>
                <label for="exampleFormControlInput1" class="form-label">User </label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="(ex : root)"
                    name="user_bd"
                    value="<?php if (isset($_POST['user_bd'])) {
                                                                                                                                                echo $_POST['user_bd'];
                                                                                                                                            } ?>"
                    required>
                <label for="exampleFormControlInput1" class="form-label">Password </label>
                <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="Password Bd"
                    name="password_bd">

                <label for="exampleFormControlInput1" class="form-label">Hôte </label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="(ex : localhost)"
                    name="hote_bd"
                    value="<?php if (isset($_POST['hote_bd'])) {
                                                                                                                                                    echo $_POST['hote_bd'];
                                                                                                                                                } ?>"
                    required>
            </div>



            <?php for ($i = 0; $i < $_SESSION['nb_tables']; $i++) { ?>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nom de la table <?php echo $i + 1 ?> </label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="(ex : client)"
                    name="tables[]"
                    value="<?php if (isset($_POST['tables'][$i])) {
                                                                                                                                                        echo $_POST['tables'][$i];
                                                                                                                                                    } ?>">
                <?php
                        if (isset($_POST['reg_generate'])) {
                            if (!isset($_POST['tables'][$i]) or $_POST['tables'][$i] == "") {
                        ?> <span>Nom Manquant *</span> <?php
                                                    }
                                                } ?>
            </div>
            <?php }
            } ?>


            <button type="sumbit" class="btn btn-primary" name="reg_generate">Générer</button>

        </form>
    </main>

    <footer>
        <?php include('./partials/footer.php') ?>
    </footer>
</body>

</html>