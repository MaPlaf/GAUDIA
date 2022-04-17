<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>GAUDIA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="../assets/css/style.css" />
        <link rel="icon" type="./image/svg+xml" sizes="32x32" href="../assets/img/icon.svg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Roboto&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/a3ddde716a.js" crossorigin="anonymous"></script>
	</head>

	<body>
        <?php require 'header_nav.php';?>

        <div class="titre">
            <img src="../assets/img/titre_cinema.svg" alt="Page Cinéma" id="cinema_titre">
        </div>

        <?php

            include '../db/database.php';
            global $db;
            $id_user_active = $_SESSION['id_user_active'];
            $classe = null;
            $deja_existe = "";
            $vide ="";
            $listes_listes ="";

            function genere_liste($ordre){
                global $db;
                $id_user_active = $_SESSION['id_user_active'];
                global $listes_listes;
                
                foreach($db->query("SELECT * FROM films_listes WHERE id_user = $id_user_active ORDER BY $ordre") as $row){
                    $listes_listes = $listes_listes . 
                                '<div id="liste_listes">
                                    <a href="cinema_liste.php?id='.$row[0].'&liste='.$row[2].'"> '.$row[2] .'</a>
                                </div>';
                }
            }

            $e = $db->prepare("SELECT * FROM films_listes WHERE id_user = $id_user_active");
            $e->execute();
            $nb_res = $e->rowCount();

            if(($nb_res !== 0)){

                if(isset($_POST['classer_par'])){
                    $classe = $_POST['classer_par'];
                    
                }
                
                switch($classe){
                    case 'date_desc': 
                        genere_liste("date desc");
                        break;

                    case 'date_asc': 
                        genere_liste("date asc");
                        break;

                    case 'alpha_asc': 
                        genere_liste("nom asc");
                        break;

                    case 'alpha_desc': 
                        genere_liste("nom desc");
                        break;

                    default: 
                        genere_liste("date desc");
                        break;
                    }

            }else{
                $vide = "<h3 style='margin-top:5rem;'>Vous n'avez pas encore créé de liste!</h3>";
            }

            if(isset($_POST["add_list"])){

                extract($_POST);

                if(!empty($nom_liste)){

                    $c = $db->prepare("SELECT nom FROM films_listes WHERE nom = :nom AND id_user = :id_user");
                    $c->execute([ 'nom' => $nom_liste, 'id_user' => $id_user_active]);
                    $result = $c->rowCount();
                    $result_c = $c->fetch();

                    if($result == 0){

                        $q = $db->prepare("INSERT INTO films_listes(id_user, nom) VALUES(:id_user, :nom)");
                        $q->execute([
                            'id_user' => $id_user_active,
                            'nom' => $nom_liste,
                            ]);

                        $qq = $db->prepare("SELECT id FROM films_listes WHERE nom = :nom AND id_user = :id_user");
                        $qq->execute([ 'nom' => $nom_liste, 'id_user' => $id_user_active]);
                        $result_qq = $qq->fetch();

                        header("Location: cinema_liste.php?id=".$result_qq['id']."&liste=".$nom_liste."");
                        die();
                                    
                    }else{
                        $deja_existe = "<p style='color:red; text-align:center;'>Cette liste existe déjà!</p>";

                        echo '<script type="text/javascript">function myFunction(){document.getElementById("myModal").style.display = "block";};</script>';
                        echo '<BODY onLoad="myFunction()">';
                    }
                }
            }
        ?>

        <main class="page_main">
            <div id="fait_nonfait">
                <a id="afaire" class="nav_b" href="cinema.php">À FAIRE</a><span> | </span><a id="realise" class="nav_b" href="cinema_realise.php">RÉALISÉS</a>
            </div>

            <div id="options">

                <form name="myform" method="post">
                    <label for="classer_par">Classer par</label>
                    <select name="classer_par" onchange="this.form.submit()">
                        <option value="date_desc"<?php if($classe == "date_desc"){ echo " selected"; }?>> Date (récent->ancien)</option>
                        <option value="date_asc"<?php if($classe == "date_asc"){ echo " selected"; }?>> Date (ancien->récent)</option>
                        <option value="alpha_asc"<?php if($classe == "alpha_asc"){ echo " selected"; }?>> Ordre alphabétique</option>
                        <option value="alpha_desc"<?php if($classe == "alpha_desc"){ echo " selected"; }?>> Ordre alphabétique inverse</option>
                    </select>
                </form>

                <button type="button" id="btn_ouvrir" onclick="ouvrir_modal('myModal');"><span title="Ajouter une liste">+</span></button>

            </div>

            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="ferme_modal('myModal', 0);">&times;</span>

                    <form id="ajout_liste" method="post">
                        <label for="nom_liste">NOM DE LA LISTE</label>
                        <input type="text" name="nom_liste" id="nom_liste" required class="cInput"></br>
                        <?php echo $deja_existe?>
                        <input type="submit" name="add_list" id="add_list" class="bouton_a" value="CRÉER LA LISTE" >
                    </form>
                </div>
            </div>

            <h1 class="titre_liste_a">LISTES À FAIRE</h1>

            <?php echo $vide;?>

            <div id="contenant_liste">
            <?php echo $listes_listes;?>
            </div>
            

        </main>

        <?php require 'footer.php'; ?>

        <script src="../assets/js/main.js"></script>

    </body>
</html> 